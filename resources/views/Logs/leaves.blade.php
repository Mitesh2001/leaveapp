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
                    <h3 class="card-label">Leaves</h3>
                </div>
            </div>
            <div class="card-body">
                {{-- begin: Datatable --}}
                <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                    <thead>
                        <tr> 				
                            <th>
                                <select name="leave_type_filter" id="leave_type_filter" class="form-control width-100">
                                    <option value="">All Types</option>
                                    @foreach($types as $key => $type)
                                        <option value="{{$type}}">{{$type}}</option>
                                    @endforeach
                                </select>
                            </th>	 		 			
                            <th>Person</th>     		 			 
                            <th>Start Date</th>  
                            <th>End Date</th>   
                            <th>Total Days</th>    
                            <th>Clients List</th>
                            <th>Alternate Person</th>
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
                    ajax: '{!! route('logs.leaves-data') !!}',
                    name:'search',
                    drawCallback: function(){
                        var length_select = $(".dataTables_length");
                        var select = $(".dataTables_length").find("select");
                        select.addClass("tablet__select");
                    },
                    autoWidth: false,
                    columns: [
                        {data: 'type', name: 'type'},
                        {data: 'person', name: 'person'},
                        {data: 'start_date', name: 'start_date'},
                        {data: 'end_date', name: 'end_date'},
                        {data: 'total_days', name: 'total_days'},
                        {data: 'clients_list', name: 'clients_list'},
                        {data: 'alternate_person', name: 'alternate_person'},
                    ]
                });

                $('#leave_type_filter').change(function () { 
                    var selected = $("#leave_type_filter").val();// option:selected
                    
                    if (selected == "" || selected == null ) {
                        table.column(0).search('').draw();
                    } else { 
                        table.column(0).search('^' + selected + '$', true, false).draw();
                    }
                });
            }
        };

        jQuery(document).ready((function () {
            KTDatatablesDataSourceAjaxServer.init() 
        }));  
    </script>

@endsection