@extends('layouts.default')

{{-- Content --}}
@section('content')
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
            </div>
            <div class="card-body">
                {{-- begin: Datatable --}}
                <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th>Done By</th> 				
                            <th>Behalf Of</th> 	 		 			
                            <th>Task & Descriptions</th>          		 			 
                            <th>Client</th> 
                            <th>Time Taken</th> 
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
                {{-- end: Datatable --}}
            </div>
        </div>
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

    	"use strict";
        var KTDatatablesDataSourceAjaxServer = {
            init: function () {
            var table =    $("#kt_datatable").DataTable({
					"aaSorting": [],
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('logs.tasks-data') !!}',
                    name:'search',
                    drawCallback: function(){
                        var length_select = $(".dataTables_length");
                        var select = $(".dataTables_length").find("select");
                        select.addClass("tablet__select");
                    },
                    autoWidth: false,
                    columns: [
						{data: 'done_by', name: 'done_by'},
						{data: 'person', name: 'person'},
						{data: 'task_details', name: 'task_details'},
						{data: 'client', name: 'client'},
						{data: 'time', name: 'time'},
						{data: 'date', name: 'date'}
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
    </script>

@endsection