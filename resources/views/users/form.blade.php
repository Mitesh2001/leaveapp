
<div class="wizard-navigation">
    <ul class="employee-tab">
        <li><a href="#personal_details" data-toggle="tab">Personal Details</a></li>
    </ul> 
</div>

<div class="tab-content">
    <div class="tab-pane" id="personal_details">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group form-group-error">
                    {{Form::label('Role')}} *
                    @if(isset($user))
                        {{Form::select('role',$roles, $user->roles->first()->id ?? NULL,['class'=>'form-control','id' => 'role', 'placeholder'=>'Select Role'])}}
                    @else
                        {{Form::select('role',$roles, NULL,['class'=>'form-control','id' => 'role', 'placeholder'=>'Select Role'])}}
                    @endif  
                    @if ($errors->has('role')) 
                        <span class="form-text text-danger">{{ $errors->first('role') }}</span>
                    @endif
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group form-group-error">
                    {{Form::label('First Name')}} *
                    {{Form::text('first_name', null,['class'=>'form-control','placeholder'=>'First Name'])}}
                    
                    @if ($errors->has('first_name')) 
                        <span class="form-text text-danger">{{ $errors->first('first_name') }}</span>
                    @endif
                </div> 
            </div>
            <div class="col-sm-4">
                <div class="form-group form-group-error">
                    {{Form::label('Last Name')}} *
                    {{Form::text('last_name', null,['class'=>'form-control','placeholder'=>'Last Name'])}}

                    @if ($errors->has('last_name')) 
                        <span class="form-text text-danger">{{ $errors->first('last_name') }}</span>
                    @endif
                </div> 
            </div>
            <div class="col-sm-4">
                <div class="form-group form-group-error">
                    {{Form::label('Nick Name')}}
                    {{Form::text('nick_name', null,['class'=>'form-control','placeholder'=>'Nick Name'])}}
                    @if ($errors->has('nick_name')) 
                        <span class="form-text text-danger">{{ $errors->first('nick_name') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group form-group-error">
                    {{Form::label('Email')}} *
                    {{Form::email('email', null,['class'=>'form-control','id' => 'email', 'placeholder'=>'Email'])}}
                    @if ($errors->has('email')) 
                        <span class="form-text text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group form-group-error">
                    {{Form::label('Primary Number')}} *
                    {{Form::text('primary_number', null,['class'=>'form-control valid-number','placeholder'=>'Primary Number', 'id'=> 'primary_number'])}}

                    @if ($errors->has('primary_number')) 
                        <span class="form-text text-danger">{{ $errors->first('primary_number') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group form-group-error">
                    {{Form::label('Secondary Number')}}<small> </small>
                    {{Form::text('secondary_number', null,['class'=>'form-control valid-number','placeholder'=>'Secondary Number'])}}

                    @if ($errors->has('secondary_number')) 
                        <span class="form-text text-danger">{{ $errors->first('secondary_number') }}</span>
                    @endif
                </div>
            </div> 
        </div>
        <div class="row"> 
            <div class="col-sm-4">
                <div class="form-group form-group-error">
                    {{Form::label('Expertise')}}
                    {{Form::text('expertise', null,['class'=>'form-control','placeholder'=>'Expertise'])}}
                    
                    @if ($errors->has('expertise')) 
                        <span class="form-text text-danger">{{ $errors->first('expertise') }}</span>
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group form-group-error">
                    {{Form::label('Working Hours')}} 
                    {{Form::number('working_hours', null,['class'=>'form-control','placeholder'=>'Working Hours', 'number' => true, 'min' => 0])}}

                    @if ($errors->has('working_hours')) 
                        <span class="form-text text-danger">{{ $errors->first('working_hours') }}</span>
                    @endif
                </div> 
            </div>
            <div class="col-sm-4 form-group form-group-error employeer">
                {!! Form::label('employer', __('Employer'). ':', ['class' => 'control-label thin-weight']) !!}
                
                {{Form::select('employer',$employers, $user->employer_id ?? "",['class'=>'form-control','placeholder'=>'Select Employer'])}}
                
                @if ($errors->has('employer'))  
                    <span class="form-text text-danger">{{ $errors->first('employer') }}</span>
                @endif
            </div> 
        </div>
        <div class="row">
            <div class="col-sm-4"> 
                <div class="form-group form-group-error">
                    {{Form::label('Password')}} @if(!isset($user->id)) * @endif
                    {{Form::password('password',['class'=>'form-control','placeholder'=>'Password'])}}

                    @if ($errors->has('pasfsword')) 
                        <span class="form-text text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>  
                <div class="form-group form-group-error">
                    {{Form::label('Date of Joining')}} *
                    {{Form::date('date_of_joining', null,['class'=>'form-control'])}}

                    @if ($errors->has('date_of_joining')) 
                        <span class="form-text text-danger">{{ $errors->first('date_of_joining') }}</span>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group form-group-error">
                    {{Form::label('Address')}}
                    {{Form::textarea('address', null,['class'=>'form-control','placeholder'=>'Address','rows'=>4])}}

                    @if ($errors->has('address')) 
                        <span class="form-text text-danger">{{ $errors->first('address') }}</span>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                @if(isset($user->profile_pic))
                    <img class="profile_pic" src="{{ asset($user->profile_pic) }}" alt="" width="100px">
                @else 
                    <img class="profile_pic" src="" alt="" width="100px">
                @endif
                <div class="form-group form-group-error">
                    {{Form::label('Profile Pic')}} 
                    {{Form::file('profile_pic', ['class'=>'form-control', 'id' => 'profile_pic'])}}
 
                    @if ($errors->has('profile_pic')) 
                        <span class="form-text text-danger">{{ $errors->first('profile_pic') }}</span>
                    @endif
                    @if(isset($user->profile_pic))
                        <input type="hidden" name="old_profile_pic" value="{{ $user->profile_pic }}">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wizard-footer height-wizard d-flex">
    <div class="mr-3">
        <input type='button' class='btn btn-previous btn-light-primary font-weight-bold btn-wd btn-sm' name='previous'
            value='Previous' />
    </div>
    <input type='reset' class='btn btn-light-primary font-weight-bold btn-wd btn-sm mr-3' name='cancel' value='cancel' /> 
    <div class="pull-right">
        <input type='button' class='btn btn-next btn-fill btn-primary btn-wd btn-sm' name='next' value='Next' /> 
        {!! Form::hidden('id', null, ['id' => 'id']) !!}
        {!! Form::hidden('back_url', $back_url) !!}
        {!! Form::submit($submitButtonText, ['class' => 'btn btn-finish btn-fill btn-primary btn-wd btn-sm', 'id' => 'submitEmployeee']) !!} 
    </div>
</div>


<script>

</script>