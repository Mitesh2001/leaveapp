{{-- Extends layout --}}
@extends('layouts.default')
{{-- Content --}}
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="box shadow-sm rounded bg-white mb-3">
                <div class="box-title d-flex justify-content-between border-bottom p-3">
                    <h6 class="m-0">New</h6>
                    <a href="{{route('notification.markAllAsRead')}}">
                        <button class="btn btn-sm btn-success">Mark All</button>
                    </a>
                </div>
                <div class="box-body p-0">
                    @forelse (auth()->user()->unreadNotifications as $notification)
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
                </div>
            </div>
            <div class="box shadow-sm rounded bg-white mb-3">
                <div class="box-title border-bottom p-3">
                    <h6 class="m-0">Earlier</h6>
                </div>
                <div class="box-body p-0">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 

{{-- Styles Section --}}
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
 
    
@endsection