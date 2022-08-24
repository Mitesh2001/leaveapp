<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
	<!--begin::Form-->
	<div class="form">
		<div class="card-body"> 
            <div class="row">
                <div class="col-lg-6 form-group form-group-error">
                    {!! Form::label('name', __('Name :'), ['class' => '']) !!} *
                    {!! 
						Form::text('name',
						isset($emailTemplate['name']) ? $emailTemplate['name'] : null, 
						['class' => 'form-control','required']) 
					!!}
                    {{Form::hidden('id',!empty($emailTemplate) ? $emailTemplate->email_template_id : null)}}
                </div>
                <div class="col-lg-6 form-group form-group-error">
                    {!! Form::label('subject', __('Subject :'), ['class' => '']) !!} * 
                    {!! 
                        Form::text('subject',
                        isset($emailTemplate['subject']) ? $emailTemplate['subject'] : null, 
                        ['class' => 'form-control','required']) 
                    !!}  
                    @if ($errors->has('event_type'))  
                        <span class="form-text text-danger">{{ $errors->first('event_type') }}</span>
                    @endif   
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-12"> 
                    {!! Form::label('content', __('Email Template'), ['class' => '']) !!} *
                    {!! 
                        Form::textarea('content',  
                        null, 
                        ['class' => 'form-control','id'=>'kt_tinymce_2','required']) 
                    !!} 
                    @if ($errors->has('content'))  
                        <span class="form-text text-danger">{{ $errors->first('content') }}</span>
                    @endif
                </div>
            </div>
			<div class="form-group row">
				<div class="col-md-6 ">
					{!! Form::label('event_type', __('Event Type'). ':', ['class' => '']) !!}
					{{Form::select('event_type',
						['leave_notification' => "Leave Notification"],
						$email->event_type ?? NULL,
						['class'=>'form-control','placeholder'=>'Select Event Type'])}}
				</div>
			</div>
		</div>
		<div class="card-footer">
            <div class="row">
                <div class="col-lg-6 form-group">
                    {!! Form::submit($submitButtonText, ['class' => 'btn btn-md btn-primary ml-3']) !!}
                    <a href="#" id="reset-email" class="btn btn-light-primary font-weight-bold">Cancel</a>
                </div>
            </div>
		</div>
	</div>
<!--end::Form-->
</div>
<!--end::Card-->  

<script>
$(document).ready(function () {


	function countSms()
	{
		let message_length = $("#message").val().length; 
		let messages_count = Math.ceil(message_length / 160); 
		let insertText = messages_count + " SMS credit will be deducted for every SMS"; 

		if(messages_count > 0) {
			$("#sms-count-message").html(insertText);
		} else {
			$("#sms-count-message").html("");
		}  
	}

});
</script>

<script src="https://preview.keenthemes.com/metronic/theme/html/demo1/dist/assets/plugins/custom/tinymce/tinymce.bundle.js?v=7.2.8"></script>
<script src="{{ asset('plugins/custom/tinymce/tinymce.min.js') }}"></script>
<script> 

var KTTinymce = function () {
    var emailTemplateEditor = function () {
        tinymce.init({
            selector: '#kt_tinymce_2',
            plugins: ['table'],
            forced_root_block : "", 
            force_br_newlines : true,
            force_p_newlines : false,
            statusbar: false,
        });
    }
    return {
        init: function() {
            emailTemplateEditor();
        }
    };
}();

jQuery(document).ready(function() {
    KTTinymce.init();
});
</script>
 
	