<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use  App\Http\Requests\StoreLeaveTypeRequest;
use  App\Http\Requests\UpdateLeaveTypeRequest;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Datatables;

class LeaveTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:leave-types-view', ['only' => ['index']]);
		$this->middleware('permission:leave-types-create', ['only' => ['create','store']]);
		$this->middleware('permission:leave-type-update', ['only' => ['edit','update']]);  
    }

    public function indexData()
    {
        $leave_types = LeaveType::all();

        return Datatables::of($leave_types)
            ->addColumn('name', function ($leave_types) {
                return $leave_types->name;
            })
            ->addColumn('description', function ($leave_types) {
                return $leave_types->description;
            })
            ->addColumn('actions', function ($leave_types) {
                $html = "";
                if (auth()->user()->can('leave-type-update')) {
                    $html .=  '<a href="'.route('leave-types.edit', $leave_types->external_id).'"><i class="flaticon2-pen text-success text-hover-primary"></i></a>';
                }
                return $html;
            })
            ->rawColumns(['name','description','actions'])
            ->make(true);
    }
    public function findByExternalId($external_id)
    {
        return LeaveType::where('external_id', $external_id)->firstOrFail();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('leave_types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leave_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeaveTypeRequest $request)
    {
        $leave_type = LeaveType::create([
            'external_id' => Uuid::uuid4()->toString(),
            'name' => $request->name,
            'description' => $request->description
        ]);
        Session()->flash('flash_message', __('Leave successfully Created'));
        return redirect()->route('leave-types.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveType $leaveType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function edit($external_id)
    {
        $data['leave_type'] = LeaveType::where('external_id', $external_id)->first();
        return view('leave_types.edit')->with($data); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType, $external_id)
    {
        $leave_type = $this->findByExternalId($external_id);
        $update_arr = [
            'name' => $request->name,
            'description' =>$request->description
        ]; 

        $leave_type->fill($update_arr)->save();
        
        Session()->flash('flash_message', __('Leave successfully updated'));
        return redirect()->route('leave-types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveType $leaveType)
    {
        //
    }
}
