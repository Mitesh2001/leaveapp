<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<div class="form">
		<div class="card-body remove-paddin-mobile">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-error">
                        {{-- {!! Form::label('person', __('Person'). ':', ['class' => '']) !!} --}}
                        {{-- {!!
                            Form::select('person',
                            $person ?? [],
                            isset($leave->person_id) ? $leave->person_id : [], 
                            ['class' => 'form-control',
                            'id' => 'person',  
                            'style' => 'width:100%'])
                        !!}  --}}
                        <select name="person" id="person" style = 'width:100%'>
                            @if (isset($leave))
                                <option value="{{$leave->person_id}}" selected>{{$person_name}}</option>
                            @endif
                        </select>
                        @if ($errors->has('person'))  
                            <span class="form-text text-danger">{{ $errors->first('person') }}</span>
                        @endif  
                    </div> 
                </div>
            </div> 
			<div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-error">
                        {!! Form::label('type', __('Leave Type'). ' :', ['class' => '']) !!}
                        {{Form::select('type',$types, 
                        isset($leave) ? $leave->type_id : "",
                        ['class'=>'form-control','placeholder'=>'Leave Type'])}}
                        @if ($errors->has('type'))  
                            <span class="form-text text-danger">{{ $errors->first('type') }}</span>
                        @endif  
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 form-group">
                    <label class="form-check-label text-center bg-light col-12 border rounded p-2" for="flexRadioDefault1">
                        <div class="form-check">
                            <input class="form-check-input" id="flexRadioDefault1" type="radio" name="days_count" value="single" checked>
                            <span class="font-weight-bold">Single Day</span>
                        </div>
                    </label>
                </div>
                <div class="col-md-5 form-group">
                    <label class="form-check-label text-center bg-light col-12 border rounded p-2" for="flexRadioDefault2">
                        <div class="form-check">
                            <input class="form-check-input" id="flexRadioDefault2" type="radio" name="days_count" value="multiple">
                            <span class="font-weight-bold">Multiple Days</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 form-group">
                    <div class="form-group form-group-error">
                        {{Form::label('Start Date')}} *
                        {{
                            Form::date('start_date',$leave->start_date ?? old('start_date'),
                            [
                                'class'=>'form-control',
                                'id' => 'start_date',
                                'value' => date('Y-m-d'),
                                'onchange' => 'countDays();setMinimumEndDate();',
                                'onkeyup' => 'countDays()'
                            ])
                        }}

                        @if ($errors->has('start_date')) 
                            <span class="form-text text-danger">{{ $errors->first('start_date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-5 form-group end-date-group d-none">
                    <div class="form-group form-group-error">
                        {{Form::label('End Date')}} *
                        {{
                            Form::date('end_date',$leave->end_date ?? old('end_date'),
                            [
                                'class'=>'form-control',
                                'id' => 'end_date',
                                'value' => date('Y-m-d'),
                                'onchange' => 'countDays()',
                                'onkeyup' => 'countDays()'
                            ])
                        }}

                        @if ($errors->has('end_date')) 
                            <span class="form-text text-danger">{{ $errors->first('end_date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-2 form-group">
                    <div class="form-group form-group-error">
                        {!! Form::label('total_days', __('Total Days'). ':', ['class' => '']) !!}
                        {!! Form::number('total_days',$leave->total_days ?? 1,[
                            'class' => 'text-center form-control',
                            'id' =>'total_days',
                            'readonly' => 'readonly'
                            ]) 
                        !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="form-group form-group-error">
                        {!! Form::label('notes', __('Notes'). ':', ['class' => '']) !!}
                        {!! 
                            Form::textarea('notes',  
                            $data['notes'] ?? old('notes'), 
                            ['class' => 'form-control',
                            'rows' => 2,
                            'placeholder' => "Notes (Optional)"]) 
                        !!}
                        @if ($errors->has('description'))  
                            <span class="form-text text-danger">{{ $errors->first('description') }}</span>
                        @endif  
                    </div>
                </div>
			</div>
            <div class="row">
                <div class="col-md-3 form-group form-group-error">
                    {!! Form::label('attachment', __('Attachment'). ' :', ['class' => '']) !!}
                    {{Form::file('attachment', ['class'=>'form-control', 'id' => 'attachment'])}} 
                    @if ($errors->has('attachment')) 
                        <span class="form-text text-danger">{{ $errors->first('profile_pic') }}</span>
                    @endif                   
                </div>
            </div>
		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col-lg-6">
					{!! Form::submit($submitButtonText, ['class' => 'btn btn-md btn-primary', 'id' => 'submitLeave']) !!} 
					{!! Form::reset("Cancel", ['class' => 'btn btn-light-primary font-weight-bold', 'id' => 'submitLeave']) !!}
				</div> 
			</div>
		</div>
	</div>
<!--end::Form-->
</div>
<!--end::Card--> 
<script>
	$(document).ready(function (){

        $('input[type=radio][name=days_count]').change(function() {
            if (this.value == 'multiple') {
                $(".end-date-group").removeClass("d-none");
                countDays();
            }
            else if (this.value == 'single') {
                $(".end-date-group").addClass("d-none");
                $("#total_days").val(1);
            }
        });

        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
        $("#start_date").attr("min", today);
        $("#end_date").attr("min", today);
        if ($("#start_date").val() == "") {
            $("#start_date").val(today);
        }
        if ($("#end_date").val() == "") {
            $("#end_date").val(today);
        }

		$("#person").select2({
			placeholder: "Select Person",
			allowClear: true,
			ajax: {
				url: '{!! route('leaves.persons_list') !!}',
				dataType: 'json', 
				processResults: function (data, param) {  
					return {
						results: $.map(data, function (item) { 
							return {
								text: item.first_name+" "+ item.last_name,
								id: item.id
							}
						})
					};
				}
			}
		})

	});

    function setMinimumEndDate(){
        var start_date = new Date($("#start_date").val());
        var day = ("0" + start_date.getDate()).slice(-2);
        var month = ("0" + (start_date.getMonth() + 1)).slice(-2);
        var date = start_date.getFullYear()+"-"+(month)+"-"+(day) ;
        $("#end_date").attr("min", date);
        $("#end_date").val(date);
    }

    function countDays()
    {
        var start_date = new Date($("#start_date").val());
        var end_date = new Date($("#end_date").val());
        var millisBetween = end_date.getTime() - start_date.getTime() ;
        var days = millisBetween / (1000 * 3600 * 24);
        if (days<0) {
            $("#end_date").val("");
        } else {
            $("#total_days").val(days+1);
        }
    }
</script>