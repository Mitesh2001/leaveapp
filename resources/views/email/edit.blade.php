{{-- Extends layout --}}
@extends('layouts.default')

@section('content')
<div class="card card-custom">
<div class="card-header d-flex justify-content-between">
    <div class="card-title remove-flex">
        <span class="card-icon">
            <i class="flaticon-shopping-basket text-primary"></i>
        </span>
        <h3 class="card-label">
            {{ __('Edit Email Template :email' , ['email' => '(' . $email->name. ')']) }}
        </h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('emails.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
    </div>
</div>
<div class="card-body">

    {!! Form::model($email, [
        'method' => 'PATCH',
        'route' => ['emails.update', encrypt($email->email_template_id)],
        'id' => 'EmailEditForm'
    ]) !!}

        @include('email.add_form', ['submitButtonText' => __('Update Email Template')])

    {!! Form::close() !!}
</div>
</div>
<!--end::Card-->

<script>
$(document).ready(function (){

    $("#EmailCreateForm").submit(function(element){
            
            if (CountCharacters() > 0) {
                return true;
            } else {    
                alert("Enter some template content !");
                return false;
            }
        });

    $("#EmailEditForm").validate({
        rules: {
                name: {
                    required: true,  
                },
                subject : {
                    required : true,
                },
                content : {
                    required : true,
                }    
            },
            messages: { 
                name: {
                    required: "Please enter template name!",    
                },
                subject : {
                    required : "Please enter template Subject!",
                },
                content : {
                    required : "Please enter some template content!",
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
})

function CountCharacters() {  
        var body = tinymce.get("kt_tinymce_2").getBody();  
        var content = tinymce.trim(body.innerText || body.textContent);  
        return content.length;  
    };

</script>

@stop