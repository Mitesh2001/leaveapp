@extends('layouts.master')

@section('content')

<div class="card card-custom">
    <div class="card-header justify-content-between">
        <div class="card-title remove-flex">
            <span class="card-icon">
                <i class="flaticon-shopping-basket text-primary"></i>
            </span>
            <h3 class="card-label">MY Works</h3>
        </div>
        <div class="mt-3">
            <a href="{{ route('tasks.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="copy d-none">
            <div class="row my-2 task">
                <div class="border border-primary p-5 rounded">
                    <div class="row">
                        <div class="col-lg-6 form-group form-group-error">
                            {!! Form::label('title[]', __('Title'). ': ', ['class' => '']) !!}
                            {!! 
                                Form::text('title[]',  
                                "",    
                                ['class' => 'form-control',
                                'required' => true,
                                'placeholder' => 'Task Title']) 
                            !!}
                            @if ($errors->has('title'))  
                                <span class="form-text text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-6 form-group form-group-error">
                            {!! Form::label('client[]', __('Select Client'). ':', ['class' => '']) !!}
                                {!!
                                    Form::select('client[]',
                                    $clients,
                                    Null, 
                                    ['class' => 'form-control',
                                    'id' => 'clients',
                                    'placeholder' => 'Client', 
                                    'style' => 'width:100%'])
                                !!} 
                                @if ($errors->has('client'))  
                                    <span class="form-text text-danger">{{ $errors->first('client') }}</span>
                                @endif
                        </div>
                        <div class="col-lg-6 form-group ">
                            {!! Form::label('', __('Task Hours (HH:MM)'). ' :', ['class' => '']) !!}
                            <div class="row">
                                <div class="col-6 form-group-error">
                                    {!! 
                                        Form::number('hours[]',  
                                        "",    
                                        ['class' => 'form-control',
                                        'required' => true,
                                        'placeholder' => 'Add Hours']) 
                                    !!}
                                </div>
                                <div class="col-6 form-group-error">
                                    {!! 
                                        Form::number('minutes[]',  
                                        "",    
                                        ['class' => 'form-control',
                                        'required' => true,
                                        'placeholder' => 'Add Minutes']) 
                                    !!}
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group form-group-error">
                            {!! Form::label('description[]', __('Task Description'). ':', ['class' => '']) !!}
                            {!! 
                                Form::textarea('description[]',  
                                $data['description'] ?? old('description'), 
                                ['class' => 'form-control',
                                'rows' => 2,
                                'placeholder' => "Description"]) 
                            !!}
                            @if ($errors->has('description'))  
                                <span class="form-text text-danger">{{ $errors->first('description') }}</span>
                            @endif  
                        </div> 
                    </div>
                    <button type="button" class="btn btn-danger remove"> - Delete</button>
                </div>
            </div>
        </div>
    {!! Form::open([
        'route' => ['tasks.update',$leavemanage->external_id],
        'class' => 'ui-form',
        'id' => 'TaskEditForm'
    ]) !!}
            
        @include('tasks.form', ['submitButtonText' => __('Submit Tasks')])
            
    {!! Form::close() !!}  
    </div>
</div>
 
<script>
    $(document).ready(function () {

        $("#TaskEditForm").submit(function(element){
            var countDiv = $("#TaskEditForm").find(".task").length;
            if (countDiv > 0) {
                return true;
            } else {
                alert("Please Add atleast one Task !");
                return false;
            }
        });

        $("#TaskEditForm").validate({
            rules: {
                admin: {
                    required: true,  
                },  
                client: {
                    required: true,  
                },
                title :{
                    required : true
                },
                'hours[]' : {
                    min:0
                },
                'minutes[]' : {
                    max: 59,
                    min:0
                }   
            },
            messages: { 
                admin: {
                    required: "Please select Admin!",
                },
                title : {
                    required : "Please add task !"
                },  
                client: {
                    min: "Enter Valid Minutes!",
                },
                'hours[]': {
                    min : "Enter Valid Hours!",
                    max: "Enter Valid Hours!"
                },
                'minutes[]' : {
                    min : "Enter Valid Minutes!",
                    max: "Enter Valid Minutes!"
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


