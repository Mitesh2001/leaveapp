{{-- Extends layout --}}
@extends('layouts.default')

{{-- Content --}}
@section('content')
@php
    if (auth()->id() == 1) {
        $redirect = route('logs.leaves');
    } else {
        $redirect = route('manage_leaves.index');
    } 
@endphp
<div>
    <main>
        <div class="container-fluid px-4">
            <h1 class="my-4">Leaves</h1>
            @php
                $totalPersonsOnLeave = 0;
            @endphp
            <div class="row">
                @foreach ($leave_types as $key => $type)
                    @php
                    $count = 0;
                        if (isset($leaves[$type->name])) {
                            $count = count($leaves[$type->name]);
                        }
                        $totalPersonsOnLeave += $count;
                    @endphp
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-{{$colors[$key]}} text-white mb-4">
                            <div class="card-body"><h6>{{$type->name}} Leave</h6></div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="text-dark stretched-link" href="{{$redirect}}">
                                    <h3>{{$count}}</h3>
                                    Person
                                </a>
                                <div class=" text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div> 
                @endforeach
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body"><h6>Total </h6></div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="text-dark stretched-link" href="{{$redirect}}">
                                <h3>{{$totalPersonsOnLeave}}</h3>
                                Persons On Leave
                            </a>
                            <div class=" text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </main>
</div>
<br>
@if (auth()->user()->roles->first()->user_type == 3)
    <div class="container">
        <div class="box shadow-sm rounded bg-white my-3">
            <div class="box-title d-flex justify-content-between border-bottom p-3">
                <h6 class="m-0">My Tasks</h6>                    
            </div>
        </div>
        
        @if ($tasks->count() == 0)
            <div class="alert alert-secondary">
                No Task Assigned !
            </div>
        @endif
        @foreach ($tasks as $task)            
            @if (\App\Models\Task::where('leave_id',$task->leave->id)->get()->count() == 0)                                    
                <div class="card border-info bg-secondary my-3" style="cursor: pointer">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span class="h6">
                                <h6>
                                    Leave :
                                    <b>
                                        {{date_format(date_create($task->leave->start_date),"d M, Y")}} 
                                        {{$task->leave->total_days > 1 ? " to ".date_format(date_create($task->leave->end_date),"d M, Y") : "" }}
                                    </b>
                                </h6>
                            </span>
                            <div>
                                <a href="{{route('tasks.index')}}" class="btn btn-primary font-weight-bold">
                                    Tasks
                                </a>
                            </div>
                        </div>
                    </div>                
                </div>
            @endif
        @endforeach
    </div>
@endif
@if (auth()->user()->roles->first()->user_type == 0 || auth()->user()->roles->first()->user_type == 1 )
<div class="container">
    <div class="box shadow-sm rounded bg-white my-3">
        <div class="box-title d-flex justify-content-between border-bottom p-3">
            <h6 class="m-0">Leave Manages</h6>                    
        </div>
    </div>
    
    <a href="{{$redirect}}" style="text-decoration: none">
    <div class="row text-center d-flex justify-content-center">
        <div class="d-flex bg-white col-md-5 mx-2 rounded p-5 justify-content-center">
            {{-- <h5>Managed</h5> --}}
            <div id="chart"></div>
        </div>
        <div class="d-flex bg-white col-md-5 mx-2 rounded p-5 justify-content-center">
            {{-- <h5>Pending</h5> --}}
            <div id="chart2"></div>
        </div>                 
    </div>
    </a>
</div>
@endif
<br>
<div class="container">        
    <div class="box shadow-sm rounded bg-white my-3">
        <div class="box-title d-flex justify-content-between border-bottom p-3">
            <h6 class="m-0">Upcoming Holidays</h6>                    
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        @foreach ($holidays as $holiday)                
        <div class="col-xl-3 col-md-6">
            <div class="card border-primary bg-primary my-3">
                <div class="card-body"><h6>{{$holiday->title}}</h6></div>
                <div class="card-footer">
                    <h5>{{date_format(date_create($holiday->date),"d M, Y")}}</h5>
                    {{$holiday->description}}
                </div>
            </div>
        </div>
        @endforeach        
    </div>
</div>
<br>
<div class="container" id="notification-box">
    <div class="row">
        <div class="col-lg-12">
            <div class="box shadow-sm rounded bg-white mb-3">
                <div class="box-title d-flex justify-content-between border-bottom p-3">
                    <h6 class="m-0">New Notifications</h6>
                    <a href="{{route('notification.markAllAsRead')}}">
                        <button class="btn btn-sm btn-success">Mark All</button>
                    </a>
                </div>
                <div class="box-body p-0">
                    @php
                        $unreadNotifications = auth()->user()->unreadNotifications;
                    @endphp
                    <marquee onmouseover="this.stop();" scrollamount="3" onmouseout="this.start();" direction="up" height="{{$unreadNotifications->count()>0 ? '200px' : 'auto'}}">
                    @forelse ($unreadNotifications as $notification)
                        <a href="{{route('notification.markRead',$notification->id)}}">
                        <div class="p-3 d-flex align-items-center text-dark rounded border border-success border-bottom osahan-post-header">
                            <div class="dropdown-list-image mr-3">
                                <img class="rounded-circle" src="https://png.pngtree.com/png-vector/20190806/ourlarge/pngtree-alert-bell-notification-sound-blue-dotted-line-line-icon-png-image_1651804.jpg" alt="" />
                            </div>
                            <div class="font-weight-bold mr-3">
                                <div class="text-truncate">{!!$notification['data']['title']!!}</div>
                                <div class="small">{!!$notification['data']['message']!!}</div> 
                            </div>
                            <span class="ml-auto mb-auto">
                                
                                {{-- <div class="btn-group">
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <button class="dropdown-item" type="button"><i class="mdi mdi-delete"></i> Delete</button>
                                        <button class="dropdown-item" type="button"><i class="mdi mdi-close"></i> Turn Off</button>
                                    </div>
                                </div>
                                <br /> --}}
                                <div class="text-right text-muted pt-1">{{$notification->created_at->diffForHumans()}}</div>
                            </span>
                        </div>
                    </a>
                    @empty  
                        <div class="p-3 d-flex align-items-center bg-light border-bottom osahan-post-header">
                            No New Notifications
                        </div>
                    @endforelse
                    </marquee>
                </div>
            </div>
            <div class="box shadow-sm overflow-auto rounded bg-white mb-3" style="height: 200px">
                <div class="box-title border-bottom p-3">
                    <h6 class="m-0">Earlier Notifications</h6>
                </div>
                <div class="box-body p-0">
                    {{-- <marquee onmouseover="this.stop();" onmouseout="this.start();" direction="up" height="200px"> --}}
                    @forelse (auth()->user()->notifications as $notification)
                        @if ($notification->read_at)
                            <div class="p-3 d-flex align-items-center bg-light border-bottom osahan-post-header">
                                <div class="dropdown-list-image mr-3">
                                    <img class="rounded-circle" src="https://png.pngtree.com/png-vector/20190806/ourlarge/pngtree-alert-bell-notification-sound-blue-dotted-line-line-icon-png-image_1651804.jpg" alt="" />
                                </div>
                                <div class="font-weight-bold mr-3">
                                    <div class="text-truncate">{!!$notification['data']['title']!!}</div>
                                    <div class="small">{!!$notification['data']['message']!!}</div>                                     
                                </div>
                                <span class="ml-auto mb-auto">
                                    {{-- <div class="btn-group">
                                        <button type="button" class="btn btn-light btn-sm rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button class="dropdown-item" type="button"><i class="mdi mdi-delete"></i> Delete</button>
                                            <button class="dropdown-item" type="button"><i class="mdi mdi-close"></i> Turn Off</button>
                                        </div>
                                    </div>
                                    <br /> --}}
                                    <div class="text-right text-muted pt-1">{{$notification->created_at->diffForHumans()}}</div>
                                </span>
                            </div>
                        @endif
                    @empty                    
                    <div class="p-3 d-flex align-items-center bg-light border-bottom osahan-post-header">
                        No Earlier Notifications
                    </div>
                    @endforelse
                    {{-- </marquee> --}}
                </div>
            </div>
        </div>
    </div>
</div> 

@endsection
@section('styles')
<style>
    body{
        margin-top:20px;
        background-color: #f0f2f5;
    }
    .dropdown-list-image {
        position: relative;
        height: 2.5rem;
        width: 2.5rem;
    }
    .dropdown-list-image img {
        height: 2.5rem;
        width: 2.5rem;
    }
    .btn-light {
        color: #2cdd9b;
        background-color: #e5f7f0;
        border-color: #d8f7eb;
    }
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
</style>
@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/pages/widgets.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    
    <script>
        var options = {
            chart: {
                height: 180,
                type: "radialBar",
            },

            series: [{{isset($leavemanages) ? number_format($leavemanages['managedPercentage']) : ""}}],
            colors: ["#20E647"],
            plotOptions: {
                radialBar: {
                hollow: {
                    margin: 0,
                    size: "70%",
                    background: "#293450"
                },
                track: {
                    dropShadow: {
                    enabled: true,
                    top: 2,
                    left: 0,
                    blur: 4,
                    opacity: 0.15
                    }
                },
                dataLabels: {
                    name: {
                        offsetY: -10,
                        color: "#fff",
                        fontSize: "13px"
                        },
                        value: {
                        color: "#fff",
                        fontSize: "21px",
                        show: true
                        }
                    }
                }
            },
            fill: {
                type: "gradient",
                gradient: {
                shade: "dark",
                type: "vertical",
                gradientToColors: ["#87D4F9"],
                stops: [0, 100]
                }
            },
            stroke: {
                lineCap: "round"
            },
            labels: ["Managed"]
        };

        var options2 = {
            chart: {
                height: 180,
                type: "radialBar",
            },

            series: [{{isset($leavemanages) ? number_format($leavemanages['pendingPercentage']) : ""}}],
            colors: ["#20E647"],
            plotOptions: {
                radialBar: {
                hollow: {
                    margin: 0,
                    size: "70%",
                    background: "#293450"
                },
                track: {
                    dropShadow: {
                    enabled: true,
                    top: 2,
                    left: 0,
                    blur: 4,
                    opacity: 0.15
                    }
                },
                dataLabels: {
                    name: {
                        offsetY: -10,
                        color: "#fff",
                        fontSize: "13px"
                        },
                        value: {
                        color: "#fff",
                        fontSize: "21px",
                        show: true
                        }
                    }
                }
            },
            fill: {
                type: "gradient",
                gradient: {
                shade: "dark",
                type: "vertical",
                gradientToColors: ["#87D4F9"],
                stops: [0, 100]
                }
            },
            stroke: {
                lineCap: "round"
            },
            labels: ["Pending"]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);

        chart2.render();
        chart.render();

    </script>
@endsection
