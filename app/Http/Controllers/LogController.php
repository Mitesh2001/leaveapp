<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Task;
use App\Models\User;
use Datatables;
use Illuminate\Http\Request;

class LogController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:log-access', ['only' => ['leaves','emails', 'tasks']]);
    }

    public function leaveData()
    {
        $leaves = Leave::with('leaveManage')->latest();
        return Datatables::of($leaves)
            ->addColumn('type', function ($leaves) {
                $type = LeaveType::find($leaves->type_id);
                return $type->name;
            })
            ->addColumn('start_date', function ($leaves) {
                return $leaves->start_date;
            })
            ->addColumn('end_date', function ($leaves) {
                return $leaves->end_date;
            })
            ->addColumn('total_days', function ($leaves) {
                return $leaves->total_days;
            })
            ->addColumn('person', function ($leaves) {
                $person = User::find($leaves->person_id);
                return $person->first_name." ".$person->last_name;
            })
            ->addColumn('clients_list', function ($leaves) {
                if ($leaves->leaveManage) {
                    $clients = Client::whereIn('id', explode(",",$leaves->leaveManage->clients_list))->get();
                    $html = "";
                    foreach ($clients as $key => $client) {
                        $url = route('clients.show', $client->external_id);
                        $html .= '<a data-search="' . $client->name . '" href="'.$url.'" ">' . $client->name . '</a><hr>';                        
                    }
                    return $html;
                }
            })
            ->addColumn('alternate_person', function ($leaves) {
                if ($leaves->leaveManage) {
                    $alternate_person = User::find($leaves->leaveManage->alternate_person);
                    return $alternate_person->first_name." ".$alternate_person->last_name;
                }
            })
            ->rawColumns(['type','start_date','end_date','total_days','person','alternate_person','clients_list'])
            ->make(true);
    }

    public function emailsData()
    {
        $logs = EmailLog::latest();
        return Datatables::of($logs)
            ->addColumn('template', function ($logs) {
                return EmailTemplate::find($logs->template_id)->name;
            })
            ->addColumn('client', function ($logs) {
                $client = Client::find($logs->client_id);
                $html = "";                
                $url = route('clients.show', $client->external_id);
                $html .= '<a data-search="' . $client->name . '" href="'.$url.'" ">' . $client->name . '</a><hr>';                        
                return $html;
            })
            ->addColumn('date_time', function ($logs) {
                return $logs->created_at->format("j M Y h:i:s A");
            })
            ->rawColumns(['template','client','email_subject','client_email','date_time'])
            ->make(true);
    }

    public function tasksData()
    {
        $tasks =  Task::latest();
        return Datatables::of($tasks)
            ->addColumn('task_details', function ($tasks) {
                $html = "<b>".$tasks->title."</b>";
                $html .= "<br>";
                $html .= $tasks->description;
                return $html;
            })  
            ->addColumn('client', function ($tasks) {
                $client = Client::find($tasks->client_id)->first();
                return $client->name;
            }) 
            ->addColumn('time', function ($tasks) {
                $time = $tasks->time_taken;
                return json_decode($time)->hours." Hours & ".json_decode($time)->minutes." minutes";
            })
            ->addColumn('date', function ($tasks) {
                $details = $tasks->created_at->diffForHumans();
                return $tasks->created_at->format('m-d-Y')." (".$details.")";
            })
            ->addColumn('person', function ($tasks) {
                $person = User::find($tasks->behalf_of);
                return $person->first_name." ".$person->last_name;
            })
            ->addColumn('done_by', function ($tasks) {
                $done_by = User::find($tasks->done_by);
                return $done_by->first_name." ".$done_by->last_name;
            })
            ->rawColumns(['person','task_details','client','time','date','done_by'])
            ->make(true);
    }

    public function leaves()
    {
        $data['types'] = LeaveType::pluck('name', 'id');
        return view("Logs.leaves")->with($data);
    }

    public function emails()
    {
        return view("Logs.emails");
    }
    public function tasks()
    {
        return view("Logs.tasks");
    }
}
