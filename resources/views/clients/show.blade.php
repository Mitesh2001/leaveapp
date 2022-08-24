@extends('layouts.master')
@section('content') 
<div class="card card-custom mb-5">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon2-menu text-primary"></i>
            </span>
            <h3 class="card-label">{{ __('Client Details') }}</h3>
        </div> 
        <div class="mt-3">
            <a href="{{ route('clients.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
        </div>
    </div>
    <div class="card-body detail-parent">
		<div class="card rounded gradient-detail-card mb-6 shadow-sm">
			<div class="card-body">
				<div class="row"> 
					<div class="col-md-6">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Name</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->name }}</h3>
						</div>
					</div>      
				</div> 
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Service TYpe</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->service_type }}</h3>
						</div>
					</div>
					<div class="col-md-6 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Project Name</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->project_name }}</h3>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Primary Email</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->primary_email }}</h3>
						</div>
					</div>
					<div class="col-md-6 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Secondary Email</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->secondary_email }}</h3>
						</div>
					</div>
				</div>
				<div class="row mb-lg-4 mb-md-2">
					<div class="col-md-3 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Contact Number</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->primary_number }}</h3>
						</div>
					</div>
					<div class="col-md-3 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">WhatsApp Number</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->secondary_number }}</h3>
						</div>
					</div>
				</div> 
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Primary Contact Person</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->primary_contact_person }}</h3>
						</div>
					</div>
					<div class="col-md-6 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Secondary Contact Person</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->secondary_contactprimary_contact_person }}</h3>
						</div>
					</div>
				</div> 
				<div class="row mb-lg-4 mb-md-2">
					<!--begin::Info-->  
					<div class="col-md-3 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Country</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->getCountry->name }}</h3>
						</div>
					</div>  
					<div class="col-md-3 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">State</span>
							@if($client->state_id == 0)
								<h3 class="text-white font-size-17 font-weight-bold">{{ $client->state_name }}</h3>
							@else
								<h3 class="text-white font-size-17 font-weight-bold">{{ $client->getState->name }}</h3>
							@endif
						</div>
					</div>
					<div class="col-md-3 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">City</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->city }}</h3>
						</div>
					</div>
					<div class="col-md-3 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Zip Code</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->zipcode }}</h3>
						</div>
					</div>  
				</div> 
				<div class="row mb-lg-4 mb-md-2">
					<div class="col-md-3 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Client Type</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->client_type }}</h3>
						</div>
					</div>
					<div class="col-md-3 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Notes</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->notes }}</h3>
						</div>
					</div> 
					<!--begin::Info-->  
					<div class="col-md-6 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Address</span>
							<h3 class="text-white font-size-17 font-weight-bold">{{ $client->address }}</h3>
						</div>
					</div> 
					<div class="col-md-6 col-sm-12">
						<div class="mb-8 d-flex flex-column">
							<span class="text-light font-size-14 mb-3">Users Assigned</span>
							@foreach($client->users as $user)
							<h3 class="text-white font-size-17 font-weight-bold">
								<i class="fas fa-check-circle text-success"></i>
								{{$user->first_name." ".$user->last_name }}
							</h3>
							@endforeach
						</div>
					</div>  
				</div>
			</div>
		</div>
    </div>
</div>  
@stop
@section('styles')
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('scripts')
    {{-- vendors --}}
    <script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    {{-- page scripts --}}
    <script>  

	</script>
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
@endsection