{{-- Extends layout --}}
@extends('layouts.default')

@section('content')
<div class="card card-custom">
<div class="card-header d-flex justify-content-between">
    <div class="card-title">
        <span class="card-icon">
            <i class="flaticon2-menu text-primary"></i>
        </span>
        <h3 class="card-label">{{ __('Admins') }}</h3>
    </div> 
    <div class="mt-3">
        <a href="{{ route('clients.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
    </div>
</div>
<div class="card-body remove-padding-mobile">
    {!! Form::open([
            'method' => 'POST',
            'route' => ['clients.admins_list_update', $client->external_id],
    ]) !!}
    <div class="form">
		<div class="card-body">
			<div class="row">
            <div class="col-lg-12 form-group form-group-error">
					{{-- 
					{!! 
						Form::select('admins_list[]', 
						$admins_list ?? [],
						old('admins_list'), 
						[
							'class' => 'form-control', 
							'id' => 'admins_list',
							'multiple' => 'multiple'
						]) 
					!!} --}}
					{!! Form::label('admins_list', __('Admins'). ':', ['class' => 'control-label thin-weight']) !!}
					<select name="admins_list[]" id="admins_list" class="form-control">
						@if (isset($admins_list))
							@foreach ($admins_list as $admin)
								<option value="{{$admin->id}}" selected>{{$admin->first_name." ".$admin->last_name}}</option>								
							@endforeach
						@endif
					</select>
					@if ($errors->has('admins_list'))  
						<span class="form-text text-danger">{{ $errors->first('admins_list') }}</span>
					@endif
				</div> 
            </div>
        </div>
        <div class="card-footer">
			<div class="row">
				<div class="col-lg-6">
					{!! Form::submit("Assign Admins", ['class' => 'btn btn-md btn-primary', ]) !!} 
					{!! Form::reset("Cancel", ['class' => 'btn btn-light-primary font-weight-bold', ]) !!} 
				</div> 
			</div>
		</div>
    </div>
    {!! Form::close() !!}
</div>
</div>
<!--end::Card-->
<script>
    $(document).ready(function (){
        $('#admins_list').select2({
            placeholder: "Select Admins",
            allowClear: true,
            ajax: {
				url: '{!! route('clients.admins_list') !!}',
				dataType: 'json', 
				processResults: function (data, param) {  
					return {
						results: $.map(data, function (item) { 
							return {
								text: item.first_name+" "+item.last_name, 
								id: item.id
							}
						})
					};
				}
			}
        });
    });
</script> 
@stop