<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use App\Models\LeaveType;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use Datatables;
use Illuminate\Http\Request;
use Mail;
use App\Helpers\Helper;
use App\Models\EmailTemplate;
use Ramsey\Uuid\Uuid;
use App\Notifications\LeaveNotification;
use Illuminate\Support\Facades\Notification;


class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    
    public function __construct()
    {
        $this->middleware('permission:leaves-view', ['only' => ['index']]);
		$this->middleware('permission:leave-create', ['only' => ['create','store']]);
		$this->middleware('permission:leave-update', ['only' => ['edit','update']]);  
    }
    

    public function anyData()
    {
        $leaves = Leave::latest();
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
            ->addColumn('person', function ($leave) {
                $person = User::find($leave->person_id);
                return $person->first_name." ".$person->last_name;
            })
            ->addColumn('description', function ($leave) {
                return $leave->notes;
            })
            ->addColumn('action', function ($leave) {
                $html = "";
                if (auth()->user()->can('leave-update')) {
                    $html .=  '<a href="'.route('leaves.edit',$leave->external_id).'" class="btn btn-outline-primary">
                            <span class="text-dark">
                            <i class="flaticon2-pen"></i>
                            </span>
                        </a>';
                }
                return $html;
            })
            ->rawColumns(['type','start_date','end_date','total_days','person','action','description'])
            ->make(true);
    }
    
    public function index()
    {
        $data['types'] = LeaveType::pluck('name', 'id');
        return view('leave.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['types'] = LeaveType::pluck('name', 'id');
        return view('leave.create')->with($data);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeaveRequest $request)
    { 
        if($request->hasFile('attachment')) {

            $attachment  = strtolower(str_replace(' ', '_',$request->attachment));

            $attachment_name = $attachment ."_". time() .".". $request->attachment->extension(); 
            $path = 'storage/assets/employees/leave_attachments/';
            $returned = $request->attachment->move(public_path($path), $attachment_name); 

            $attachment_full_path = $path . $attachment_name;
        } else {
            $attachment_full_path = "";
        } 

        $leave = [
            'external_id' => Uuid::uuid4()->toString(),
            'person_id' => $request->person,
            'user_id' =>auth()->user()->id,
            'type_id' =>$request->type,
            'start_date' =>$request->start_date,
            'end_date' =>$request->end_date,
            'total_days' =>$request->total_days,
            'notes' =>$request->notes,
            'attachment' =>$attachment_full_path
        ];

        $leave = Leave::create($leave);

        $this->sendLeaveNotificationToUser($leave);

        Session()->flash('flash_message', __('Leave successfully Created !'));
        return redirect()->route('leaves.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show(Leave $leave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    
    public function edit($external_id)
    {
        $data['types'] = LeaveType::pluck('name', 'id');
        $data['leave'] = Leave::where('external_id',$external_id)->first();
        $data['person_id'] =  $data['leave']->person_id;
        $person = User::find($data['person_id']);
        $data['person_name'] = $person->first_name." ".$person->last_name;
        return view('leave.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function update($external_id, Request $request)
    {
        $leave = Leave::where('external_id',$external_id)->first();
        if($request->hasFile('attachment')) {

            $attachment  = strtolower(str_replace(' ', '_',$request->attachment));

            $attachment_name = $attachment ."_". time() .".". $request->attachment->extension(); 
            $path = 'storage/assets/employees/leave_attachments/';
            $returned = $request->attachment->move(public_path($path), $attachment_name); 

            $attachment_full_path = $path . $attachment_name;
        } else {
            $attachment_full_path = "";
        } 
        $updated_leave = [
            'person_id' => $request->person,
            'user_id' =>auth()->user()->id,
            'type_id' =>$request->type,
            'start_date' =>$request->start_date,
            'end_date' =>$request->end_date,
            'total_days' =>$request->total_days,
            'notes' =>$request->notes,
            'attachment' =>$attachment_full_path
        ];

        $leave->update($updated_leave);

        $this->sendLeaveNotificationToUser($leave);

        Session()->flash('flash_message', __('Leave successfully Updated !'));
        return redirect()->route('leaves.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leave $leave)
    {
        //
    }

    public function sendLeaveNotificationToUser($leave)
    {   
        $person = User::find($leave->person_id);
        $start_date = $leave->start_date;
        $end_date = $leave->end_date;
        $total_days = $leave->total_days;
        if ($total_days > 1) {
            $date_message = "From <b>".$start_date."</b> to <b>".$end_date."</b>";
        } else {
            $date_message = "At date <b>".$start_date."</b> ";
        }
        
        $leave_type = LeaveType::find($leave->type_id);

        $data['title'] = "Leave Notification";
        $data['link'] = "manage_leaves.index";
        $data['message'] = $date_message." You are on <b>".$leave_type->name."</b> leave for <b>".$total_days."</b> day(s), please select your clients whom you want to notifie that and select any alternate person who will handle your work in your absence !";
        Notification::send($person, new LeaveNotification($data));
        
        $data['message'] = $date_message.auth()->user()->first_name." ".auth()->user()->last_name." will on <b>".$leave_type->name."</b> leave for <b>".$total_days."</b> day(s)";
        Helper::notifyToOwner($data);
    }

    
    public function personsList(Request $request)
    {
        $name = $request->get('q');
        $admins = User::where('first_name', 'like', "%{$name}%")->whereHas(
            'roles', function($q){
                $q->where('name', 'admin');
            }
        )->get();
        return response()->json($admins);
    }

}
