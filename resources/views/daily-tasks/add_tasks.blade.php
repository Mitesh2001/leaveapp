@extends('layouts.master')
@section('content')
<div class="d-none copy">
    <div class="row my-2 task">
        <div class="border border-primary p-5 rounded box shadow ">
            <div class="row">
                <div class="col-lg-6 form-group form-group-error">
                    {!! Form::label('project_name[]', __('Project Name'). ' ', ['class' => '']) !!}
                    {!! 
                        Form::text('project_name[]',  
                        "",    
                        ['class' => 'form-control',
                        'required' => true,
                        'placeholder' => 'Enter Project Name']) 
                    !!}
                    @if ($errors->has('project_name'))  
                        <span class="form-text text-danger">{{ $errors->first('project_name') }}</span>
                    @endif
                </div>                                    
                <div class="col-lg-6 form-group ">
                    {!! Form::label('', __('Task Hours (HH:MM)'). ' ', ['class' => '']) !!}
                    <div class="row">
                        <div class="col-6 form-group-error">
                            {!! 
                                Form::number('hours[]',  
                                "",
                                ['class' => 'form-control hourcount',
                                'required' => true,
                                'onchange' => 'calcHours()',
                                'placeholder' => 'Add Hours']) 
                            !!}
                        </div>
                        <div class="col-6 form-group-error">
                            {!! 
                                Form::number('minutes[]',  
                                "",    
                                ['class' => 'form-control minutecount',
                                'required' => true,
                                'onchange' => 'calcHours()',
                                'placeholder' => 'Add Minutes']) 
                            !!}
                        </div>                                        
                    </div>
                    <small class="form-text text-muted">Add Hours and Minutes for example 02:45</small>
                </div> 
            </div>
            <div class="row">
                <div class="col-lg-12 form-group form-group-error">
                    {!! Form::label('description[]', __('Task Details'). '', ['class' => '']) !!}
                    {!! 
                        Form::textarea('description[]',  
                        "", 
                        ['class' => 'form-control',
                        'rows' => 2,
                        'placeholder' => "Enter Task Details Here..."]) 
                    !!}
                    
                    @if ($errors->has('description'))  
                        <span class="form-text text-danger">{{ $errors->first('description') }}</span>
                    @endif  
                </div> 
            </div>
            
            <div class="d-flex justify-content-between">
                <div></div>
                <button type="button" class="btn btn-danger remove"> - Delete</button>
            </div>
        </div>                        
    </div>
</div>
<div class="row">
	<div class="col-lg-12">
        @if(Session::has('flash_message')) 
			<div class="alert alert-success" role="alert">
				{{Session::get('flash_message') }}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true"><i class="ki ki-close"></i></span>
				</button>
			</div>
		@endif
	</div>
</div>
{!! Form::open([
    'route' => 'my-task.store',
    'class' => 'ui-form',
    'id' => 'DailyTaskCreateForm'
]) !!}
             
<div class="card card-custom">
    <div class="card-header justify-content-between">
        <div class="card-title remove-flex">
            {{-- // --}}
        </div> 
        <div class="card-title sticky remove-flex">
            <h4>Total Time Taken : <strong id="totalhours">00:00</strong></h4>
        </div>        
    </div>
    <div class="card-body">        
        <!--begin::Form-->
        <div class="form">
            <div class="card-body dynamic-task-form remove-paddin-mobile">
                <div class="row my-2">
                    <div class="row">
                        <div class="col-lg-12 form-group form-group-error">
                            {!! Form::label('leavetaken', __('Leave Taken'). '', ['class' => '']) !!}
                            {!!
                                Form::select('leavetaken',
                                [
                                    'Quarter Leave' => "Quarter Leave"
                                ],
                                "", 
                                ['class' => 'form-control select2',
                                'placeholder' => 'Select Leave Type', 
                                'style' => 'width:100%'])
                            !!} 
                            @if ($errors->has('leavetaken'))  
                                <span class="form-text text-danger">{{ $errors->first('leavetaken') }}</span>
                            @endif
                            <small class="form-text text-muted">If Any Leave Taken Today</small>
                        </div>
                    </div>
                </div>
                <div class="row my-2 task">
                    <div class="border border-primary p-5 rounded box shadow ">
                        <div class="row">
                            <div class="col-lg-6 form-group form-group-error">
                                {!! Form::label('project_name[]', __('Project Name'). ' ', ['class' => '']) !!}
                                {!! 
                                    Form::text('project_name[]',  
                                    "",    
                                    ['class' => 'form-control',
                                    'required' => true,
                                    'placeholder' => 'Enter Project Name']) 
                                !!}
                                @if ($errors->has('project_name'))  
                                    <span class="form-text text-danger">{{ $errors->first('project_name') }}</span>
                                @endif
                            </div>                                    
                            <div class="col-lg-6 form-group ">
                                {!! Form::label('', __('Task Hours (HH:MM)'). ' ', ['class' => '']) !!}
                                <div class="row">
                                    <div class="col-6 form-group-error">
                                        {!! 
                                            Form::number('hours[]',  
                                            "",
                                            ['class' => 'form-control hourcount',
                                            'required' => true,
                                            'onchange' => 'calcHours()',
                                            'placeholder' => 'Add Hours']) 
                                        !!}
                                    </div>
                                    <div class="col-6 form-group-error">
                                        {!! 
                                            Form::number('minutes[]',  
                                            "",    
                                            ['class' => 'form-control minutecount',
                                            'required' => true,
                                            'onchange' => 'calcHours()',
                                            'placeholder' => 'Add Minutes']) 
                                        !!}
                                    </div>                                        
                                </div>
                                <small class="form-text text-muted">Add Hours and Minutes for example 02:45</small>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-lg-12 form-group form-group-error">
                                {!! Form::label('description[]', __('Task Details'). '', ['class' => '']) !!}
                                {!! 
                                    Form::textarea('description[]',  
                                    "", 
                                    ['class' => 'form-control',
                                    'rows' => 2,
                                    'placeholder' => "Enter Task Details Here..."]) 
                                !!}
                                
                                @if ($errors->has('description'))  
                                    <span class="form-text text-danger">{{ $errors->first('description') }}</span>
                                @endif  
                            </div> 
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <div></div>
                            <button type="button" class="btn btn-danger remove"> - Delete</button>
                        </div>
                    </div>                        
                </div>
                <div class="d-flex justify-content-center new-task-btn">
                    <button type="button" class="btn btn-secondary col-6 add-task"> + Add Task</button>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-6">
                        {!! Form::submit("SUBMIT YOUR TASKs", ['class' => 'btn btn-md btn-primary', 'id' => 'submitClient']) !!} 
                    </div> 
                </div>
            </div>
        </div>
        <!--end::Form-->        
    </div>
</div>

{!! Form::close() !!}

@endsection
@section('scripts')
<script>

    $(function () {
        $('.select2').select2();
    });

    $(document).ready(function(){

        $("body").on("click",".add-task",function(){
            var newHtml = $(".copy").html();
            $(".new-task-btn").before(newHtml);
        });

        $("body").on("click",".remove",function(){
            $(this).parents(".task").remove();
            calcHours();
        });

    });

    function calcHours(){
        var hours = 0;
        var minutes = 0;
        
        jQuery('.hourcount').each(function(index) {
            var time = jQuery(this).val();
            if(time!='')
            hours = hours + parseInt(time);		
        });
        
        jQuery('.minutecount').each(function(index) {
            var time = jQuery(this).val();
            if(time!='')
            minutes = minutes + parseInt(time);
        });
        
        var totmin = minutes/60;
        totmin = Math.floor(totmin);

        var tothr = hours+totmin;
        if(tothr<10)
            tothr = '0'+tothr;
        var totmin = minutes%60;
        if(totmin<10)
            totmin = '0'+totmin;
            
        jQuery('#totalhours').html(''+tothr+':'+totmin);
    }

</script>
@endsection