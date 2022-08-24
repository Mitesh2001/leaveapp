{{-- Extends layout --}}
@extends('layouts.default')

@section('content')
<div class="card card-custom">
<div class="card-header d-flex justify-content-between">
    <div class="mt-3">
        <a href="{{ route('leave-types.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
    </div>
</div>
<div class="card-body remove-padding-mobile">
    {!! Form::model($leave_type, [
            'method' => 'PATCH',
            'route' => ['leave_types.update', $leave_type->external_id],
            'id' => 'leaveEditForm'
    ]) !!}
    @include('leave_types.form', ['submitButtonText' => __('Update Leave Type')])

    {!! Form::close() !!}
</div>
</div>
<!--end::Card-->

<script>    
        $(document).ready(function () {

            $("#leaveEditForm").validate({
                rules: {
                    name: {
                        required: true,
                    }, 
                },
                messages: { 
                    name: {
                        required: "Please enter name!",
                    },  
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
                    $(element).closest('.col-lg-6').append(error);
                }
            });

        });
    </script>
@stop