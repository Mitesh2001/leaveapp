@extends('layouts.master')
@section('content') 
<div class="card card-custom">
<div class="card-header d-flex justify-content-between">
    <div class="card-title">
        <span class="card-icon">
            <i class="flaticon2-menu text-primary"></i>
        </span>
        <h3 class="card-label">{{ __('Create Client') }}</h3>
    </div> 
    <div class="mt-3">
        <a href="{{ route('clients.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
    </div>
</div>
<div class="card-body remove-padding-mobile">
  
    {!! Form::open([
        'route' => 'clients.store',
        'class' => 'ui-form',
        'id' => 'clientCreateForm'
    ]) !!}
        @include('clients.form', ['submitButtonText' => __('Create New Client')])

    {!! Form::close() !!}

    </div>
</div>

    <script>    
        $(document).ready(function () { 

            $("#clientCreateForm").validate({
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
                        //         id: function () {
                        //             return $("#id").val();
                        //         },
                        //         email: function () {
                        //             return $("#primary_email").val();
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
                    project_name : {
                        required: true,
                        
                    },
                    secondary_number: { 
                        number: true,
                        minlength: 10,
                        maxlength: 10,
                    },   
                    'admins_list[]': {
                        required : true
                    },
                    state_id: {
                        required: true,
                    },
                    country_id: {
                        required: true
                    }
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
                    primary_number: {
                        required: "Please enter contact number!",
                        number: "Please enter valid contact number!",
                        minlength: "Please enter valid contact number!",
                        maxlength: "Please enter valid contact number!",
                        // remote: "Number already exist!"
                    }, 
                    project_name : {
                        required: "Project name is required !",
                        
                    },
                    'admins_list[]': {
                        required : "This is field is required !",
                    },
                    secondary_number: { 
                        number: "Please enter valid WhatsApp number!",
                        minlength: "Please enter valid WhatsApp number!",
                        maxlength: "Please enter valid WhatsApp number!",
                    }, 
                    state_id: {
                        required: "Please select state!",
                    },
                    country_id: {
                        required: "Please select country!",
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
                    $(element).closest('.form-group-error').append(error);
                }
            });

        });
    </script>
 
@stop
