@extends('layouts.master')
@section('content') 

<div class="card card-custom">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title remove-flex">
            <span class="card-icon">
                <i class="flaticon2-supermarket text-primary"></i>
            </span>
            <h3 class="card-label">{{ __('Permission management') }} ({{ $role->display_name }})</h3>
        </div>
        <div class="mt-3">
            <a href="{{ route('roles.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
        </div>
    </div>
    <div class="card-body"> 
        <div class="card card-custom gutter-b example example-compact">  
            <div class="card-body">
                {!! Form::model($permissions_grouping, [
                    'method' => 'PATCH',
                    'id' => 'roleUpdateForm',
                    'route' => ['roles.update', $role->external_id],
                ]) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-group-error">
                            {!! Form::label('display_name', __('Display Name'). ' :', ['class' => '']) !!}
                            {!! 
                                Form::text('display_name',  
                                $role->display_name, 
                                ['class' => 'form-control',
                                'id' => 'role_name',
                                'placeholder' => "Role Name"]) 
                            !!}
                        </div>
                        @if ($errors->has('display_name'))  
                            <span class="form-text text-danger">{{ $errors->first('display_name') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-error">
                            {!! Form::label('user_type', __('User Type'). ' :', ['class' => '']) !!}
                            
                            {{Form::select('user_type',$user_types, $role->user_type,['class'=>'form-control','id' => 'user_type', 'placeholder'=>'select type'])}}
                            
                            @if ($errors->has('user_type')) 
                                <span class="form-text text-danger">{{ $errors->first('user_type') }}</span>
                            @endif
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12 form-group-error">
                        {!! Form::label('description', __('Description'). ' :', ['class' => '']) !!}
                        {!! 
                            Form::textarea('description',  
                            $role->description, 
                            ['class' => 'form-control',
                            'rows' => 2,
                            'placeholder' => "Description"]) 
                        !!}
                        @if ($errors->has('description'))  
                            <span class="form-text text-danger">{{ $errors->first('description') }}</span>
                        @endif  
                    </div> 
                </div>
                <hr> 
                <div class="row">
                    <div class="col-lg-12">
                        @foreach($permissions_grouping as $permissions)
                        <div class="row">
                            @if($permissions->first)
                            <div class="col-md-2">
                                <p class="calm-header">{{ucfirst(__($permissions->first()->grouping))}} </p>
                            </div>
                            @endif
                            <div class="col-md-9 row">
                                @foreach($permissions as $permission) 
                                    <?php 
                                    $isEnabled = !current(
                                        array_filter(
                                            $role->permissions->toArray(),
                                            function ($element) use ($permission) {
                                                return $element['id'] === $permission->id;
                                            }
                                        )
                                    );  
                                    ?>
                                    <div class="col-lg-4">
                                        <label class="option">
                                            <span class="option-control">
                                                <label class="checkbox">
                                                    <input type="checkbox" {{ !$isEnabled ? 'checked' : ''}}
                                                    name="permissions[ {{ $permission->id }} ]" value="1" data-role="{{ $role->id }}" class="form">
                                                    <span></span>
                                                </label>
                                            </span>
                                            <span class="option-label">
                                                <span class="option-head">
                                                    <span class="option-title">{{ $permission->display_name }}</span> 
                                                </span>
                                                <span class="option-body">{{ $permission->description }}</span>
                                            </span>
                                        </label>
                                    </div>  
                                @endforeach
                            </div>
                        </div>
                        <hr>
                        @endforeach                                                   
                            
                        @if(auth()->user()->can('role-update'))
                            {!! Form::submit( __('Update Role') , ['class' => 'btn float-right btn-primary']) !!}
                        @endif
                        
                        {!! Form::close(); !!}
                        
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>
<!--end::Card-->  
@stop
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.2.6.min.js"></script>
<script>
    $(document).ready(function () {
        $("#roleUpdateForm").validate({
            rules: {
                display_name: {
                    remote: {
                        url: "{!! route('roles.check_name') !!}",
                        type: "POST",
                        cache: false,
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: function () {
                                return $("#role_name").val();
                            },
                        }
                    }
                },  
                user_type : {
                    required : true
                } 
            },
            messages: { 
                display_name: {
                    remote: "Role name already exists !"
                },
                user_type : {
                    required: "Please select User Type !",
                }
            },
        });
    });
</script>
