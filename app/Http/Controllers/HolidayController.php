<?php

namespace App\Http\Controllers;

use App\Holiday;
use App\Http\Requests\HolidayStoreRequest;
use Illuminate\Http\Request;
use Datatables;
use Ramsey\Uuid\Uuid;

class HolidayController extends Controller
{

    public $days = [
        'Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
    ];

    public function __construct()
    {
        $this->middleware('permission:holidays-view', ['only' => ['index']]);
		$this->middleware('permission:holiday-create', ['only' => ['create','store']]);
		$this->middleware('permission:holiday-update', ['only' => ['edit','update']]);  
    }

    

    public function anydata()
    {
        $holidays = Holiday::latest();
        
        return Datatables::of($holidays)
            ->addColumn('title', function ($holidays) { 
                return $holidays->title;
            })
            ->addColumn('date', function ($holidays) {
                return $holidays->date ." (".$this->days[$holidays->day].")";
            })
            ->addColumn('end_date', function ($holidays) {
                return $holidays->end_date;
            })             
            ->addColumn('description', function ($holidays) {
                return $holidays->description;
            })
            ->addColumn('action', function ($holidays) {
                $edit_url = route('holidays.edit', $holidays->external_id);    
                $html ="";
                // $html = '<form action="'.route('holidays.destroy', $holidays->external_id).'" method="POST">'; 
                // $html .= '<button type="button" name="submit" value="' . __('Delete') . '" class="btn btn-link"  data-toggle="tooltip" title="Delete Holiday"><i class="flaticon2-trash text-danger text-hover-warning"></i></button>';              
                // $html .= '<input type="hidden" class="user_id" value="'.$holidays->external_id.'">'; 
				// $html .= csrf_field();
				// $html .= '</form>';
                $html .= '<a href="'.$edit_url.'" class="btn btn-link" data-toggle="tooltip" title="Edit Holiday"><i class="flaticon2-pen text-success text-hover-primary"></i></a>';
                return $html;
            }) 
            ->rawColumns(['title', 'date','end_date', 'email', 'description', 'action'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("holidays.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['days'] = $this->days;
        return view("holidays.create")->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HolidayStoreRequest $request)
    {
        $holiday = [
            'external_id' => Uuid::uuid4()->toString(),
            'title' => $request->title,
            'date' => $request->date,
            'end_date' => $request->end_date,
            'day' => $request->day,
            'description' => $request->description
        ];

        Holiday::create($holiday);

        Session()->flash('flash_message', __('Holiday successfully Created!'));

        return redirect()->route("holidays.index") ;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit($external_id)
    {
        $data['days'] = $this->days;
        $data['holiday'] = $this->findByExternalId($external_id);
        return view("holidays.edit")->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update($external_id, Request $request)
    {
        $holiday = $this->findByExternalId($external_id);
        $updated_holiday = [
            'title' => $request->title,
            'date' => $request->date,
            'end_date' => $request->end_date,
            'day' => $request->day,
            'description' => $request->description
        ];
        $holiday->fill($updated_holiday)->save(); 

        Session()->flash('flash_message', __('Holiday successfully updated!'));
        return redirect()->route("holidays.index") ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        // return $holiday;
    }

    public function findByExternalId($external_id)
    {
        return Holiday::where('external_id',$external_id)->first();
    }
}
