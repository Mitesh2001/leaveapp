@extends('layouts.master')
@section('styles')
    <style>
        
    </style>
@endsection
@section('content') 
<div class="card card-custom">
<div class="card-header d-flex justify-content-between">
    <div class="card-title remove-flex">
        <span class="card-icon">
            <i class="flaticon2-list-3 text-primary"></i>
        </span>
        <h3 class="card-label">{{ __('Manage Leave') }}</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('manage_leaves.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
    </div>
</div>
<div class="card-body remove-padding-mobile">
    {!! Form::open([
        'route' => ['manage_leaves.update',$leave_manage->external_id],
        'method' => 'PATCH',
        'class' => 'ui-form',
        'id' => 'editLeaveManagementForm'
    ]) !!}
    
    @include('leave_management.form', ['submitButtonText' => __('Edit Data')])

    {!! Form::close() !!}
    </div>
</div>
<!--end::Card-->

    <script>
        $(document).ready(function () {
            $("#editLeaveManagementForm").validate({
                rules: {
                    'clients[]' :  {
                        required: true,
                    },
                    email_template : {
                        required : true,
                    },
                    alternate_person :  {
                        required: true,
                    }
                },
                messages: { 
                    'clients[]': {
                        required: "Select Clients !",
                    },
                    email_template : {
                        required : "Select Email Template !",
                    },
                    alternate_person: {
                        required: "Select Alternate Person !",
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
