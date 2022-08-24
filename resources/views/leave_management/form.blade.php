<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<div class="form">
		<div class="card-body remove-paddin-mobile">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-group-error">
                        {{-- {!! Form::label('clients[]', __('Select Your Clients'). ':', ['class' => '']) !!}
                        {!!
                            Form::select('clients[]',
                            $clients ?? [],
                            null, 
                            ['class' => 'form-control',
                            'id' => 'clients_input',
                            'multiple' => 'multiple', 
                            'style' => 'width:100%'])
                        !!}  --}}
                        <label for="clients_input">Select Your Clients : *</label>
                        <select multiple class="form-control" name="clients[]" id="clients_input">
                            @if (isset($clients))
							@foreach ($clients as $clients)
								<option value="{{$clients->id}}" selected>{{$clients->name}}</option>								
							@endforeach
						@endif
                        </select>
                        @if ($errors->has('clients'))  
                            <span class="form-text text-danger">{{ $errors->first('clients') }}</span>
                        @endif  
                    </div> 
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-error">
                        {!! Form::label('email_template', __('Select Email Template'). ': *', ['class' => '']) !!}
                        {!!
                            Form::select('email_template',
                            $email_templates,
                            isset($leave_manage) ? $leave_manage->email_template_id : "", 
                            ['class' => 'form-control',
                            'id' => 'email_templates',  
                            'placeholder' => 'Templates',
                            'style' => 'width:100%'])
                        !!} 
                        @if ($errors->has('email_templates'))  
                            <span class="form-text text-danger">{{ $errors->first('email_templates') }}</span>
                        @endif  
                    </div> 
                </div>
            </div>
            {{ Form::hidden('leave_external_id', $leave->external_id) }} 
			<div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-error">
                        {{-- {!! Form::label('alternate_person', __('Select Alternate Person'). ':', ['class' => '']) !!}
                        {!!
                            Form::select('alternate_person',
                            $alternate_person ?? "",
                            null, 
                            ['class' => 'form-control',
                            'id' => 'alternate_person',  
                            'placeholder' => 'People',
                            'style' => 'width:100%'])
                        !!}  --}}
                        <label for="alternate_person">Select Alternate Person : *</label>
                        <select class="form-control" name="alternate_person" id="alternate_person">
                            @if (isset($alternate_person))
								<option value="{{$alternate_person->id}}" selected>{{$alternate_person->first_name." ".$alternate_person->last_name}}</option>								
						@endif
                        </select>
                        @if ($errors->has('alternate_person'))  
                            <span class="form-text text-danger">{{ $errors->first('alternate_person') }}</span>
                        @endif  
                    </div> 
                </div>
            </div>
		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col-lg-6">
					{!! Form::submit($submitButtonText, ['class' => 'btn btn-md btn-primary', 'id' => 'submitLeave']) !!} 
					{!! Form::reset("Cancel", ['class' => 'btn btn-light-primary font-weight-bold', 'id' => 'submitLeave']) !!}
				</div> 
			</div>
		</div>
	</div>
<!--end::Form-->
</div>
<!--end::Card--> 
<script>
	$(document).ready(function (){        
        
        $('#clients_input').select2({
			placeholder: "Select Clients",
			allowClear: true,
			ajax: {
				url: '{!! route('manage_leaves.clients') !!}',
				dataType: 'json', 
				processResults: function (data, param) {  
					return {
						results: $.map(data, function (item) { 
							return {
								text: item.name,
								id: item.id
							}
						})
					};
				}
			}
		})

        $("#alternate_person").select2({
            placeholder: "Select Alternate Person",
            allowClear: true,
            ajax: {
                url: '{!! route('manage_leaves.persons_list') !!}',
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