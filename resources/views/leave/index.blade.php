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
<div class="row">
<div class="col-lg-12">
<!--begin::Card-->
<div class="card card-custom">
	<div class="card-header">
		<div class="card-title remove-flex">
			<span class="card-icon">
				<i class="flaticon2-list-3 text-primary"></i>
			</span>
			<h3 class="card-label">Leaves</h3>
		</div>
        @if (auth()->user()->can('leave-create'))
            <!-- The Modal -->
            <div class="modal fade" id="myModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">							
                                <i class="flaticon2-list-3 text-primary"></i>							
                                <span>{{ __('Add Leave') }}</span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            {!! Form::open([
                                'route' => 'leaves.store',
                                'class' => 'ui-form',
                                'id' => 'leaveRequestForm',
                                'files' => true
                            ]) !!}
                            
                            @include('leave.form', ['submitButtonText' => __('Create Leave')])
                        
                            {!! Form::close() !!}
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
		<div class="card-toolbar">
            @if (auth()->user()->can('leave-create'))
			    <a href="#" data-backdrop="static" data-keyboard="false" class="btn btn-primary mr-3" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add Leave</a>		
            @endif	
		</div>
	</div>
	<div class="card-body">
		{{-- begin: Datatable --}}
		<table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
			<thead>
				<tr> 				
					<th>Person</th>     		 			 
					<th>
                        <select name="leave_type_filter" id="leave_type_filter" class="form-control width-100">
                            <option value="">All Types</option>
                            @foreach($types as $key => $type)
                                <option value="{{$type}}">{{$type}}</option>
                            @endforeach
                        </select>
                    </th> 	 		 			
					<th>Start Date</th>  
					<th>End Date</th>   
					<th>Total Days</th>  
                    <th>Description</th> 
					@if(\Entrust::can('leave-update'))
                    <th>Action</th>
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
	<style>
        .form-check-label{
            cursor: pointer;            
        }
    </style>
@endsection


{{-- Scripts Section --}}
@section('scripts') 
 
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    {{-- page scripts --}}
    <script> 

		

    	"use strict";
        var KTDatatablesDataSourceAjaxServer = {
            init: function () {
            var table =    $("#kt_datatable").DataTable({
					"aaSorting": [],
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('leaves.data') !!}',
                    name:'search',
                    drawCallback: function(){
                        var length_select = $(".dataTables_length");
                        var select = $(".dataTables_length").find("select");
                        select.addClass("tablet__select");
                    },
                    autoWidth: false,
                    columns: [
                        {data: 'person', name: 'person'},
                        {data: 'type', name: 'type'},
                        {data: 'start_date', name: 'start_date'},
                        {data: 'end_date', name: 'end_date'},
                        {data: 'total_days', name: 'total_days'},
                        {data: 'description', name: 'description'},
						@if(\Entrust::can('leave-update'))
                        {data: 'action', name: 'action'}
                        @endif
                    ]
                });

                $('#leave_type_filter').change(function () { 
                    var selected = $("#leave_type_filter").val();// option:selected
                    
                    if (selected == "" || selected == null ) {
                        table.column(1).search('').draw();
                    } else { 
                        table.column(1).search('^' + selected + '$', true, false).draw();
                    }
                });
            }
        };

        jQuery(document).ready((function () {
            KTDatatablesDataSourceAjaxServer.init() 
        }));  

		$(document).ready(function () {
            $("#leaveRequestForm").validate({
                rules: {
                    person :  {
                        required: true,
                    },
                    type :  {
                        required: true,
                    },
                    start_date : {
                        required: true,                            
                    },
                    end_date : {                        
                        required: true,                        
                    }
                },
                messages: { 
                    person: {
                        required: "Select Leave Person !",
                    },
                    type: {
                        required: "Select Leave Type !",
                    },
                    start_date : {
                        required: "Select start date !",                            
                    },
                    end_date : {
                        required: "Select End date !",  
                    }   
                },
                normalizer: function( value ) { 
                    return $.trim( value );
                },
                errorElement: "span",
                errorClass: "form-text text-danger",
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group-error').append(error);
                }
            });

        });

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