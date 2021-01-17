@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Edit Project')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @include('internal.project.includes.edit_tabs')
                            <div class="card">
                                <div class="card-body">
                                    {!!Form::open()->patch()->autocomplete('off')->id('survey-panel_invite-edit')!!}
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Choose Invite Template</h5>
                                        </div>
                                    </div>
                                    @php
                                        $is_quotaSpecific = (!empty($inviteData) && $inviteData[0]['is_generic'] == 0)?true:false;
                                        $is_custom_template = (!empty($inviteData) && count($inviteData) == 1 && $inviteData[0]['is_custom'] == 1)?true:false;
                                        //Todo-fix this code here to support quota specific invites
                                    @endphp
                                    <div class="row">
                                        <div class="col-12">
                                            <p>
                                                Quota Specific invite
                                            </p>
                                            <div class="switch">
                                                <div class="onoffswitch">
                                                    <label class="switch switch-label switch-outline-primary-alt">
                                                        <input class="switch-input" type="checkbox" name="quota_specific_template" id="quota_specific_template" @if( !empty($is_quotaSpecific) ) checked="checked" @endif>
                                                        <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p>
                                                Custom Invite Template ?
                                            </p>
                                            <div class="switch">
                                                <div class="onoffswitch">
                                                    <label class="switch switch-label switch-outline-primary-alt">
                                                        <input class="switch-input" type="checkbox" name="custom_invite_template_flag" id="custom_invite_template_flag" @if( !empty($is_custom_template) ) checked="checked" @endif>
                                                        <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-12">
                                            @if( !empty($inviteTemplates) )
                                                @foreach($inviteTemplates as $template)
                                                    <div class="col-3 float-left invite_template_item p-0">
                                                        <div class="custom-contact-box center-version">

                                                            <a href="javascript:void(0);">
                                                                <input
                                                                    type="radio"
                                                                    name="selected_template"
                                                                    @if(!empty($inviteData) && $is_quotaSpecific == false && $inviteData[0]["invite_template_id"] == $template->id) checked="checked" @endif
                                                                    id="invitetemplate_{{$template->id}}" value="{{$template->id}}" class="input-hidden" />
                                                                <label for="invitetemplate_{{$template->id}}">
                                                                    <img
                                                                        src="{{asset('screenshots/'.$template->image_url)}}"
                                                                        alt="{{$template->label}}" />
                                                                </label>

                                                                <h3 class="m-b-xs"><strong>{{$template->label}}</strong></h3>

                                                                <div class="font-bold">Subject</div>

                                                                <span onclick="displayInviteText(this);">{{$template->subject}}</span><br>

                                                                <div class="invite_template_body_div" style="display:none;">
                                                                    <textarea class="invite_template_body">{{$template->body}}</textarea>
                                                                    <button type="button" class="btn btn-info" onclick="hideInviteBody(this);">hide</button>
                                                                </div>
                                                            </a>
                                                            <a class="center-version" data-project_id="{{$project->id}}" data-template_id="{{$template->id}}" href="{{route('internal.project.edit.templates.edit',[$project->id,$template->id])}}">Edit</a><br>
                                                            <a class="center-version preview_temp" data-project_id="{{$project->id}}" data-template_id="{{$template->id}}" data-template_body="{{$template->body}}" href="javascript:void(0);">Preview</a><br>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <div class="col-3 float-left">
                                                <div class="custom-contact-box center-version">

                                                    <a class="btn" href="{{route('internal.project.edit.panel_invite.custom.show', [$project->id])}}" id="custom_invite_template_a">

                                                        <i class="fas fa-plus fa-5x"></i>

                                                        <h3 class="m-b-xs"><strong>Custom</strong></h3>

                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <p>
                                                Test Invite
                                            </p>
                                            <div class="test_mail_form_section">
                                                <div class="form-group">
                                                    <label for="test_mail_textarea">Email Ids (comma Seprated)</label>
                                                    <textarea class="form-control" id="test_mail_textarea"></textarea>
                                                </div>
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary" id="testMailSenderButton">Send Test Mail</button>
                                                </div>
                                                <div class="hr-line-dashed"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {!!Form::submit('Save')!!}

                                    {!!Form::close()!!}
                                </div>
                            </div>

                        </div>
                    </div><!-- row -->
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->

    <div class="modal template_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Your body is : </label><br>
                    <span id="current_status_span"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>

                </div>
            </div>
        </div>
    </div>
    {{--<div class="modal inmodal" id="customInviteModalForm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>

                    <h4 class="modal-title">Custom Invite Template</h4>
                </div>
                <div class="modal-body">
                    <form id="custom_invite_template_modal_form">
                        <input type="hidden" name="custom_invite_flag" value="true" class="disabled_modal_input">
                        <div class="form-group">
                            <label>Subject</label>
                            <input disabled="true" name="custom_invite_subject" id="T_G_NAME" placeholder="Custom Subject" type="text"  class="form-control disabled_modal_input">
                        </div>
                        <div class="form-group">
                            <label>Body</label>
                            <div data-editable data-name="main-content">
                                <blockquote>
                                    Always code as if the guy who ends up maintaining your code will be a violent psychopath who knows where you live.
                                </blockquote>
                                <p>John F. Woods</p>
                            </div>
                            --}}{{--<textarea data-editable data-name="main-content" name="custom_invite_body" id="custom_invite_body" class="form-control disabled_modal_input custom_invite_body"></textarea>--}}{{--
                        </div>
                    </form>
                    <div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="customInviteModalSubmit">Save changes</button>
                </div>
            </div>
        </div>
    </div>--}}
@endsection

@push('after-styles')
    <style>
        /* for invite section */
        .custom-contact-box.center-version{
            border: 1px solid #e7eaec;
            padding: 0;
        }
        .custom-contact-box.center-version > a {
            display: block;
            background-color: #ffffff;
            padding: 20px 10px;
            text-align: center;
        }
        .custom-contact-box .contact-box-footer {
            text-align: center;
            background-color: #ffffff;
            border-top: 1px solid #e7eaec;
            padding: 15px 20px;
        }
        .input-hidden {
            position: absolute;
            left: -9999px;
        }

        input[type=radio]:checked + label>img {
            border: 1px solid #fff;
            box-shadow: 0 0 3px 3px #090;
        }

        /* Stuff after this is only to make things more pretty */
        input[type=radio] + label>img {
            border: 1px dashed #444;
            width: 150px;
            height: 150px;
            transition: 500ms all;
        }

        input[type=radio]:checked + label>img {
            transform:
                rotateZ(-10deg)
                rotateX(10deg);
        }

    </style>
@endpush

@push('after-scripts')
    <!-- Toastr style -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
    <script>
        $(document).ready(function(e){
            $('.preview_temp').on('click',function (e) {
                var body = $(this).attr("data-template_body");
                $('#current_status_span').html(body);
                $('.template_modal').modal('toggle');
            })

            // With a custom message
            $('#survey-panel_invite-edit').areYouSure( {'message':'Your profile details are not saved!'} );

            function displayInviteText(element)
            {
                var clickedElement = jQuery(element);
                var currentInviteSection = clickedElement.closest('.invite_template_item');

                var invite_body = currentInviteSection.find('.invite_template_body_div');
                invite_body.show("slide", {}, 500, function() {

                });

            }

            function hideInviteBody(element)
            {
                var clickedElement = jQuery(element);
                var currentInviteSection = clickedElement.closest('.invite_template_item');

                var invite_body = currentInviteSection.find('.invite_template_body_div');
                invite_body.hide("slide", {}, 500, function() {

                });
            }

            function executeInviteSelectionStepForward()
            {
                var inviteSelectedForm = jQuery('.invite_step_form');
                var inviteSelectedFormData = inviteSelectedForm.serializeArray();

                var custom_invite_flag = false;
                $.each(inviteSelectedFormData, function( index, value ) {
                    if(value.name == 'custom_invite_template_flag'){
                        custom_invite_flag  = true;
                    }
                });

                if(custom_invite_flag){
                    var custominviteSelectedForm = jQuery('form#custom_invite_template_modal_form');
                    inviteSelectedFormData = custominviteSelectedForm.serializeArray();
                }

                var res = confirm("Do you want to leave the invite selection step?");
                if(res){
                    storeInviteInformation(inviteSelectedFormData);
                }
                return res;
            }

            function storeInviteInformation(formData)
            {
                /*if(!formData){
                    return false;
                }
                var headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }

                var globalForm = jQuery('#globalQuotaForm');
                var uniqueSessionCode = globalForm.find('input#uniqueStepCode').val();
                var project_id = globalForm.find('input#project_id').val();

                var postData =  {
                    formData: formData,
                    uniqueSessionCode: uniqueSessionCode
                }

                if(project_id != ''){
                    postData['project_id'] = project_id;
                }

                axios.post("{{--{{ route('inpanel.survey.quota.project.tempdata.save', 'invite') }}--}}",postData,headers)
                    .then(function (response) {
                        try{
                            if(response.data.status == true){
                                var globalForm = jQuery('#globalQuotaForm');
                                globalForm.find('#invite_form_status').val('1');
                            }
                        }catch(error){
                            console.log('error in invite ajax');
                        }

                    })
                    .catch(function (error) {
                        alert('some Error occured');
                        console.log(error);
                    });*/
            }

            jQuery('a#custom_invite_template_anchor').on('click', function(e){
                jQuery('#customInviteModalForm').modal('toggle');
            });

            jQuery('#custom_invite_template_flag').on('click', function(e){
                var element = jQuery(e.target);
                var inviteInputs = jQuery('#customInviteModalForm').find('.disabled_modal_input');
                if(element.is(':checked')){
                    inviteInputs.each(function( index ) {
                        $( this ).removeAttr('disabled');
                    });
                }else{
                    inviteInputs.each(function( index ) {
                        $( this ).attr('disabled',true);
                    });
                }

            });

            jQuery('#customInviteModalSubmit').on('click',function(e){
                jQuery('#customInviteModalForm').modal('toggle');
            });

            jQuery('#testMailSenderButton').on('click',function(e){
                var inviteSelectedForm = jQuery('.invite_step_form');
                var inviteSelectedFormData = inviteSelectedForm.serializeArray();

                var custom_invite_flag = false;
                $.each(inviteSelectedFormData, function( index, value ) {
                    if(value.name == 'custom_invite_template_flag'){
                        custom_invite_flag  = true;
                    }
                });

                if(custom_invite_flag){
                    var custominviteSelectedForm = jQuery('form#custom_invite_template_modal_form');
                    inviteSelectedFormData = custominviteSelectedForm.serializeArray();
                }
                var testMailIds = jQuery.trim(jQuery("#test_mail_textarea").val());


                if(testMailIds != ''){
                    //console.log(inviteSelectedFormData, testMailIds);
                    executeSendTestInvite(inviteSelectedFormData, testMailIds);
                }
            });

            function executeSendTestInvite(formData, testMailIds)
            {
                /*if(!formData){
                    return false;
                }
                var headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }

                var postData =  {
                    formData: formData,
                    testMailIds:testMailIds
                }

                axios.post("{{--{{ route('inpanel.survey.quota.project.sendTestInvite') }}--}}",postData,headers)
                    .then(function (response) {
                        try{
                            console.log(response);
                        }catch(error){
                            console.log('error in test invite ajax');
                        }

                    })
                    .catch(function (error) {
                        alert('some Error occured');
                        console.log(error);
                    });*/
            }

        });
    </script>

@endpush
