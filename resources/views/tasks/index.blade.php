{{-- Extends layout --}}
@extends('layouts.default')

{{-- Content --}}
@section('content')
<div class="row">
	<div class="col-lg-12">   
		@if(Session::has('flash_message')) 
			<div class="alert alert-success" role="alert">
				{{Session::get('flash_message') }}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true"><i class="ki ki-close"></i></span>
				</button>
			</div> 
		@endif
	</div>
</div>
<!-- The Modal -->
<div class="modal fade" id="myModal">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">							
					<i class="flaticon2-list-3 text-primary"></i>							
					<span>{{ __('Completed Tasks') }}</span>
				</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12">
					<!--begin::Card-->
						<div class="card card-custom">
							<div class="card-body">
								{{-- begin: Datatable --}}
								<table class="table table-bordered table-hover table-checkable" id="kt_taskCompletedDatatable" style="margin-top: 13px !important">
									<thead>
										<tr> 				
											<th>Task & Descriptions</th>          		 			 
											<th>Person On Behalf</th> 	 		 			
											<th>Client</th> 
											<th>Time Taken</th> 
											<th>Date</th>
										</tr>
									</thead>
								</table>
								{{-- end: Datatable --}}
							</div> 
						</div>
					<!--end::Card-->
					</div>
				</div> 
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
	<!--begin::Card-->
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title remove-flex">
					<span class="card-icon">
						<i class="flaticon2-list-3 text-primary"></i>
					</span>
					<h3 class="card-label">Tasks</h3>
				</div>
				<div class="card-title remove-flex">
					<a href="#" class="btn btn-primary btn-block mr-3" data-toggle="modal" data-target="#myModal" class="btn btn-primary">View Completed Tasks</a>		
				</div>
			</div>
			<div class="card-body">
				{{-- begin: Datatable --}}
				<table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
					<thead>
						<tr> 				
							<th>Person On Behalf</th> 	 		 			
							<th>Leave Dates</th>
							@if (auth()->user()->can('task-update') || auth()->user()->can('task-create'))							
								<th>Works</th>    
							@endif								   
						</tr>
					</thead>
				</table>
				{{-- end: Datatable --}}
			</div> 
		</div>
	<!--end::Card-->
	</div>
</div> 

@endsection
 

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection


{{-- Scripts Section --}}
@section('scripts') 
 
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    {{-- page scripts --}}
    <script> 

		$(document).ready(function (){
			
		});

    	"use strict";
        var KTDatatablesDataSourceAjaxServer = {
            init: function () {
                $table1 = $("#kt_datatable").DataTable({
					"aaSorting": [],
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('tasks.data') !!}',
                    name:'search',
                    drawCallback: function(){
                        var length_select = $(".dataTables_length");
                        var select = $(".dataTables_length").find("select");
                        select.addClass("tablet__select");
                    },
                    autoWidth: false,
                    columns: [
                        {data: 'person', name: 'person'},
                        {data: 'dates', name: 'dates'},
						@if (auth()->user()->can('task-update') || auth()->user()->can('task-create'))							
                        {data: 'tasks', name: 'tasks'}
						@endif								   
                    ]
                });

				$table2 = $("#kt_taskCompletedDatatable").DataTable({
					"aaSorting": [],
					processing: true,
					serverSide: true,
					ajax: '{!! route('tasks.completed_task_data') !!}',
					name:'search',
					drawCallback: function(){
						var length_select = $(".dataTables_length");
						var select = $(".dataTables_length").find("select");
						select.addClass("tablet__select");
					},
					autoWidth: false,
					columns: [
						{data: 'task_details', name: 'task_details'},
						{data: 'person', name: 'person'},
						{data: 'client', name: 'client'},
						{data: 'time', name: 'time'},
						{data: 'date', name: 'date'}
					]
				});
			}
		};

        jQuery(document).ready((function () {
            KTDatatablesDataSourceAjaxServer.init();
        }));  

		$( document ).ajaxComplete(function() {
            // Required for Bootstrap tooltips in DataTables
            $('[data-toggle="tooltip"]').tooltip({
                "html": true,
                "delay": {"show": 100, "hide": 0},
            });
        });
	</script>
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection