@extends('layouts.master')
@section('content') 

<div class="card card-custom">
<div class="card-header d-flex justify-content-between">
    <div class="card-title remove-flex">
        <span class="card-icon">
            <i class="flaticon2-list-3 text-primary"></i>
        </span>
        <h3 class="card-label">{{ __('Edit Holiday') }}</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('holidays.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
    </div>
</div>
<div class="card-body remove-padding-mobile">
    {!! Form::model($holiday,[
        'route' => ['holidays.update',$holiday->external_id],
        'method' => 'PATCH',
        'class' => 'ui-form',
        'id' => 'holidayCreateForm',
        'files' => true
    ]) !!}
    
        @include('holidays.form', ['submitButtonText' => __('Update Holiday')])

    {!! Form::close() !!}
    </div>
</div>
<!--end::Card-->

    <script>
        $(document).ready(function () {
            $("#holidayCreateForm").validate({
                rules: {
                    title: {
                        required: true,
                        // remote: {
                        //     url: "{!! route('roles.check_name') !!}",
                        //     type: "POST",
                        //     cache: false,
                        //     data: {
                        //         _token: "{{ csrf_token() }}",
                        //         name: function () {
                        //             return $("#role_name").val();
                        //         },
                        //     }
                        // }
                    },
                    date : {
                        required : true
                    }  
                },
                messages: { 
                    title: {
                        required: "Please Enter Holiday Title!",
                        // remote: "Holiday title already Exist !"
                    },
                    date : {
                        required: "Please select holiday date !",
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
