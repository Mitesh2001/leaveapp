@extends('layouts.master')
@section('content')
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon2-email text-primary"></i>
            </span>
            <h3 class="form_title">Update Email Template</h3>
        </div>
        <div class="mt-3">
            <a href="{{ route('emails.index') }}" class="btn btn-light-primary font-weight-bold">Back</a>
        </div>
    </div>
    <div class="card-body remove-padding-mobile"> 
        <div class="card card-custom gutter-b example example-compact">
            <!--begin::Form-->
            <div class="form">
                <div class="card-body">
                @include('layouts.alert')
                {!! Form::open(['route' => 'emails.store']) !!}
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            {!! Form::label('name', __('Name'), ['class' => '']) !!} *
                            {!! 
                                Form::text('name',
                                isset($emailTemplate['name']) ? $emailTemplate['name'] : null, 
                                ['class' => 'form-control','required','disabled']) 
                            !!}
                            {{Form::hidden('id',!empty($emailTemplate) ? $emailTemplate->email_template_id : null)}}
                        </div>
                        <div class="col-lg-6 form-group">
                            {!! Form::label('subject', __('Subject'), ['class' => '']) !!} * 
                            {!! 
                                Form::text('subject',
                                isset($emailTemplate['subject']) ? $emailTemplate['subject'] : null, 
                                ['class' => 'form-control','required']) 
                            !!}     
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12"> 
                            {!! Form::label('content', __('Email Template'), ['class' => '']) !!} *
                            {!! 
                                Form::textarea('content',  
                                isset($emailTemplate['content']) ? $emailTemplate['content'] : null, 
                                ['class' => 'form-control','id'=>'kt_tinymce_2','required']) 
                            !!} 
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                {!! Form::submit('Update', ['class' => 'btn btn-md btn-primary ml-3']) !!}
                                <a href="#" id="reset-email" class="btn btn-light-primary font-weight-bold">Cancel</a>
                            </div>
                        </div>
                    </div>
                {{Form::close()}}
                <div>
            <!--begin::Form-->
            <div>
        <div>
    </div>
</div>
@stop
@section('scripts')
<script>

$(document).ready(function (){
    $(document).on('click', '#reset-email', function (){
        location.reload(); 
    });
});

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
<script src="https://preview.keenthemes.com/metronic/theme/html/demo1/dist/assets/plugins/custom/tinymce/tinymce.bundle.js?v=7.2.8"></script>

<script src="{{ asset('plugins/custom/tinymce/tinymce.min.js') }}"></script>

@endsection      
	