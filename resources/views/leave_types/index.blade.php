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
		<div class="card-toolbar">
		@if(\Entrust::can('leave-types-create'))
            <a href="{{ route('leave-types.create') }}" class="btn btn-primary mr-3">Create Leave Type</a>
		@endif
			<!--begin::Dropdown-->
			<div class="dropdown dropdown-inline mr-2">

			</div>
			<!--end::Dropdown-->
			
		</div>
	</div>
	<div class="card-body">
		<!--begin: Datatable-->
		<table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
			<thead>
				<tr> 				
					<th>Name</th> 	 		 			
					<th>Description</th> 
					@if(\Entrust::can('leave-type-update'))
						<th>Action</th> 	
					@endif
				</tr>
			</thead>
		</table>
		<!--end: Datatable-->
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

		// Reload page after 2 seconds
		function reload_page()
		{
			setTimeout(() => {
				location.reload();
			}, 2000);
		}

    	"use strict";
        var KTDatatablesDataSourceAjaxServer = {
            init: function () {
                $("#kt_datatable").DataTable({
					"aaSorting": [],
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('leave_types.data') !!}',
                    name:'search',
                    drawCallback: function(){
                        var length_select = $(".dataTables_length");
                        var select = $(".dataTables_length").find("select");
                        select.addClass("tablet__select");
                    },
                    autoWidth: false,
                    columns: [
                        {data: 'name', name: 'name', width: "30%"},  
                        {data: 'description', name: 'description', width: "40%"},
						@if(\Entrust::can('leave-type-update'))
                        {data: 'actions', name: 'actions', width: "	10%"}
						@endif
                    ]
                })
            }
        };

        jQuery(document).ready((function () {
            KTDatatablesDataSourceAjaxServer.init() 
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