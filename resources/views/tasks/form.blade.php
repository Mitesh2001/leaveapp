<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<div class="form">
		<div class="card-body dynamic-task-form remove-paddin-mobile">
			<div class="row">
                <div class="col-lg-6 form-group form-group-error">
                    {!! Form::label('admin', __('Admin'). ': *', ['class' => '']) !!}
                    {!! 
                        Form::text('admin',  
                        $admin,    
                        [
                        'class' => 'form-control',
                        'placeholder' => 'Admin',
                        'readonly' => 'readonly'
                        ]) 
                    !!}
                    @if ($errors->has('admin'))  
                        <span class="form-text text-danger">{{ $errors->first('admin') }}</span>
                    @endif
                </div>

                {{ Form::hidden('leave_id', $leaveManage->leave->id) }}
                {{ Form::hidden('behalf_of', $person->id) }}

			</div>
            @if (isset($tasks))
                @foreach ($tasks as $key => $task)                    
                <div class="row my-2 task">
                    <div class="border border-primary p-5 rounded">
                        <div class="row">
                            <div class="col-lg-6 form-group form-group-error">
                                {!! Form::label('title[]', __('Title'). ': ', ['class' => '']) !!}
                                {!! 
                                    Form::text('title[]',  
                                    $task->title,    
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
                                        $task->client_id, 
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
                                            json_decode($task->time_taken)->hours,
                                            ['class' => 'form-control',
                                            'required' => true,
                                            'placeholder' => 'Add Hours']) 
                                        !!}
                                    </div>
                                    <div class="col-6 form-group-error">
                                        {!! 
                                            Form::number('minutes[]',  
                                            json_decode($task->time_taken)->minutes,    
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
                                    $task->description, 
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
                @endforeach
            @endif
            <div class="d-flex justify-content-center new-task-btn">
                <button type="button" class="btn btn-secondary col-6 add-task"> + Add Task</button>
            </div>
		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col-lg-6">
					{!! Form::submit($submitButtonText, ['class' => 'btn btn-md btn-primary', 'id' => 'submitClient']) !!} 
					{!! Form::reset("Cancel", ['class' => 'btn btn-light-primary font-weight-bold', 'id' => 'submitClient']) !!}
				</div> 
			</div>
		</div>
	</div>
<!--end::Form-->
</div>
<!--end::Card--> 
<script>
    $(document).ready(function(){

        $("body").on("click",".add-task",function(){
            var newHtml = $(".copy").html();
            $(".new-task-btn").before(newHtml);
        });

        $("body").on("click",".remove",function(){
            $(this).parents(".task").remove();
        });
    });
</script>