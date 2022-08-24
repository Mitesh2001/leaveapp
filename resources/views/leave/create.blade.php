@extends('layouts.master')
@section('styles')
    <style>
        .form-check-label{
            cursor: pointer;            
        }
    </style>
@endsection
@section('content') 
<div class="card card-custom">
<div class="card-header d-flex justify-content-between">
    <div class="card-title remove-flex">
        <span class="card-icon">
            <i class="flaticon2-list-3 text-primary"></i>
        </span>
        <h3 class="card-label">{{ __('Create Leave') }}</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('leaves.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
    </div>
</div>
<div class="card-body remove-padding-mobile">
    {!! Form::open([
        'route' => 'leaves.store',
        'class' => 'ui-form',
        'id' => 'leaveRequestForm',
        'files' => true
    ]) !!}
    
    @include('leave.form', ['submitButtonText' => __('Create Leave')])

    {!! Form::close() !!}
    </div>
</div>
<!--end::Card-->

    <script>
        $(document).ready(function () {
            $("#leaveRequestForm").validate({
                rules: {
                    person :  {
                        required: true,
                    },
                    type :  {
                        required: true,
                    },
                    start_date : {
                        required: true,                            
                    }
                },
                messages: { 
                    person: {
                        required: "Select Leave Person !",
                    },
                    type: {
                        required: "Select Leave Type !",
                    },
                    start_date : {
                        required: "Select start date !",                            
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
