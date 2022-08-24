@extends('layouts.master')
@section('styles')
<style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.css" integrity="sha512-gp+RQIipEa1X7Sq1vYXnuOW96C4704yI1n0YB9T/KqdvqaEgL6nAuTSrKufUX3VBONq/TPuKiXGLVgBKicZ0KA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
</style>
@endsection
@section('content')
<div class="card card-custom">
    <div class="card-header justify-content-between">
        <div class="card-title sticky remove-flex">
            <div class="form-group">
                <label>Task Search by Date</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text"> <i class="fa fa-calendar"></i> </span> </div>
                    <input type="text" name="reservation" class="form-control float-right" id="reservation">
                    <div class="mx-4">
                        <button class="btn btn-primary" id="search-btn">SEARCH</button>
                        <button class="btn btn-primary" id="clear-btn">CLEAR</button>  
                    </div>                  
                </div>
            </div>
        </div>        
    </div>
    <div class="card-body">
		{{-- begin: Datatable --}}
		<table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
			<thead>
				<tr> 				
					<th>Project Name/Task Details/Task Hours</th>
                    <th>Task Date</th>     		 			 
				</tr>               
			</thead>
            <tbody id="t_body">
               
            </tbody>
		</table>
		{{-- end: Datatable --}}
	</div> 
</div>
@endsection
@section('scripts')
<script>
    $(function () {
        $('#reservation').daterangepicker({maxDate: new Date()});

        var table = $("#kt_datatable").DataTable({
            searching: false, paging: false,"bInfo" : false,
            "oLanguage": {
                "sEmptyTable": "Select Date !"
            }
        });

        $('#clear-btn').on( 'click', function () {
            table.destroy();
        } );
    }); 

    function clearTable() {
        $("#t_body").html("");
    }

    $("#search-btn").click(function () {
        var dates = $("#reservation").val();
        $.ajax({
            dataType: "json",
            url: '{!! route('my_tasks.daily_tasks.data') !!}',
            data: {
               dates : dates 
            },
            success:function(data) {
                if (data.html == "") {
                    alert("No Data Found !");
                } else {
                    $("#t_body").html(data.html);
                }
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
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection