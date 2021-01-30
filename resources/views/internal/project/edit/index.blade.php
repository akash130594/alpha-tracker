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
                                    {{html()->form('post',route('internal.project.update.basic',$project->id))->acceptsFiles()->open()}}

                                    <div class="row">
                                        <div class="form-group col-sm-8">
                                            {!!Form::text('name', 'Project Name', $project->name)->placeholder('Project Name')!!}
                                        </div>
                                        <div class="form-group col-sm-4">
                                            {!!Form::text('code', 'Project Code', $project->code)->placeholder('Project Code')->disabled()!!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!!Form::select('study_type_id', 'Study Type', $study_types, $project->study_type_id)!!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!!Form::select('project_topic_id', 'Project Topic', $project_topics, $project->project_topic_id)!!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-5">
                                            {!!Form::select('client_id', 'Client Name', $clients, $project->client_id)!!}
                                        </div>
                                        <div class="form-group col-sm-4">
                                            {!!Form::select('client_var', 'Client Variable', [0 => 'No Variable Required', $project->client_var => $project->client_var], $project->client_var)!!}
                                        </div>
                                        <div class="form-group col-sm-3">
                                            {!!Form::text('client_project_no', 'Client Project no.', $project->client_project_no)->placeholder('Client Project/Offer ID')!!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!!Form::select('can_links', 'Canonical Links', [0 => 'No', 1 => 'Yes' ], $project->can_links)!!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!!Form::select('unique_ids_flag', 'Unique Links', [0 => 'No', 1 => 'Yes' ], $project->unique_ids_flag)->id('unique_links')!!}
                                            @if( $project->unique_ids_flag && !empty($project->unique_ids_file) )
                                                <small class="text-muted">Sample Link: {{$link}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 client_links">
                                            {!!Form::text('client_link', 'Client Link', $project->client_link)->id('client_link')->placeholder('Client Link')!!}
                                        </div>
                                        <div class="form-group col-sm-12 unique_link_file">
                                            <label>Unique File Attach:</label><br>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="unique_ids_file" name="unique_ids_file" disabled="disabled">
                                                    @if($project->unique_ids_file)
                                                    <label class="custom-file-label" for="customFile">{{$project->unique_ids_file}}</label>
                                                        @else
                                                    <label class="custom-file-label" for="customFile">Choose File</label>
                                                    @endif
                                            </div>
                                            {{--<div class="custom-file">
                                                {!! Form::file('unique_ids_file', 'Unique File Attachment') !!}
                                            </div>--}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!!Form::text('country_id', 'Country')->disabled(true)->value($country->name)!!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!!Form::text('language_id', 'Language')->disabled(true)->value($language->name)!!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!! Form::text('end_date', 'End Date', $project->end_date)->attrs(['data-toggle' => 'datetimepicker', 'data-target'=> '#end_date']) !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!! Form::text('cpi', 'Project CPI', $project->cpi)->disabled(false) !!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!! Form::text('quota', 'Project Quota', $project->quota)->disabled(false) !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!! Form::text('created_by', 'Project Manager', $project_user->name)->disabled(true) !!}
                                        </div>
                                        <div class="form-group col-sm-6 survey_dedupe_flg_div">
                                            <div class="form-group">
                                                <label for="survey_dedupe_flag">Dedupe Survey ?</label>
                                                <select type="select" name="survey_dedupe_flag" id="survey_dedupe_flag" class="form-control">
                                                    <option value="0" @if($project->survey_dedupe_flag==0) selected @endif>No</option>
                                                    <option value="1" @if($project->survey_dedupe_flag==1) selected @endif>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card dedupe_section" style="display: none;">
                                        <div class="card-header">
                                            Survey Dedupe
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-sm-3">
                                                    <label class="col-form-label">Respondent Status</label>
                                                </div>
                                                <div class="form-group col-sm-9">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="dedupe_status_attempted" @if($dedupe_status=="attempted") checked @endif value="attempted" name="dedupe[survey_dedupe_status]">
                                                        <label class="form-check-label" for="dedupe_status_attempted">Attempted</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="dedupe_status_completed" @if($dedupe_status=="completed") checked @endif value="completed" name="dedupe[survey_dedupe_status]">
                                                        <label class="form-check-label" for="dedupe_status_completed">Completed</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="dedupe_status_terminated" @if($dedupe_status=="completed") checked @endif value="completed" name="dedupe[survey_dedupe_status]">
                                                        <label class="form-check-label" for="dedupe_status_terminated">Disqualified</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-3">
                                                    <label class="col-form-label">Dedupe Filter</label>
                                                </div>
                                                <div class="form-group col-sm-9">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="de_dupe_selection" name="dedupe[de_dupe_type]" id="dedupe_survey_list" @if($dedupe_filter && $dedupe_filter['type']=="surveys_list") checked @endif  value="surveys_list" data-target="survey_list">
                                                        <label class="form-check-label" for="dedupe_survey_list">Surveys List</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="de_dupe_selection" name="dedupe[de_dupe_type]" id="dedupe_date_range" @if($dedupe_filter && $dedupe_filter['type']=="date_range") checked @endif value="date_range" data-target="date_range">
                                                        <label class="form-check-label" for="dedupe_date_range">Date Range</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="de_dupe_selection" name="dedupe[de_dupe_type]" id="dedupe_client_dedupe" @if($dedupe_filter && $dedupe_filter['type']=="client_dedupe") checked @endif value="client_dedupe" data-target="client_id">
                                                        <label class="form-check-label" for="dedupe_client_dedupe">Client</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="de_dupe_selection" name="dedupe[de_dupe_type]" id="dedupe_wildcard" @if($dedupe_filter && $dedupe_filter['type']=="wildcard_dedupe") checked @endif value="wildcard_dedupe" data-target="wildcard_survey_name">
                                                        <label class="form-check-label" for="dedupe_wildcard">Wildcard Name %</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @php dd("asdasaaaaas s"); @endphp
                                            <div class="row dedupe_actions_list">
                                                <div class="dedupe_action survey_list col-12">
                                                    <label>
                                                        Dedupe From Archive:
                                                    </label>&nbsp;
                                                    <input type="checkbox" @if($dedupe_filter['type']=="surveys_list" && !empty($dedupe_filter['archive']))) checked @endif  name="dedupe[data][archive]" value="1"><br>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <div class="form-group ">
                                                                <label for="dedupe_data_list">List Of Surveys</label>
                                                                <textarea rows="3" type="textarea" name="dedupe[data][surveys_list]" id="dedupe_data_list" class="form-control">@if($dedupe_filter['type']=="surveys_list"){{$dedupe_filter['content']}}@endif</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dedupe_action date_range col-12">
                                                    <label>
                                                        Dedupe From Archive:
                                                    </label>&nbsp;
                                                    <input type="checkbox" @if($dedupe_filter['type']=="date_range" && !empty($dedupe_filter['archive']))) checked @endif  name="dedupe[data][archive]" value="1"><br>
                                                    <div class="row">
                                                        <div class="form-group col-sm-6">
                                                            <div class="form-group ">
                                                                @php
                                                                    $from_date = null;
                                                                    $to_date = null;
                                                                    if($dedupe_filter['type']=='date_range'){
                                                                    foreach($dedupe_filter as $key=>$value){
                                                                    if($key=="content"){
                                                                       $from_date = $value['from_date'];
                                                                       $to_date = $value['to_date'];
                                                                    }
                                                                    }
                                                                    }
                                                                @endphp
                                                                <label for="dedupe_data_from_date">From Date:</label>
                                                                <input data-toggle="datetimepicker" data-target="#from_date" @if($from_date) value="{{$from_date}}"  @else value=""  @endif type="text" name="dedupe[data][date_range][from_date]" id="dedupe_data_from_date" class="form-control datetimepicker-input">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <div class="form-group ">
                                                                <label for="dedupe_data_to_date">To Date:</label>
                                                                <input data-toggle="datetimepicker" data-target="#to_date" @if($to_date) value="{{$to_date}}"  @else value=""  @endif type="text" name="dedupe[data][date_range][to_date]" id="dedupe_data_to_date" class="form-control datetimepicker-input">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dedupe_action client_id col-12">
                                                    <label>
                                                        Dedupe From Archive:
                                                    </label>&nbsp;
                                                    <input type="checkbox" @if($dedupe_filter['type']=="client_dedupe" && !empty($dedupe_filter['archive']))) checked @endif  name="dedupe[data][archive]" value="1"><br>
                                                    <div class="row">
                                                        <div class="form-group col-sm-6">
                                                            {!!Form::select('dedupe[data][client_dedupe]', 'Client Name', $clients, $project->client_id)!!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dedupe_action wildcard_survey_name col-12">
                                                    <label>
                                                        Dedupe From Archive:
                                                    </label>&nbsp;
                                                    <input type="checkbox" @if($dedupe_filter['type']=="wildcard_dedupe" && !empty($dedupe_filter['archive'])) checked @endif  name="dedupe[data][archive]" value="1"><br>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label>Survey Name Starts with</label>
                                                            <input class="form-control" type="text" @if($dedupe_filter['type']=="wildcard_dedupe") value="{{$dedupe_filter['content'] }}" @endif name="dedupe[data][wildcard_dedupe]">
                                                            {{--{!!Form::text('dedupe[data][wildcard_dedupe]', 'Survey Name Starts with')!!}--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @include('internal.project.includes.dedupe')
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

    <!-- Modal -->
    <div id="client_link_test" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Link Checker</h4>
                </div>
                <div class="modal-body">
                    <iframe class="client_link_test_iframe" src=""></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('after-styles')
    <style>

        #dedupe_data_list{
            white-space:pre-wrap;
        }
        .custom-file-label {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }
        .dedupe_action{
            display:none;
        }
        .unique_link_file{
            display:none;
        }
        .client_links{
            display:none;
        }
    </style>
@endpush

@push('after-scripts')
    <!-- Toastr style -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>

    <script>
        $('#unique_ids_file').change(function() {
            var i = $(this).find('label').clone();
            var file = $('#unique_ids_file')[0].files[0].name;
            console.log($('.custom-file-label').text(file));
        });

        if(($('select#survey_dedupe_flag').val()) === "0"){
            $('.card.dedupe_section').hide();
            $('.card.dedupe_section').find('input').attr('disabled', 'disabled');
            $('.card.dedupe_section').find('select').attr('disabled', 'disabled');
            $('.card.dedupe_section').find('textarea').attr('disabled', 'disabled');
        } else if(($('select#survey_dedupe_flag').val()) === "1") {

            $('.card.dedupe_section').show();
            $('.card.dedupe_section').find('input').attr('disabled', false);
            $('.card.dedupe_section').find('select').attr('disabled', false);
            $('.card.dedupe_section').find('textarea').attr('disabled', false);
        }
        if($('#dedupe_wildcard').prop("checked")){
            $action = "wildcard_survey_name";
            autoShowHideDiv($action);
        }
        if($('#dedupe_date_range').prop("checked")){
            $action = "date_range";
            autoShowHideDiv($action);
        }
        if($('#dedupe_survey_list').prop("checked")){
            $action = "survey_list";
            autoShowHideDiv($action);
        }
        if($('#dedupe_client_dedupe').prop("checked")){
            $action = "client_id";
            autoShowHideDiv($action);
        }
        function autoShowHideDiv(action)
        {
                $('.dedupe_action').hide();
                $('.dedupe_actions_list').find('.'+action).show();
        }
        $(document).ready(function(e){
            $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar-alt',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check-o',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                } });

            $('#end_date').datetimepicker({
                format: 'YYYY-MM-DD hh:mm:ss',
                showClose: true
            });
            // With a custom message
            $('#survey-edit').areYouSure( {'message':'Your profile details are not saved!'} );

            $('select#survey_dedupe_flag').on('change', function(e){
                console.log($(this).val());
                $dedupe_section = $('.card.dedupe_section');
                if ($(this).val() === "0") {
                    $dedupe_section.hide();
                    $dedupe_section.find('input').attr('disabled', 'disabled');
                    $dedupe_section.find('select').attr('disabled', 'disabled');
                    $dedupe_section.find('textarea').attr('disabled', 'disabled');
                }else if($(this).val() === "1") {
                    console.log("yes");
                    $dedupe_section.show();
                    $dedupe_section.find('input').attr('disabled', false);
                    $dedupe_section.find('select').attr('disabled', false);
                    $dedupe_section.find('textarea').attr('disabled', false);
                }
            });
            $('input[name="dedupe[de_dupe_type]"]').on('change', function(e){
                $('.dedupe_action').hide();
                $action = $(this).attr('data-target');
                console.log($action);
                $('.dedupe_actions_list').find('.'+$action).show();
            });
            if(($('#unique_links').val())==='1'){
                $('.unique_link_file').show();
                $('#unique_ids_file').removeAttr('disabled')
            } else{
                $('.client_links').show();
                $('#unique_ids_file').attr('disabled','disabled')
            }
            $('#unique_links').on('change', function (e) {
                var unique_link =   $(this).val();
                if(unique_link==='1'){
                    $('.client_links').hide();
                    $('.unique_link_file').show();
                    $('#unique_ids_file').removeAttr('disabled')
                } else{
                    $('.client_links').show();
                    $('.unique_link_file').hide();
                    $('#unique_ids_file').attr('disabled','disabled')
                }
            })

            $('#client_id').on('change', function(e) {

                var client_id = this.value;
                var headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }

                if(!client_id || client_id == 0){
                    return false;
                }
                //$('#dynamic_response_div').html('Loading....');
                // Make a request for a user with a given ID
                axios.get("{{ route('internal.project.client.fetch') }}", {
                    params: {
                        client_id: client_id,
                    }
                }).then(function (response) {
                    // handle success
                    if(response.status === 200){
                        $select = $('#client_var');
                        $select.find('option').remove();
                        var client_vars = response.data;
                        $.each(client_vars, function(value, name) {
                            $select.append($('<option />', {value: client_vars, text: name}));
                        });
                        //$('#dynamic_response_div').html(result);
                    }

                }).catch(function (error) {
                    // handle error
                    $('#dynamic_response_div').html('Some Error Occurred');
                }).then(function () {
                    // always executed
                    console.log('always executed');
                });
            });
        });
    </script>
@endpush
