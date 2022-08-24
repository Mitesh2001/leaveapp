<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<div class="form">
		<div class="card-body">
			<div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-error">
                        {!! Form::label('title', __('Title'). ' : *', ['class' => '']) !!}
                        {!! 
                            Form::text('title',  
                            isset($holiday['title']) ? $holiday['title'] : old('title'),
                            ['class' => 'form-control',
                            'id' => 'title',
                            'placeholder' => "Title"]) 
                        !!}
                        @if ($errors->has('title'))  
                            <span class="form-text text-danger">{{ $errors->first('title') }}</span>
                        @endif  
                    </div>
                </div>
            </div>
            <div class="row">                                    
                <div class="form-group col-md-4 form-group-error">
                    {!! Form::label('date', __('Date'). ' : *', ['class' => '']) !!}
                    
                    {{
                        Form::date('date',
                            isset($holiday['date']) ? $holiday['date'] : old('date'),
                            [
                                'class'=>'form-control',
                                'id' => 'date',
                                'onchange' => '$("#day").val((new Date(this.value)).getDay());setMinimumEndDate();'
                            ]
                        )
                    }}
                    
                    @if ($errors->has('date')) 
                        <span class="form-text text-danger">{{ $errors->first('date') }}</span>
                    @endif
                </div>
                <div class="form-group col-md-4 form-group-error">
                    {!! Form::label('end_date', __('End Date'). ' : ', ['class' => '']) !!}
                    
                    {{Form::date('end_date',
                        isset($holiday['end_date']) ? $holiday['end_date'] : old('end_date'),
                    ['class'=>'form-control','id' => 'end_date'])}}
                    
                    @if ($errors->has('end_date')) 
                        <span class="form-text text-danger">{{ $errors->first('end_date') }}</span>
                    @endif
                </div> 
                <div class="form-group col-md-4 form-group-error">
                    {!! Form::label('day', __('Day'). ' : ', ['class' => '']) !!}
                    
                    {{Form::select('day',$days, 
					isset($holiday['day']) ? $holiday['day'] : null,
                    ['class'=>'form-control','id' => 'day','placeholder'=>'--select--'])}}
                    
                    @if ($errors->has('day')) 
                        <span class="form-text text-danger">{{ $errors->first('day') }}</span>
                    @endif
                </div>                                     
            </div>
            <div class="row">
                <div class="col-md-12"> 
                    <div class="form-group form-group-error">
                        {!! Form::label('description', __('Description'). ' :', ['class' => '']) !!}
                        {!! 
                            Form::textarea('description',  
                            isset($holiday['description']) ? $holiday['description'] : null,
                            ['class' => 'form-control',
                            'rows' => 2,
                            'placeholder' => "Description"]) 
                        !!}
                        @if ($errors->has('description'))  
                            <span class="form-text text-danger">{{ $errors->first('description') }}</span>
                        @endif  
                    </div> 
                </div>
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

// $(document).ready(function (){

    function setMinimumEndDate(){
        var start_date = new Date($("#date").val());
        var day = ("0" + start_date.getDate()).slice(-2);
        var month = ("0" + (start_date.getMonth() + 1)).slice(-2);
        var date = start_date.getFullYear()+"-"+(month)+"-"+(day) ;
        $("#end_date").attr("min", date);
        $("#end_date").val(date);
    }
// });

    
</script>