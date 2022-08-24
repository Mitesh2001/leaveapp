@extends('layouts.master')
@section('styles')
<style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.css" integrity="sha512-gp+RQIipEa1X7Sq1vYXnuOW96C4704yI1n0YB9T/KqdvqaEgL6nAuTSrKufUX3VBONq/TPuKiXGLVgBKicZ0KA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
</style>
@endsection
@section('content')
<div class="card card-custom">
<div class="card-header">
    <div class="card-title d-flex justify-content-between">
        <div class="form-group mx-3">
            {{-- <label>Task Search by Date</label> --}}
            <div class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text"> <i class="fa fa-calendar"></i> </span> </div>
                <input type="text" name="reservation" class="form-control float-right" id="reservation">                  
            </div>
        </div>
        <div class="form-group mx-3">
            {{-- <label>Select User</label> --}}
            <div class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text"> <i class="fa fa-user"></i> </span> </div>
                <select class="custom-select" name="user_id" id="user_id">
                    <option value="">--select user--</option>
                    @foreach ($users as $user)
                    <option value="{{$user->id}}">{{$user->first_name." ".$user->last_name}}</option>
                    @endforeach
                </select>                  
            </div>
        </div>
        <div class="form-group mx-3">
            <button class="btn btn-primary" id="search-btn">SEARCH</button>
            <button class="btn btn-primary" onclick="clearTable()">CLEAR</button>  
        </div>
    </div>
    <div class="card-title">
        <!--begin::Dropdown-->
					<div class="dropdown dropdown-inline mr-2">
						<button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="svg-icon svg-icon-md">
							<!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/PenAndRuller.svg-->
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24" />
									<path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
									<path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
								</g>
							</svg>
							<!--end::Svg Icon-->
						</span>Export</button>
						<!--begin::Dropdown Menu-->
						<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
							<!--begin::Navigation-->
							<ul class="navi flex-column navi-hover py-2">
								<li class="navi-header font-weight-bolder text-uppercase  d-flex justify-content-around text-primary pb-2">Choose an option:</li>
								<li class="navi-item">
									<a href="#" class="navi-link export-print">
										<span class="navi-icon">
											<i class="la la-print"></i>
										</span>
										<span class="navi-text">Print</span>
									</a>
								</li>
								<li class="navi-item">
									<a href="#" class="navi-link export-copy">
										<span class="navi-icon">
											<i class="la la-copy"></i>
										</span>
										<span class="navi-text">Copy</span>
									</a>
								</li>
								<li class="navi-item">
									<a href="#" class="navi-link export-excel">
										<span class="navi-icon">
											<i class="la la-file-excel-o"></i>
										</span>
										<span class="navi-text">Excel</span>
									</a>
								</li>
								<li class="navi-item">
									<a href="#" class="navi-link export-csv">
										<span class="navi-icon">
											<i class="la la-file-text-o"></i>
										</span>
										<span class="navi-text">CSV</span>
									</a>
								</li>
								<li class="navi-item">
									<a href="#" class="navi-link export-pdf">
										<span class="navi-icon">
											<i class="la la-file-pdf-o"></i>
										</span>
										<span class="navi-text">PDF</span>
									</a>
								</li>
							</ul>
							<!--end::Navigation-->
						</div>
						<!--end::Dropdown Menu-->
					</div>
					<!--end::Dropdown-->
    </div>
</div>
<div class="card card-custom">
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
        var print_columns = [0,1];
        var table = $("#kt_datatable").DataTable({
            searching: false, paging: false,"bInfo" : false,
            // dom: 'Bfrtip',
				buttons: [	
					'colvis',
					{
						extend: 'print',
						exportOptions: {
							columns: print_columns
						},
					},
					{
						extend: 'copy',
						exportOptions: {
							columns: print_columns
						},
					},
					{
						extend: 'excel',
						exportOptions: {
							columns: print_columns
						},
					},
					{
						extend: 'csv',
						exportOptions: {
							columns: print_columns
						},
					},
					{
						extend: 'pdf',
						exportOptions: {
							columns: print_columns
						},
						orientation: 'landscape'
					},
				],
        });
        $('.export-print').click(() => {
			$('#kt_datatable').DataTable().buttons(0,1).trigger()
		})
		$('.export-copy').click(() => {
			$('#kt_datatable').DataTable().buttons(0,2).trigger()
		})
		$('.export-excel').click(() => {
			$('#kt_datatable').DataTable().buttons(0,3).trigger()
		})
		$('.export-csv').click(() => {
			$('#kt_datatable').DataTable().buttons(0,4).trigger()
		})
		$('.export-pdf').click(() => {
			$('#kt_datatable').DataTable().buttons(0,5).trigger()
		})
    }); 

    function clearTable() {
        $("#t_body").html("");
    }

    $("#search-btn").click(function () {
        var dates = $("#reservation").val();
        var user_id = $("#user_id").val();
        if (user_id == "") {
            alert('select user !');
        } else {
            $.ajax({
                dataType: "json",
                url: '{!! route('employee-daily-tasks.data') !!}',
                data: {
                    dates : dates,
                    user_id :  user_id
                },
                success:function(data) {
                    if (data.html == "") {
                        alert("No Data Found !");
                    } else {
                        $("#t_body").html(data.html);
                    }
                }
            });
        }
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