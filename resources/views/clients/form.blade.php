<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<div class="form">
		<div class="card-body">
			<div class="row">
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('name', __('Name'). ': *', ['class' => '']) !!}
					{!! 
						Form::text('name',  
						isset($data['name']) ? $data['name'] : old('name'), 
						['class' => 'form-control',
						'placeholder' => 'Client name']) 
					!!}
					@if ($errors->has('name'))  
						<span class="form-text text-danger">{{ $errors->first('name') }}</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('service_type', __('Service Type'). ' :', ['class' => '']) !!}
					{!! 
						Form::text('service_type',  
						isset($data['service_type']) ? $data['service_type'] : old('service_type'), 
						['class' => 'form-control',
						'placeholder' => 'Service Type']) 
					!!}
					@if ($errors->has('service_type'))  
						<span class="form-text text-danger">{{ $errors->first('service_type') }}</span>
					@endif
				</div>
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('project_name', __('Project Name'). ': *', ['class' => '']) !!}
					{!! 
						Form::text('project_name',  
						isset($data['project_name']) ? $data['project_name'] : old('project_name'), 
						['class' => 'form-control',
						'placeholder' => 'Project name']) 
					!!}
					@if ($errors->has('project_name'))  
						<span class="form-text text-danger">{{ $errors->first('project_name') }}</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('primary_email', __('Primary Email'). ': *', ['class' => '']) !!}
					{!! 
						Form::email('primary_email',  
						isset($data['primary_email']) ? $data['primary_email'] :old('primary_email'), 
						['class' => 'form-control',
						'placeholder' => 'Primary Email']) 
					!!}
					@if ($errors->has('primary_email'))  
						<span class="form-text text-danger">{{ $errors->first('primary_email') }}</span>
					@endif
				</div>
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('secondary_email', __('Secondary Email'). ':', ['class' => 'control-label thin-weight']) !!}
					{!! 
						Form::email('secondary_email',	
						isset($data['secondary_email']) ? $data['secondary_email'] :old('secondary_email'), 
						['class' => 'form-control',
						'placeholder' => 'Secondary Email']) 
					!!}
					@if ($errors->has('secondary_email'))  
						<span class="form-text text-danger">{{ $errors->first('secondary_email') }}</span>
					@endif
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('primary_number', __('Contact Number'). ': *', ['class' => 'control-label thin-weight']) !!}
					{!! 
						Form::text('primary_number',  
						isset($data['primary_number']) ? $data['primary_number'] :old('primary_number'), 
						['class' => 'form-control',
						'placeholder' => 'Contact Number']) 
					!!} 
					@if ($errors->has('primary_number'))  
						<span class="form-text text-danger">{{ $errors->first('primary_number') }}</span>
					@endif
				</div>
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('secondary_number', __('WhatsApp Number'). ':', ['class' => 'control-label thin-weight']) !!}
					{!! 
						Form::text('secondary_number',  
						isset($data['secondary_number']) ? $data['secondary_number'] :old('secondary_number'),  
						['class' => 'form-control',
						'placeholder' => 'WhatsApp Number']) 
					!!} 
					@if ($errors->has('secondary_number'))  
						<span class="form-text text-danger">{{ $errors->first('secondary_number') }}</span>
					@endif
				</div>
			</div> 
			<div class="row">
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('primary_contact_person', __('Primary Contact Person'). ': ', ['class' => 'control-label thin-weight']) !!}
					{!! 
						Form::text('primary_contact_person',  
						isset($data['primary_contact_person']) ? $data['primary_contact_person'] :old('primary_contact_person'),   
						['class' => 'form-control',
						'placeholder' => 'Primary Contact Person']) 
					!!} 
					@if ($errors->has('primary_contact_person'))  
						<span class="form-text text-danger">{{ $errors->first('primary_contact_person') }}</span>
					@endif
				</div>
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('secondary_contact_person', __('Secondary Contact Person'). ':', ['class' => 'control-label thin-weight']) !!}
					{!! 
						Form::text('secondary_contact_person',  
						isset($data['secondary_contact_person']) ? $data['secondary_contact_person'] :old('secondary_contact_person'),    
						['class' => 'form-control',
						'placeholder' => 'Secondary Contact Person']) 
					!!} 
					@if ($errors->has('secondary_contact_person'))  
						<span class="form-text text-danger">{{ $errors->first('secondary_contact_person') }}</span>
					@endif
				</div>
			</div> 
			<div class="row">
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('company_name', __('Company Name'). ': ', ['class' => '']) !!}
					{!! 
						Form::text('company_name',  
						isset($data['company_name']) ? $data['company_name'] :old('company_name'),    
						['class' => 'form-control',
						'placeholder' => 'Company Name']) 
					!!}
					@if ($errors->has('company_name'))  
						<span class="form-text text-danger">{{ $errors->first('company_name') }}</span>
					@endif
				</div>
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('company_type', __('Company Type'). ':', ['class' => '']) !!}
					{!! 
						Form::text('company_type',  
						isset($data['company_type']) ? $data['company_type'] :old('company_type'), 
						['class' => 'form-control',
						'placeholder' => 'Company Type',]) 
					!!}
					@if ($errors->has('company_type'))  
						<span class="form-text text-danger">{{ $errors->first('company_type') }}</span>
					@endif
				</div>				
				<div class="col-lg-4 form-group form-group-error">
					{{-- {!! Form::label('admins_list', __('Admins'). ':', ['class' => 'control-label thin-weight']) !!}
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
					{!! Form::label('admins_list', __('Admins'). ' : *', ['class' => 'control-label thin-weight']) !!} 

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
			<div class="row">
				<div class="col-lg-3 form-group form-group-error"> 
					{!! Form::label('country_id', __('Country'). ': *', ['class' => 'control-label thin-weight']) !!}
					{!!
						Form::select('country_id',
						$countries,
						isset($data->country_id) ? $data->country_id : 101,
						['class' => 'form-control ui search selection top right pointing country_id-select country-val searchpicker',
						'id' => 'country_id-select','placeholder'=>'Please select country','data-statepicker'=>'state-drop-down-client','data-statetext'=>'state-textbox-client','data-postcode'=>'postcode-client','required'])
					!!} 
					@if ($errors->has('country_id'))  
						<span class="form-text text-danger">{{ $errors->first('country_id') }}</span>
					@endif
				</div>
				@php
					$checkStatePicker =  empty($client) || (!empty($client) && $client->country_id == 101) ? '' : 'd-none';
					$checkStatePickerAttr =  empty($client) || (!empty($client) && $client->country_id == 101) ? 'required' : '';
					$checkStateText = !empty($checkStatePicker) ? '' : 'd-none';
					$checkStateTextAttr =  empty($checkStatePicker) ? '' : 'required';
				@endphp
				<div class="{{'col-lg-3 state-drop-down-client '.$checkStatePicker}} form-group form-group-error"> 
					{!! Form::label('state_id', __('State'). ': *', ['class' => 'control-label thin-weight']) !!}
					{!!
						Form::select('state_id',
						$states,
						isset($data['state_id']) ? $data['state_id'] : null,
						['class' => 'form-control ui search selection top right pointing state_id-select searchpicker state-drop-down-client-picker',
						'id' => 'state_id-select','placeholder'=>'Please select state',$checkStatePickerAttr, 'style' => 'width:100%'])
					!!}
					@if ($errors->has('state_id'))  
						<span class="form-text text-danger">{{ $errors->first('state_id') }}</span>
					@endif
				</div>
				<div class="{{'col-lg-3 state-textbox-client '.$checkStateText}} form-group form-group-error">
					{!! Form::label('state_name', __('State'), ['class' => '']) !!}
					<span>*</span>
					<div class="input-group">
						{!! 
							Form::text('state_name',  
							isset($data['state_name']) ? $data['state_name'] : null, 
							['class' => 'form-control state-textbox-client-text', 'placeholder' => "State name",$checkStateTextAttr]) 
						!!}
					</div>
					@if ($errors->has('state_name'))  
						<span class="form-text text-danger">{{ $errors->first('state_name') }}</span>
					@endif
				</div>
				<div class="col-lg-3 form-group form-group-error">
					{!! Form::label('city', __('City'). ':', ['class' => 'control-label thin-weight']) !!}
					{!! 
						Form::text('city',
						isset($data['city']) ? $data['city'] : old('city'),
						['class' => 'form-control',
						'placeholder' => 'City']) 
					!!}
					@if ($errors->has('city'))  
						<span class="form-text text-danger">{{ $errors->first('city') }}</span>
					@endif
				</div> 
				<div class="col-lg-3">
					<div class="form-group form-group form-group-error">
						{!! Form::label('zipcode', __('Zipcode'). ':', ['class' => 'control-label thin-weight']) !!}
						<div class="input-group">
							{!! 
								Form::text('zipcode',
								isset($data['zipcode']) ? $data['zipcode'] : old('zipcode'), 
								['class' => 'form-control',
								'placeholder' => 'Zipcode']) 
							!!}
							<div class="input-group-append"><span class="input-group-text"><i class="la la-bookmark-o"></i></span></div>
						</div>
						@if ($errors->has('zipcode'))  
							<span class="form-text text-danger">{{ $errors->first('zipcode') }}</span>
						@endif
					</div>  
				</div>
			</div>
			<div class="row"> 
			<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('address', __('Address'). ':', ['class' => 'control-label thin-weight']) !!}
					<div class="input-group">
						{!! 
							Form::textarea('address',
							isset($data['address']) ? $data['address'] : old('address'), 
							['class' => 'form-control',
							'rows'=>4,
							'placeholder' => 'Address'])
						!!}
						<div class="input-group-append"><span class="input-group-text"><i class="la la-map-marker"></i></span></div>
					</div>
					@if ($errors->has('address'))  
						<span class="form-text text-danger">{{ $errors->first('address') }}</span>
					@endif
				</div>
				<div class="col-lg-6 form-group form-group-error">
					{!! Form::label('notes', __('Notes'). ':', ['class' => 'control-label thin-weight']) !!}
					<div class="input-group">
						{!! 
							Form::textarea('notes',
							isset($data['notes']) ? $data['notes'] : old('notes'), 
							['class' => 'form-control',
							'rows'=>4,
							'placeholder' => 'Notes'])
						!!} 
					</div>
					@if ($errors->has('notes'))  
						<span class="form-text text-danger">{{ $errors->first('notes') }}</span>
					@endif
				</div> 
			</div>
		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col-lg-6">
					{!! Form::hidden('id', null, ['id' => 'id']) !!}
					{!! Form::submit($submitButtonText, ['class' => 'btn btn-md btn-primary', 'id' => 'submitClient']) !!} 
					{!! Form::reset("Cancel", ['class' => 'btn btn-light-primary font-weight-bold', 'id' => 'submitClient']) !!} 
				</div> 
			</div>
		</div>
	</div>
<!--end::Form-->
</div>
<!--end::Card-->

<script>
	$(document).ready(function (){
		// set select2 picker
		$('.searchpicker').select2();
		var fixNewLine = {
			exportOptions: {
				format: {
					body: function ( data, column, row ) {
						if (row >= 0 && row <= 2) {
							return data.replace(/<.*?>/ig, "")
						} else if (row >= 4 && row <= 4) {
							return data.replace(/<.*?>/ig, "")
						}
						return data;
					}
				}
			}
		};

		$(document).on('change','.country-val',function(){
			var value = $(this).val();
			var statePicker = $(this).data('statepicker');
			var stateText = $(this).data('statetext');
			var postCode = $(this).data('postcode');
			if(value != 101)
			{
			$('.'+postCode).removeClass('valid-number'); 
			$('.'+statePicker).addClass('d-none');
			$('.'+stateText).removeClass('d-none');
			$('.'+statePicker+'-picker').prop('required',false);
			$('.'+stateText+'-text').prop('required',true);
			}else{
				$('.'+postCode).val('');
				$('.'+postCode).addClass('valid-number');
				$('.'+statePicker).removeClass('d-none');
				$('.'+stateText).addClass('d-none');
				$('.'+statePicker+'-picker').prop('required',true);
				$('.'+stateText+'-text').prop('required',false);
			}
		});

		$('#users_list').select2({
			placeholder: "Select users",
			allowClear: true,
			ajax: {
				url: '{!! route('clients.users_list') !!}',
				dataType: 'json', 
				processResults: function (data, param) {  
					return {
						results: $.map(data, function (item) { 
							return {
								text: item.first_name,
								id: item.id
							}
						})
					};
				}
			}
		})

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
		})
	});
</script>