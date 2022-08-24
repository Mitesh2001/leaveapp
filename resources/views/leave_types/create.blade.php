@extends('layouts.master')
@section('content') 

<?php
    $data = Session::get('data');
?>
<div class="card card-custom">
<div class="card-header d-flex justify-content-between">
    <div class="card-title remove-flex">
        <span class="card-icon">
            <i class="flaticon2-list-3 text-primary"></i>
        </span>
        <h3 class="card-label">{{ __('Create Leave') }}</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('leave-types.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
    </div>
</div>
<div class="card-body remove-padding-mobile">
    {!! Form::open([
        'route' => 'leave-types.store',
        'class' => 'ui-form',
        'id' => 'leaveCreateForm',
        'files' => true
    ]) !!}
    
        @include('leave_types.form', ['submitButtonText' => __('Create New Leave Type')])

    {!! Form::close() !!}
    </div>
</div>
<!--end::Card-->

    <script>
        $(document).ready(function () {
            $("#roleCreateForm").validate({
                rules: {
                    name: {
                        required: true,
                        remote: {
                            // url: "{!! route('roles.check_name') !!}",
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
                },
                messages: { 
                    name: {
                        required: "Please enter role name!",
                        remote: "Role name already exists !"
                    }
                },
                normalizer: function( value ) { 
                    return $.trim( value );
                },
                errorElement: "span",
                errorClass: "form-text text-danger",
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group-error').append(error);
                }
            });

        });
    </script>

@stop
