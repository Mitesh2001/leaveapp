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
		<div class="card-title">
			<span class="card-icon">
				<i class="flaticon2-email text-primary"></i>
			</span>
			<h3 class="form_title">Emails Template</h3>
		</div> 
		<div class="card-toolbar">
			@if(\Entrust::can('email-template-create'))
				<a href="{{ route('emails.create') }}" class="btn btn-primary mr-3">Create Email Temaplate</a>
			@endif
		</div>
	</div>
	<div class="card-body">
	 @include('layouts.alert')

	 	<div class="table-responsive">
		<!--begin: Datatable-->
		<table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
			<thead>
				<tr>
                    <th>Name</th>
					<th>Subject</th>
					<th class="action-header">Actions</th>
				</tr>
			</thead>
		</table>
		<!--end: Datatable-->
		 </div>

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

        var KTDatatablesDataSourceAjaxServer = {
            init: function () {
                $("#kt_datatable").DataTable({
					"aaSorting": [],
                    processing: true,
					serverSide: true,
					"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    ajax: '{!! route('emails.data') !!}',
                    name:'search',
					language: {
						searchPlaceholder: "Type here...",
					},
                    drawCallback: function(){
                        var length_select = $(".dataTables_length");
                        var select = $(".dataTables_length").find("select");
                        select.addClass("tablet__select");
                    },
                    autoWidth: false,
                    columns: [   
                        {data: 'name', name: 'name', width: '20%'},   
                        {data: 'subject', name: 'subject', width: '70%'},   
                        
                        @if(\Entrust::can('email-template-update'))
                            { data: 'action', name: 'action', width: '10%', orderable: false, searchable: false, class:'fit-action-delete-th table-actions'},
                        @else 
						{ data: 'action', name: 'action', visible: false},
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