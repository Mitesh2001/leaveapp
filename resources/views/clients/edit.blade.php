{{-- Extends layout --}}
@extends('layouts.default')

@section('content')
<div class="card card-custom">
<div class="card-header d-flex justify-content-between">
    <div class="card-title">
        <span class="card-icon">
            <i class="flaticon2-menu text-primary"></i>
        </span>
        <h3 class="card-label">{{ __('Edit Client') }}</h3>
    </div> 
    <div class="mt-3">
        <a href="{{ route('clients.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
    </div>
</div>
<div class="card-body remove-padding-mobile">
    {!! Form::model($client, [
            'method' => 'PATCH',
            'route' => ['clients.update', $client->external_id],
            'id' => 'clientEditForm'
    ]) !!}
    @include('clients.form', ['submitButtonText' => __('Update client')])

    {!! Form::close() !!}
</div>
</div>
<!--end::Card-->

<script>    
        $(document).ready(function () {

            $("#clientEditForm").validate({
                rules: {
                    name: {
                        required: true,
                    }, 
                    primary_email: {
                        required: true,
                        email: true,
                        // remote: {
                        //     url: '{!! route('clients.checkemail') !!}',
                        //     type: "POST",
                        //     cache: false,
                        //     data: {
                        //         _token: "{{ csrf_token() }}",
                        //         email: function () {
                        //             return $("#email").val();
                        //         },
                        //         id: function () {
                        //             return $("#id").val();
                        //         }
                        //     }
                        // }
                    }, 
                    primary_number: {
                        required: true,
                        number: true,
                        minlength: 10,
                        maxlength: 10,
                        // remote: {
                        //     url: '{!! route('clients.checkPrimaryNumber') !!}',
                        //     type: "POST",
                        //     cache: false,
                        //     data: {
                        //         _token: "{{ csrf_token() }}",
                        //         number: function () {
                        //             return $("#primary_number").val();
                        //         },
                        //         id: function () {
                        //             return $("#id").val();
                        //         }
                        //     }
                        // }
                    }, 
                    // 'admins_list[]': {
                    //     required : true
                    // },
                    project_name : {
                        required: true,                        
                    },
                    secondary_number: { 
                        number: true,
                        minlength: 10,
                        maxlength: 10,
                    },   
                },
                messages: { 
                    name: {
                        required: "Please enter name!",
                    },
                    primary_email: {
                        required: "Please enter email!",
                        email: "Please enter valid email address!",
                        // remote: "Email already exist!",
                    },
                    // 'admins_list[]': {
                    //     required : "This is field is required !",
                    // },
                    primary_number: {
                        required: "Please enter contact number!",
                        number: "Please enter valid contact number!",
                        minlength: "Please enter valid contact number!",
                        maxlength: "Please enter valid contact number!",
                        // remote: "Number already exist!",
                    }, 
                    project_name : {
                        required: "Project name is required !",                        
                    },
                    secondary_number: { 
                        number: "Please enter valid WhatsApp number!",
                        minlength: "Please enter valid WhatsApp number!",
                        maxlength: "Please enter valid WhatsApp number!",
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