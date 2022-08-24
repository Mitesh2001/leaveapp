<?php
namespace App\Http\Controllers;

use Auth;

use App\Models\Client;
use App\Models\User;
use Carbon\CarbonPeriod;
use DB;

use App\Helpers\Helper;
use App\Holiday;
use App\Models\Leave;
use App\Models\LeaveManage;
use App\Models\LeaveType;

class PagesController extends Controller
{
    /**
     * Dashobard view
     * @return mixed
     */
    public $colors = [
    'primary',
    'success',
    'danger',
    'warning',
    'info',
    'light bg-dark',
    'dark'
    ];

    public function dashboard(){
        if (auth()->user()->roles->first()->user_type == 3) {
           $data['tasks'] = $this->tasks(); 
        }

        if (auth()->user()->roles->first()->user_type == 0 || auth()->user()->roles->first()->user_type == 1){
            $data['leavemanages'] = $this->pendingLeaveManages();
        }
        $data['leaves'] = $this->todaysLeave();
        $data['colors'] = $this->colors;
        $data['leave_types'] = $this->leaveTypes();
        $data['holidays'] = $this->nextHolidays();
        return view('pages.dashboard')->with($data);            
    }

    public function leaveTypes()
    {
        return LeaveType::all();
    }

    public function nextHolidays()
    {
        return $holidays = Holiday::whereDate('date','>=',date("Y-m-d"))->whereMonth('date',date("m"))->orWhereMonth('date',date("m")+1)->orderBy('date','asc')->get();
    }

    public function todaysLeave()
    {
        $leaves = DB::table('leaves')
        ->whereDate('start_date','<=',date("Y-m-d"))
        ->whereDate('end_date','>=',date("Y-m-d"))
        ->get();
        $data = [];
        $types = $this->leaveTypes();
        foreach ($types as $key => $type) {
            foreach ($leaves as $key => $leave) {
                if ($leave->type_id == $type->id) {
                    $data[$type->name][] = $leave;
                }
            }
        }
        return $data;
    }

    public function pendingLeaveManages()
    {
        if (auth()->user()->roles->first()->user_type == 0){
            $leaves = Leave::all();
        } else if(auth()->user()->roles->first()->user_type == 1 ) {
            $user_id = auth()->id();
            $leaves = Leave::where('person_id', $user_id)->get();
        }
        
        $leavesManages = [];
        foreach($leaves as $leave) {
            if ($leave->leaveManage) {
                $leavesManages[] = 1;
            } else {
                $leavesManages[] = 0;
            }
        }
        $total = count($leavesManages);
        $done = isset(array_count_values($leavesManages)[1]) ? array_count_values($leavesManages)[1] : 0;
        
        $myLeaves = [];
        $myLeaves['totalCount'] = $total;
        $myLeaves['doneCount'] = $done;
        $myLeaves['managedPercentage'] = $total != 0 ? ($done*100)/$total : 0;
        $myLeaves['pendingPercentage'] = $total != 0 ? (($total-$done)*100)/$total : 0;
        return $myLeaves;
    }

    public function tasks()
    {
        if (auth()->user()->roles->first()->user_type == 3) {
            return $relatedLeaveManages = LeaveManage::where('alternate_person',auth()->id())
            ->whereHas('leave',function ($q)
            {
                $q->whereDate('start_date','>=',date("Y-m-d"));
            })
            ->with('leave')
            ->get();
        }
    }
}
