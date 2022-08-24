<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<div class="form">
		<div class="card-body remove-paddin-mobile">
			<div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-error">
                        {!! Form::label('name', __('Name'). ' : *', ['class' => '']) !!}
                        {!! 
                            Form::text('name',  
                            "", 
                            ['class' => 'form-control',
                            'id' => 'role_name',
                            'placeholder' => "Role Name"]) 
                        !!}
                        @if ($errors->has('name'))  
                            <span class="form-text text-danger">{{ $errors->first('name') }}</span>
                        @endif  
                    </div>
                </div>
                <div class="col-md-12">                    
                    <div class="form-group form-group-error">
                        {!! Form::label('user_type', __('User Type'). ' : *', ['class' => '']) !!}
                        
                        {{Form::select('user_type',$user_types, NULL,['class'=>'form-control','id' => 'user_type', 'placeholder'=>'select type'])}}
                        
                        @if ($errors->has('user_type')) 
                            <span class="form-text text-danger">{{ $errors->first('user_type') }}</span>
                        @endif
                    </div>                     
                </div>
                <div class="col-md-12"> 
                    <div class="form-group form-group-error">
                        {!! Form::label('description', __('Description'). ' :', ['class' => '']) !!}
                        {!! 
                            Form::textarea('description',  
                            "", 
                            ['class' => 'form-control',
                            'rows' => 2,
                            'placeholder' => "Description"]) 
                        !!}
                        @if ($errors->has('description'))  
                            <span class="form-text text-danger">{{ $errors->first('description') }}</span>
                        @endif  
                    </div> 
                </div>
            </div> 
		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col-lg-6">
					{!! Form::submit($submitButtonText, ['class' => 'btn btn-md btn-primary', 'id' => 'submitClient']) !!} 
					{!! Form::reset("Cancel", ['class' => 'btn btn-light-primary font-weight-bold', 'id' => 'submitClient']) !!}
				</div> 
			</div>
		</div>
	</div>
<!--end::Form-->
</div>
<!--end::Card--> 