{{-- Extends layout --}}
@extends('layouts.default')

@section('content')
@if (auth()->user()->can('leave-update'))
    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">							
                        <i class="flaticon2-list-3 text-primary"></i>							
                        <span>{{ __('Edit Leave') }}</span>
                    </h4>
                    <a href="{{ route('leaves.index') }}" class="close">&times;</a>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {!! Form::model($leave,[
                        'route' => ['leaves.update',$leave->external_id],
                        'method' => 'PATCH',
                        'class' => 'ui-form',
                        'id' => 'leaveEditForm',
                        'files' => true
                    ]) !!}
                    
                    @include('leave.form', ['submitButtonText' => __('Edit Leave')])
                
                    {!! Form::close() !!}
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Close</a>
                </div>
            </div>
        </div>
    </div>
@endif
@stop
@section('scripts')
<script>
    	$(document).ready(function (){
            $('#myModal').modal('toggle');
            @if ($leave->total_days > 1)
            $('input[type=radio][name=days_count]').filter('[value="multiple"]').attr('checked', true);
            $(".end-date-group").removeClass("d-none");
            @endif
        });
        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        })
        $(document).ready(function () {
            $("#leaveEditForm").validate({
                rules: {
                    person :  {
                        required: true,
                    },
                    type :  {
                        required: true,
                    },
                    start_date : {
                        required: true,                            
                    },
                    end_date : {                        
                        required: true,                        
                    }
                },
                messages: { 
                    person: {
                        required: "Select Leave Person !",
                    },
                    type: {
                        required: "Select Leave Type !",
                    },
                    start_date : {
                        required: "Select start date !",                            
                    },
                    end_date : {
                        required: "Select End date !",  
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
@endsection