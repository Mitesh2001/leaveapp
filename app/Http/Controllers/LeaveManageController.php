<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Client;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\LeaveManage;
use App\Models\User;
use App\Models\Leave;
use App\Models\LeaveType;
use Datatables;
use Mail;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Notifications\LeaveNotification;
use Illuminate\Support\Facades\Notification;


class LeaveManageController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:leave-manage', ['only' => ['index','manage']]);  
    }

    
    public function anyData()
    {
        $user_id = auth()->id();
        $leaves = Leave::where('person_id', $user_id)->latest();
        return Datatables::of($leaves)
            ->addColumn('type', function ($leave) {
                $type = LeaveType::find($leave->type_id);
                return $type->name;
            })
            ->addColumn('start_date', function ($leave) {
                return $leave->start_date;
            })
            ->addColumn('end_date', function ($leave) {
                return $leave->end_date;
            })
            ->addColumn('total_days', function ($leave) {
                return $leave->total_days;
            })
            ->addColumn('clients_list', function ($leaves) {
                if ($leaves->leaveManage) {
                    $clients = Client::whereIn('id', explode(",",$leaves->leaveManage->clients_list))->get();
                    $html = "";
                    foreach ($clients as $key => $client) {
                        $url = route('clients.show', $client->external_id);
                        $html .= '<a data-search="' . $client->name . '" href="'.$url.'" ">' . $client->name . '</a><br>';                        
                    }
                    return $html;
                }
                else {
                    return "<p class='text-danger'>Pending...</p>";
                }
            })
            ->addColumn('alternate_person', function ($leaves) {
                if ($leaves->leaveManage) {
                    $alternate_person = User::find($leaves->leaveManage->alternate_person);
                    return $alternate_person->first_name." ".$alternate_person->last_name;
                }
                else {
                    return "<p class='text-danger'>Pending...</p>";
                }
            })
            ->addColumn('manage', function ($leave) {
                if ($leave->leaveManage) {
                    $html =  '<a href="'.route('manage_leaves.edit', $leave->leaveManage->external_id).'" class="btn btn-success">
                        
                            <i class="flaticon2-pen" data-toggle="tooltip" title="Edit Details"></i> Edit
                        
                    </a>';
                } else {
                    $html =  '<a href="'.route('manage_leaves.manage',$leave->external_id).'" class="btn btn-outline-danger" >
                        <span class="text-dark">
                            <i class="flaticon2-exclamation"></i> Manage Leave
                        </span>
                    </a>';
                }
                return $html;
            })
            ->rawColumns(['type','start_date','end_date','total_days','manage','clients_list','alternate_person'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("leave_management.index");
    }

    public function manage($external_id)
    {
        $leave = $this->findLeaveByExternalId($external_id);
        $data['leave'] = $leave;
        $data['email_templates'] = EmailTemplate::pluck('name','email_template_id');
        return view("leave_management.manage_leave")->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $leave = $this->findLeaveByExternalId($request->leave_external_id);
        $clients = $request->clients;
        $clients_list = implode (",",$clients);

        $this->sendLeaveNotificationEmailToClients($request->alternate_person,$leave,$clients,$request->email_template); 

        $this->sendNotificationToAlternatePerson($leave,$request->alternate_person);

        $data = [
            'external_id' => Uuid::uuid4()->toString(),
            'leave_id' => $leave->id,
            'email_template_id' => $request->email_template,
            'clients_list' => $clients_list,
            'alternate_person' => $request->alternate_person
        ];
        $managedData = LeaveManage::create($data);

        Session()->flash('flash_message', __('Notifications sent successfully !'));
        return redirect()->route('manage_leaves.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeaveManage  $leaveManage
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveManage $leaveManage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeaveManage  $leaveManage
     * @return \Illuminate\Http\Response
     */
    public function edit($external_id)
    {
        $leave_manage = LeaveManage::where('external_id', $external_id)->first();
        $data['leave_manage'] = $leave_manage;
        $data['leave'] = $leave_manage->leave;
        $data['clients'] = Client::WhereIn('id',explode(",",$leave_manage->clients_list))->get();
        $data['alternate_person'] = User::find($leave_manage->alternate_person);
        $data['email_templates'] = EmailTemplate::pluck('name','email_template_id');
        return view("leave_management.edit_leave_manage")->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LeaveManage  $leaveManage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveManage $leaveManage)
    {
        $leave = $this->findLeaveByExternalId($request->leave_external_id);
        $clients = $request->clients;
        $clients_list = implode (",",$clients);

        // $this->sendLeaveNotificationEmailToClients($request->alternate_person,$leave,$clients,$request->email_template); 

        // $this->sendNotificationToAlternatePerson($leave,$request->alternate_person);

        $data = [
            'clients_list' => $clients_list,
            'email_template_id' => $request->email_template,
            'alternate_person' => $request->alternate_person
        ];

        $leave->leaveManage->update($data);

        Session()->flash('flash_message', __('Data Updated successfully !'));
        return redirect()->route('manage_leaves.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeaveManage  $leaveManage
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveManage $leaveManage)
    {
        //
    }

    public function sendNotificationToAlternatePerson($leave,$alternate_person)
    {   
        $data['title'] = "Leave Alternate Person !";
        $data['message'] = "You are selected as Alernate person ! Manage Your Tasks at Tasks Page !";

        $person = User::find($alternate_person);
        Notification::send($person, new LeaveNotification($data));

        $data['message'] = auth()->user()->first_name." has assigned their clients to alternate person to attend on theirself in their absence !";
        Helper::notifyToOwner($data);

    }

    public function sendLeaveNotificationEmailToClients($alternate_person,$leave,$client_ids,$email_template_id)
    {
        $email_template = EmailTemplate::where('email_template_id',$email_template_id)->first();
        $template_content = $email_template->content;
        $alternate_person = User::find($alternate_person);
        $alternate_person_name = $alternate_person->first_name." ".$alternate_person->last_name;
        $employee_on_leave = User::find(auth()->user()->id);
        $employee_name_on_leave = $employee_on_leave->first_name." ".$employee_on_leave->last_name;
        
        $template_variables = [
            '{{#start_date}}' => $leave->start_date,
            '{{#end_date}}' => $leave->end_date,
            '{{#total_days}}' => $leave->total_days,
            '{{#leave_type}}' => $leave->LeaveType->name, 
            '{{#alternate_person}}' => $alternate_person_name,
            '{{#employee_on_leave}}' => $employee_name_on_leave
        ];

        foreach ($template_variables as $key => $value)
            $template_content = str_replace($key, $value, $template_content);

        $data['subject'] = $email_template->subject;
        $data['messagecontent'] = $template_content;
        $data['from_email'] = env('MAIL_FROM_EMAIL');
        $data['from_name'] = env('MAIL_FROM_NAME');        
        
        foreach ($client_ids as $key => $client_id) {
            $client = Client::find($client_id);

            $email = $client->primary_email ?? $client->secondary_email ;

            $emailSend = Mail::send('emails.email', $data, function($message) use($data, $email) {
                $message->subject($data['subject']);
                
                if(isset($data['from_email']) && isset($data['from_name'])){
                    $message->from($data['from_email'], $data['from_name']);
                }
                $message->to($email);
            }); 
            
            $emailLogArray = [
                'template_id' => $email_template_id,
                'client_id' => $client_id,
                'client_email' => $email,
                'email_subject' => $data['subject'],
                'from_email' => env('MAIL_FROM_ADDRESS'),
                'from_name' => env('MAIL_FROM_NAME'),
                'email_json' => json_encode($template_content)
            ];
                       
            EmailLog::create($emailLogArray);
                
        }

    }

    public function personsList(Request $request)
    {
        $name = $request->get('q');
        $list = User::where('first_name', 'like', "%{$name}%")->where('last_name', 'like', "%{$name}%")
        ->whereHas(
                'roles', function($q){
                    $q->where('user_type', 3);
                }
            )->get();
        return response()->json($list);
    }

    public function getClients(Request $request)
    {
        $name = $request->get('q');
        $clients = Client::where('name', 'like', "%{$name}%")
        ->whereHas(
            'users', function($q) {
                $q->where('user_id',auth()->id());
            }
        )->get();
        return response()->json($clients);
    }

    public function findLeaveByExternalId($external_id)
    {
        return Leave::where('external_id',$external_id)->first();
    }
}
