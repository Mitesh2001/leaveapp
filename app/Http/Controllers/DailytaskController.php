<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Dailytask;
use App\Models\TaskLeave;
use App\Models\User;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailytaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:see-employee-tasks', ['only' => ['employeeDailyTasks']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function tasksData(Request $request)
    {
        $reservation = $request->dates;
        $reservation = explode('-',$reservation);
        $start_date = date("Y-m-d",strtotime(trim($reservation[0])));
        $end_date   = date("Y-m-d",strtotime(trim($reservation[1])));
        
        $dates = $this->getBetweenDates($start_date,$end_date);

        $html = '';
        
        foreach ($dates as $key => $date) {

            $tasks = Dailytask::where('user_id',auth()->id())->whereDate('created_at', '=', $date)->get();
            $leave = TaskLeave::where('user_id',auth()->id())->whereDate('created_at', '=', $date)->first();

            $hours = $minutes = 0;
            if (count($tasks) > 0) {
                $html .= '<tr>';
                $html .= '<td>';
                
                $html .= '<div class="collapse" id="collapseExample'.$key.'">';

                foreach ($tasks as $task) {
                    $html .= '<div class="row border border-bottom">
                        <h6 class="col-md-4 btn btn-link text-center">'.$task->project_name.'</h6>
                        <div class="col-md-8">
                            <p>'.$task->task_description.'</p>
                            '.$task->task_hours.'
                        </div>
                    </div> ';
                    $taskhours = explode(':',$task->task_hours);
                    $hours += $taskhours[0];
                    $minutes += $taskhours[1];
                }
                
                $totmin = $minutes/60;
                $totmin = floor($totmin);
                $tothr = $hours + $totmin;
                $totmin = $minutes%60;

                $html .= '</div>';
                $html .= '<div class="d-flex my-2 justify-content-between"> 
                        <b>Total Hours of Working : '.str_pad($tothr,2,"0",STR_PAD_LEFT).":".str_pad($totmin,2,"0",STR_PAD_LEFT).'</b>                           
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample'.$key.'" aria-expanded="false" aria-controls="collapseExample'.$key.'">
                            View All Tasks  
                        </button>';
                $html .= '</td>';
                $html .= '<td>'.$date.'<br>';
                if ($leave) {
                    $html .= '<b>'.$leave->leave_type.' Taken</b></div>';                
                }
                $html .= '</td></tr>';
            }
        }

        return response()->json(['html' => $html]);
    }

    public function EmployeetasksData(Request $request)
    {
        $reservation = $request->dates;
        $user_id = $request->user_id;
        $reservation = explode('-',$reservation);
        $start_date = date("Y-m-d",strtotime(trim($reservation[0])));
        $end_date   = date("Y-m-d",strtotime(trim($reservation[1])));
        
        $dates = $this->getBetweenDates($start_date,$end_date);
        $html = '';
        
        foreach ($dates as $key => $date) {

            // if (Helper::getUserType(auth()->id()) == 'hr' || Helper::getUserType(auth()->id()) == 'super_admin') {
            //     $user_ids = User::pluck('id');
            //     $tasks = Dailytask::where('user_id','!=',auth()->id())->whereDate('created_at', '=', $date)->get();
            // } else if(Helper::getUserType(auth()->id()) == 'admin'){
            //     $user_ids = User::where('employer_id',auth()->id())->pluck('id');
            //     return $tasks = DB::table('dailytasks')->whereIn('user_id',$user_ids)->whereDate('created_at',date_create($date))->get();
            // } 
            
            $tasks = Dailytask::where('user_id',$user_id)->whereDate('created_at', '=', $date)->get();

            $leave = TaskLeave::where('user_id',$user_id)->whereDate('created_at', '=', $date)->first();

            $hours = $minutes = 0;
            if (count($tasks) > 0) {
                $html .= '<tr>';
                $html .= '<td>';
                
                $html .= '<div class="collapse" id="collapseExample'.$key.'">';

                foreach ($tasks as $task) {
                    $html .= '<div class="row border border-bottom">
                        <h6 class="col-md-2 text-center">'.$task->project_name.'</h6>
                        <div class="col-md-8">
                            <p>'.$task->task_description.'</p>
                        </div>
                        <div class="col-md-2">'.$task->task_hours.'</div>';
                    $html .=   '</div> ';
                    $taskhours = explode(':',$task->task_hours);
                    $hours += $taskhours[0];
                    $minutes += $taskhours[1];
                }
                
                $totmin = $minutes/60;
                $totmin = floor($totmin);
                $tothr = $hours + $totmin;
                $totmin = $minutes%60;

                $html .= '</div>';
                $html .= '<div class="d-flex my-2 justify-content-between"> 
                        <b>Total Hours of Working : '.str_pad($tothr,2,"0",STR_PAD_LEFT).":".str_pad($totmin,2,"0",STR_PAD_LEFT).'</b>                           
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample'.$key.'" aria-expanded="false" aria-controls="collapseExample'.$key.'">
                            View All Tasks   
                        </button>';
                $html .= '</td>';
                $html .= '<td>'.$date.'<br>';
                if ($leave) {
                    $html .= '<b>'.$leave->leave_type.' Taken</b></div>';                
                }
                $html .= '</td></tr>';
            }
        }

        return response()->json(['html' => $html]);
    }

    public function index()
    {
        return view('daily-tasks.add_tasks');
    }

    public function dailyTasksList()
    {
        return view('daily-tasks.show_tasks');
    }

    public function employeeDailyTasks()
    {
        if (Helper::getUserType(auth()->id()) == 'hr' || Helper::getUserType(auth()->id()) == 'super_admin') {
            $users = User::all();
        } else if(Helper::getUserType(auth()->id()) == 'admin'){
            $users = User::where('employer_id',auth()->id())->get();
        }
        return view('daily-tasks.employee_daily_tasks')->with(['users' => $users]);
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
        $user_id = auth()->id();
        $project_name = $request->project_name;
        $taskhours = $request->hours;
        $taskminutes = $request->minutes;
        $taskdescriptions = $request->description;
        
        foreach ($project_name as $key => $value) {
            Dailytask::create([
                'user_id' => $user_id,
                'project_name' => $value,
                'task_description' => $taskdescriptions[$key],
                'task_hours' => $taskhours[$key].":".$taskminutes[$key]
            ]);
        }

        if ($request->leavetaken) {
            TaskLeave::create([
                'user_id' => $user_id,
                'leave_type' => $request->leavetaken
            ]);
        }

        Session()->flash('flash_message', __('Tasks successfully Submited !'));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dailytask  $dailytask
     * @return \Illuminate\Http\Response
     */
    public function show(Dailytask $dailytask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dailytask  $dailytask
     * @return \Illuminate\Http\Response
     */
    public function edit(Dailytask $dailytask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dailytask  $dailytask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dailytask $dailytask)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dailytask  $dailytask
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dailytask $dailytask)
    {
        //
    }

    function getBetweenDates($startDate, $endDate)
    {
        $rangArray = [];
            
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
             
        for ($currentDate = $startDate; $currentDate <= $endDate; 
                                        $currentDate += (86400)) {
                                                
            $date = date('Y-m-d', $currentDate);    
            $rangArray[] = $date;
        }
        
        return $rangArray;
    }
}
