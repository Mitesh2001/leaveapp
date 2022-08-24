<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\TaskCreateRequest;
use App\Models\Client;
use App\Models\Leave;
use App\Models\LeaveManage;
use App\Models\Task;
use App\Models\User;
use Datatables;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid; 
use App\Notifications\LeaveNotification;
use Illuminate\Support\Facades\Notification;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tasks-view', ['only' => ['index', 'show']]);
		$this->middleware('permission:task-create', ['only' => ['create','store']]);  
		$this->middleware('permission:submited-tasks-view', ['only' => ['submitedTasks']]);  
    }

    public function anyData()
    {
        $relatedLeaveManages = LeaveManage::where('alternate_person',auth()->id())->with('leave')->latest();
        return Datatables::of($relatedLeaveManages)
            ->addColumn('person', function ($relatedLeaveManages) {
                $person = User::find($relatedLeaveManages->leave->person_id);
                return $person->first_name." ".$person->last_name;
            })
            ->addColumn('dates', function ($relatedLeaveManages) {
                $leave = $relatedLeaveManages->leave;
                return $leave->start_date." to ".$leave->start_date." [".$leave->total_days." day(s)]";
            })
            ->addColumn('tasks', function ($relatedLeaveManages) {
                $html = "";
                if ($relatedLeaveManages->leave->start_date < date("Y-m-d")) {
                    return "<button class='btn btn-danger' disabled>Expired !</button>";
                }
                else if ($tasks = Task::where('leave_id',$relatedLeaveManages->leave->id)->get()->count()>0) {
                    if (auth()->user()->can('task-update')) {
                        $html .=  '<a href="'.route('tasks.my-work',$relatedLeaveManages->external_id).'" class="btn btn-outline-primary">
                            <span class="text-dark">
                            <i class="flaticon2-pen"></i> Edit Tasks
                            </span>
                        </a>';
                    }
                } else {
                    $html = "";
                    if (auth()->user()->can('task-create')) {
                        $html =  '<a href="'.route('tasks.my-work',$relatedLeaveManages->external_id).'" class="btn btn-outline-primary">
                            <span class="text-dark">
                            <i class="ki ki-plus "></i> Add Tasks
                            </span>
                        </a>';
                    }                   
                }
                return $html;
            })
            ->rawColumns(['person','dates','tasks'])
            ->make(true);
    }

    public function completedTaskData()
    {
        $tasks =  auth()->user()->tasks;
        return Datatables::of($tasks)
            ->addColumn('task_details', function ($tasks) {
                $html = "<b>".$tasks->title."</b>";
                $html .= "<br>";
                $html .= $tasks->description;
                return $html;
            })  
            ->addColumn('client', function ($tasks) {
                $client = Client::find($tasks)->first();
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
            ->rawColumns(['person','task_details','client','time','date'])
            ->make(true);
    }

    public function submitedTaskData()
    {
        $tasks = Task::where('behalf_of',auth()->id())->latest();
        if (auth()->user()->roles->first()->user_type == 3) {
            $tasks =  auth()->user()->tasks;
        }
        return Datatables::of($tasks)
            ->addColumn('task_details', function ($tasks) {
                $html = "<b>".$tasks->title."</b>";
                $html .= "<br>";
                $html .= $tasks->description;
                return $html;
            })  
            ->addColumn('client', function ($tasks) {
                $client = Client::find($tasks)->first();
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
                $person = User::find($tasks->done_by);
                return $person->first_name." ".$person->last_name;
            })
            ->addColumn('behalf_of', function ($tasks) {
                $behalfOf = User::find($tasks->behalf_of);
                return $behalfOf->first_name." ".$behalfOf->last_name;
            })
            ->rawColumns(['person','task_details','behalf_of','client','time','date'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("tasks.index");
    }

    public function works($external_id)
    {
        $leavemanage = LeaveManage::where('external_id',$external_id)->first();
        
        $clients = DB::table('clients')->whereIn('id',explode(",",$leavemanage->clients_list))->pluck('name','id');
        $person = User::find($leavemanage->leave->person_id);
        $admin =  $person->first_name." ".$person->last_name;
        $data['leaveManage'] = $leavemanage;
        $data['clients'] = $clients;
        $data['admin'] = $admin;
        $data['person'] = $person;
        $tasks = Task::where('leave_id',$leavemanage->leave->id)->get();
        if ($tasks->count()>0) {
            $data['tasks'] = $tasks;
            $data['leavemanage']  = LeaveManage::where('external_id',$external_id)->first();
            return view("tasks.edit_work")->with($data);
        }
        return view("tasks.works")->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("tasks.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskCreateRequest $request)
    {
        $behalf_of = $request->behalf_of;
        if ($request->title) {
            foreach ($request->title as $key => $task) {
                $taskArray = [
                    'external_id' => Uuid::uuid4()->toString(),
                    'title' => $task,
                    // 'behalf_of' => (int)$behalf_of,
                    'behalf_of' => (int)$behalf_of,
                    'client_id' => $request->client[$key],
                    'leave_id' => $request->leave_id,
                    'done_by' => Auth::id(),
                    'description' => $request->description[$key],
                    'time_taken' => json_encode(['hours' => $request->hours[$key], 'minutes' =>$request->minutes[$key]]),
                    'project_id' => 0
                ];
                Task::create($taskArray);        
            }
            $this->sendNotificationsToAdmin($behalf_of);
        }

        Session()->flash('flash_message', __('Tasks successfully submitted'));
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    public function submitedTasks()
    {
        return view("tasks.submited_tasks");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $external_id)
    {
        $leavemanage = LeaveManage::where('external_id',$external_id)->first();
        $tasks = Task::where('leave_id',$leavemanage->leave->id)->get();
        foreach($tasks as $task){
            $task->delete();
        }
        $behalf_of = $request->behalf_of;
        if ($request->title) {
            foreach ($request->title as $key => $task) {
                $taskArray = [
                    'external_id' => Uuid::uuid4()->toString(),
                    'title' => $task,
                    // 'behalf_of' => (int)$behalf_of,
                    'behalf_of' => (int)$behalf_of,
                    'client_id' => $request->client[$key],
                    'leave_id' => $request->leave_id,
                    'done_by' => Auth::id(),
                    'description' => $request->description[$key],
                    'time_taken' => json_encode(['hours' => $request->hours[$key], 'minutes' =>$request->minutes[$key]]),
                    'project_id' => 0
                ];
                Task::create($taskArray);
            }
            $this->sendNotificationsToAdmin($behalf_of);
        }

        Session()->flash('flash_message', __('Tasks successfully Updated'));
        return redirect()->route('tasks.index'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }

    public function sendNotificationsToAdmin($admin_id)
    {
        $user = auth()->user();
        $data['title'] = "Task submitted !";
        $data['message'] = $user->first_name." ".$user->last_name." have attend clients on behalf of you ! See what they've done !";
        $admin = User::find($admin_id);
        Notification::send($admin, new LeaveNotification($data));

        $data['message'] = $user->first_name." ".$user->last_name." have attend clients on behalf of ".$admin->first_name." ".$admin->last_name." ! See what they've done !";

        Helper::notifyToOwner($data);
    }
}
