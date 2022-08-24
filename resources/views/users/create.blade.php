@extends('layouts.master')
@section('heading')
    {{ __('Create user') }}
@stop
@section('styles')
    <link href="{{asset('css/gsdk-bootstrap-wizard.css')}}" rel="stylesheet" />
    <style>
        .employee-tab{
            height: 55px !important;
            text-align: center !important;
            padding: 10px !important;
        }
        .moving-tab {
            height: 55px !important;
        }
        .week-off-th{
            max-width: 140px !important;
        }
        .week-off-year{
            max-width: 80px !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-sm-offset-2">
            
            <div class="wizard-container p-0">
                <div class="card wizard-card" data-color="blue" id="wizardProfile">
                    <div class="wizard-header d-flex justify-content-between align-items-center" style="width: 100%">
                        <h4 class="pl-3"> 
                            Create <b>Employee</b>
                        </h4>
                        <div class="back-button pr-3">
                            <a href="{{ $back_url }}" class="btn btn-light-primary font-weight-bold">Back</a>
                        </div>
                    </div>
                    {!! Form::open([
                        'route' => 'users.store',
                        'class' => 'ui-form',
                        'id' => 'usersCreateForm',
                        'files' => true
                    ]) !!}
                            
                    @include('users.form', ['submitButtonText' => __('Create New Employee')])

                    {!! Form::close() !!} 
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
                    
    <script>
    $(document).ready(function () {

        $("#role").change(function() {
            if ($('option:selected', this).text() == "employee") {
                $(".employeer").removeClass("d-none");
            } else {
                $(".employeer").addClass("d-none");
            }
        });

        $.validator.addMethod('maxfilesize', function(value, element, param) {

            var length = ( element.files.length );
            var fileSize = 0;

            if (length > 0) {
                for (var i = 0; i < length; i++) {
                    fileSize = element.files[i].size; // get file size
                        // console.log("if" +length);
                            fileSize = fileSize / 1024; //file size in Kb
                            fileSize = fileSize / 1024; //file size in Mb
                        return this.optional( element ) || fileSize <= param;
                }
            }
            else
            {
                return this.optional( element ) || fileSize <= param;
            }
        }); 

        $("#usersCreateForm").validate({
            rules: {
                first_name: {
                    required: true,  
                },   
                last_name: {
                    required: true,  
                },   
                email: {
                    required: true,  
                    email: true,  
                    remote: {
                        url: '{!! route('users.checkemail') !!}',
                        type: "POST",
                        cache: false,
                        data: {
                            _token: "{{ csrf_token() }}",
                            email: function () {
                                return $("#email").val();
                            },
                            id: function () {
                                return $("#id").val();
                            },
                        }
                    }
                },    
                primary_number: {
                    required: true,
                    number: true,
                    minlength: 10,  
                    maxlength: 10,
                    remote: {
                        url: '{!! route('users.checkPrimaryNumber') !!}',
                        type: "POST",
                        cache: false,
                        data: {
                            _token: "{{ csrf_token() }}",
                            number: function () {
                                return $("#primary_number").val();
                            },
                            id: function () {
                                return $("#id").val();
                            },
                        }
                    }
                },  
                secondary_number: { 
                    number: true,
                    minlength: 10,  
                    maxlength: 10,
                },   
                role: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 12,
                },
                date_of_joining: {
                    required: true,
                }                
            },
            messages: { 
                first_name: {
                    required: "Please enter first name!",  
                },   
                last_name: {
                    required: "Please enter last name!",  
                },   
                email: {
                    required: "Please enter email address!",  
                    email: "Please enter valid email address!",  
                    remote: "Email already exist!",
                },    
                primary_number: {
                    required: "Please enter primary number!",
                    number: "Please enter valid primary number!",
                    minlength: "Please enter valid primary number!", 
                    maxlength: "Please enter valid primary number!",
                    remote: "Primary number already exist!",
                },  
                secondary_number: { 
                    number: "Please enter valid secondary number!",
                    minlength: "Please enter valid secondary number!",  
                    maxlength: "Please enter valid secondary number!",
                },
                role: {
                    required: "Plese select role!",
                },
                password: {
                    required: "Please enter password!",
                    minlength: "Password must be 6 to 12 character long!",
                    maxlength: "Password must be 6 to 12 character long!",
                },
                date_of_joining: {
                    required: "Please select date of joining!",
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

    <script src="{{asset('js/wizard/jquery.bootstrap.wizard.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/wizard/gsdk-bootstrap-wizard.js')}}"></script>
    <script src="{{asset('js/wizard/jquery.validate.min.js')}}"></script>
    <script src="{{asset('/plugins/custom/jquery_validate/additional-methods.min.js')}}"></script> 
    <script src="{{asset('js/employee.js')}}"></script>
@endsection