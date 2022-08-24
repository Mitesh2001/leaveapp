@extends("layouts.default")
@section("content")
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
                            @if (auth()->user()->roles->first()->user_type == 3) 
                                <th>Behalf Of</th>
                            @else
                                <th>Done By</th> 	 		 			
                            @endif                                                                  		 			 
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
@endsection
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
				$table2 = $("#kt_taskCompletedDatatable").DataTable({
					"aaSorting": [],
					processing: true,
					serverSide: true,
					ajax: '{!! route('tasks.submited_task_data') !!}',
					name:'search',
					drawCallback: function(){
						var length_select = $(".dataTables_length");
						var select = $(".dataTables_length").find("select");
						select.addClass("tablet__select");
					},
					autoWidth: false,
					columns: [
						{data: 'task_details', name: 'task_details'},
                        @if (auth()->user()->roles->first()->user_type == 3) 
                            {data: 'behalf_of', name: 'behalf_of'},
                        @else
                            {data: 'person', name: 'person'},	 		 			
                        @endif 
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