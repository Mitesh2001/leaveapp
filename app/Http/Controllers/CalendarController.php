<?php

namespace App\Http\Controllers;

use App\Holiday;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $data['events'] = $this->HolidaysCalender();
        return view("calendar.index")->with($data);
    }

    public function HolidaysCalender()
    {
        $holidays = Holiday::all()->toArray();
        $holiday_arr = array_map(function ($holiday)
        {
            $holiday_date = date('Y-m-d', strtotime($holiday['date']));
            $end_date = date('Y-m-d', strtotime($holiday['end_date']));
            $start_date = $holiday_date ."T00:00:00";
            $end_date = $end_date ."T12:59:59";

            return [
                'title' => $holiday['title'],
                'start' => $start_date,
                'end' => $end_date,
                'description' => $holiday['description'],
                "className" => "fc-event-danger fc-event-solid-warning"
            ];
        },$holidays);
        return response()->json($holiday_arr);
    }
}
