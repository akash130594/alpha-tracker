@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Create Project')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="card">
                                <div class="card-body">
                                  {{--  {!!Form::open()->post()->autocomplete('off')->id('survey-create')!!}--}}
                                {{html()->form('post',route('internal.project.create.post.show'))->acceptsFiles()->open()}}
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            {!!Form::text('name', 'Project Name *')->placeholder('Project Name')!!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!!Form::select('study_type_id', 'Study Type', $study_types)!!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!!Form::select('project_topic_id', 'Project Topic', $project_topics)!!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-5">
                                            {!!Form::select('client_id', 'Client Name', array_merge([0 => 'Select Client'], $clients))!!}
                                        </div>
                                        <div class="form-group col-sm-4">
                                            {!!Form::select('client_var', 'Client Variable', [0 => 'No Variable Required'])!!}
                                        </div>
                                        <div class="form-group col-sm-3">
                                            {!!Form::text('client_project_no', 'Client Project no.')->placeholder('Client Project/Offer ID')!!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!!Form::select('can_links', 'Canonical Links', [0 => 'No', 1 => 'Yes' ])!!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!!Form::select('unique_ids_flag', 'Unique Links', [0 => 'No', 1 => 'Yes' ])->id('unique_link')!!}
                                        </div>
                                    </div>
                                        <div class="row">
                                        <div class="form-group col-sm-12 client-link">
                                            {!!Form::text('client_link', 'Client Link')->id('client_link')->placeholder('Client Link')!!}
                                        </div>
                                        <div class="form-group col-sm-12 unique_file">
                                            <label>Unique File Attach:</label><br>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="unique_ids_file" name="unique_ids_file" disabled="disabled">
                                                <label class="custom-file-label" for="customFile">Choose File</label>
                                                {{-- {!! Form::file('unique_ids_file', 'Unique File Attachment') !!}--}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label>Countries</label><br>
                                            <span>
                                            <select class="js-select2" id="countries" name="country_id">
                                               @foreach($countries as $key=>$country)
                                                    <option value = {{$key}}>{{$country}}</option>
                                                @endforeach
                                            </select>
                                            </span>
                                          {{--  {!!Form::select('country_id', 'Country', $countries)!!}--}}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label>Languages</label><br>
                                            <span>
                                            <select class="js-example-basic-single col-lg" id="languages" name="language_id">
                                               {{--@foreach($languages as $key=>$language)
                                                    <option value = {{$key}}>{{$language}}</option>
                                                @endforeach--}}
                                            </select>
                                            </span>
                                            {{--{!!Form::select('language_id', 'Language', $languages)!!}--}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!! Form::text('end_date', 'End Date')->attrs(['data-toggle' => 'datetimepicker', 'data-target'=> '#end_date']) !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {!! Form::text('cpi', 'Project CPI') !!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!! Form::text('quota', 'Project Quota',$project_quota) !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {{--Had To Produce a Range of LOI here--}}
                                            @php
                                                $loiValues = range(1, 60);
                                                $loiArray = [];
                                                foreach($loiValues as $value){
                                                    $postfix = str_plural('minute', $value);
                                                    $loiArray[$value] = $value.' '.$postfix;
                                                }
                                            @endphp
                                            {!! Form::select('loi', 'Project LOI (in Minutes)', $loiArray) !!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {{--Had To Produce a Range of IR here--}}
                                            @php
                                                $irValues = range(1, 100);
                                                $irArray = [];
                                                foreach($irValues as $value){
                                                    $irArray[$value] = $value.' %';
                                                }
                                            @endphp
                                            {!! Form::select('ir', 'Project IR %', $irArray) !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            {{--{!! Form::text('created_by_name', 'Project Manager', old('created_by_name',$current_user_name)) !!}--}}
                                            {!!Form::select('created_by', 'Project Manager', [$current_user_id => $current_user_name])!!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!! Form::select( 'survey_dedupe_flag', 'Dedupe Survey ?', [0=>'No', 1=> 'Yes'] )->id('survey_dedupe_flag') !!}
                                        </div>
                                    </div>
                                    {{--{!!Form::text('country_code', 'Country Code *')->placeholder('Country Code')->required()!!}
                                    {!!Form::text('language_code', 'Language Code *')->placeholder('Language Code')->required()!!}--}}
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
                                                        <input  type="radio" id="dedupe_status_attempted" value="attempted" name="dedupe[survey_dedupe_status]">
                                                        <label class="form-check-label" for="dedupe_status_attempted">Attempted</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input  type="radio" id="dedupe_status_completed" value="completed" name="dedupe[survey_dedupe_status]">
                                                        <label class="form-check-label" for="dedupe_status_completed">Completed</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" id="dedupe_status_terminated" value="terminated" name="dedupe[survey_dedupe_status]">
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
                                                            <input type="radio" class="de_dupe_selection" name="dedupe[de_dupe_type]" id="dedupe_survey_list" value="surveys_list" data-target="survey_list">
                                                        <label class="form-check-label" for="dedupe_survey_list">Project  List</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="de_dupe_selection" name="dedupe[de_dupe_type]" id="dedupe_date_range" value="date_range" data-target="date_range">
                                                        <label class="form-check-label" for="dedupe_date_range">Date Range</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="de_dupe_selection" name="dedupe[de_dupe_type]" id="dedupe_client_dedupe" value="client_dedupe" data-target="client_id">
                                                        <label class="form-check-label" for="dedupe_client_dedupe">Client</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="de_dupe_selection" name="dedupe[de_dupe_type]" id="dedupe_wildcard" value="wildcard_dedupe" data-target="wildcard_survey_name">
                                                        <label class="form-check-label" for="dedupe_wildcard">Wildcard Name %</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row dedupe_actions_list">
                                                <div class="dedupe_action survey_list col-12">
                                                    <label>
                                                        Dedupe From Archive:
                                                    </label>&nbsp;
                                                        <input type="checkbox" name="dedupe[data][archive]" value="1"><br>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <div class="form-group ">
                                                                <label class="font-weight-bold" for="dedupe_data_list">List Of Projects</label><br>
                                                                <span>Should be line seperated</span>
                                                                <textarea rows="3" type="textarea" name="dedupe[data][surveys_list]" id="dedupe_data_list" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dedupe_action date_range col-12">
                                                    <label>
                                                        Dedupe From Archive:
                                                    </label>&nbsp;
                                                    <input type="checkbox" name="dedupe[data][archive]" value="1"><br>
                                                    <div class="row">
                                                        <div class="form-group col-sm-6">
                                                            <div class="form-group ">
                                                                {!! Form::text('dedupe[data][date_range][from_date]', 'From Date (format "YY-MM-DD")')->attrs(['data-toggle' => 'datetimepicker', 'data-target'=> '#dedupe_from_date'])->id('dedupe_from_date') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <div class="form-group ">
                                                                {!! Form::text('dedupe[data][date_range][to_date]', 'To Date (format "YY-MM-DD")')->attrs(['data-toggle' => 'datetimepicker', 'data-target'=> '#dedupe_to_date'])->id('dedupe_to_date') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dedupe_action client_id col-12">
                                                    <label>
                                                        Dedupe From Archive:
                                                    </label>&nbsp;
                                                    <input type="checkbox" name="dedupe[data][archive]" value="1"><br>
                                                    <div class="row">
                                                        <div class="form-group col-sm-6">
                                                            {!!Form::select('dedupe[data][client_dedupe]', 'Client Name', $clients)!!}
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="dedupe_action wildcard_survey_name col-12">
                                                    <label>
                                                        Dedupe From Archive:
                                                    </label>&nbsp;
                                                    <input type="checkbox" name="dedupe[data][archive]" value="1"><br>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            {!!Form::text('dedupe[data][wildcard_dedupe]', 'Survey Code Starts with')!!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {!!Form::submit('Save')!!}
                                    {{html()->form()->close()}}
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
<style>
    .custom-file-label {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }
    </style>
    <style>
        .unique_file{
            display:none;
        }
        .dedupe_action{
            display:none;
        }
    </style>
@endpush

@push('after-scripts')
    <!-- Toastr style -->
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.23.0/moment.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>

        $('#unique_ids_file').change(function() {
            var i = $(this).find('label').clone();
            var file = $('#unique_ids_file')[0].files[0].name;
            $('.custom-file-label').text(file);
        });
        $('select#unique_link').on('change',function (e) {
            var i = $(this).val();
            if(i==='1'){
                $('.client-link').hide();
                $('.unique_file').show();
                $('#unique_ids_file').removeAttr('disabled')
            }else{
                $('.client-link').show();
                $('.unique_file').hide();
                $('#unique_ids_file').attr('disabled','disabled')
            }
        });
        $(document).ready(function(){

            $select2Opttions = {
                width: '100%',
                allowClear: false,
                height: '100%'
            }

            $('#countries').select2($select2Opttions);
            $('#languages').select2({
                //minimumInputLength: 1,
                width: '100%',
                allowClear: false,
                height: '100%',
                ajax: {
                    url: '{{route('internal.project.language.fetch')}}',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    data: function (term) {
                        $country_id = $('#countries').val();
                        return {
                            term: term,
                            country_id: $country_id
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true,
                }
            });


            $('select#survey_dedupe_flag').on('change', function(e){
                $dedupe_section = $('.card.dedupe_section');
                if ($(this).val() === "0") {
                    $dedupe_section.hide();
                    $dedupe_section.find('input').attr('disabled', 'disabled');
                    $dedupe_section.find('select').attr('disabled', 'disabled');
                    $dedupe_section.find('textarea').attr('disabled', 'disabled');
                }else if($(this).val() === "1") {
                    $dedupe_section.show();
                    $dedupe_section.find('input').attr('disabled', false);
                    $dedupe_section.find('select').attr('disabled', false);
                    $dedupe_section.find('textarea').attr('disabled', false);
                }
            });

            $('input[name="dedupe[de_dupe_type]"]').on('change', function(e){
                $('.dedupe_action').hide();
                $action = $(this).attr('data-target');
                $('.dedupe_actions_list').find('.'+$action).show();
                /*if($action==="date_range"){
                    $date_range = $('.dedupe_actions_list').find('.'+$action)   ;

                    $from_date = $date_range.find('#from_date').val();
                    $from_date.$('#from_date').datetimepicker({
                        format: 'YYYY-MM-DD hh:mm:ss',
                        showClose: true
                    });
                }*/
            });

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

            // With a custom message
            $('#survey-create').areYouSure( {'message':'Your profile details are not saved!'} );

            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            console.log(today);
            /*$('#start_date').datetimepicker({
                format: 'YYYY-MM-DD hh:mm:ss',
                setDate: new Date(),
                showClose: true
            });*/

            $('#end_date').datetimepicker({
                format: 'YYYY-MM-DD hh:mm:ss',
                setDate: addDays(15),
                showClose: true
            });
            $('#dedupe_from_date').datetimepicker({
                //format: 'YYYY-MM-DD hh:mm:ss',
                showClose: true
            });
            $('#dedupe_to_date').datetimepicker({
                //format: 'YYYY-MM-DD hh:mm:ss',
                showClose: true
            });

            function addDays(n){
                var t = new Date();
                t.setDate(t.getDate() + n);
                var month = "0"+(t.getMonth()+1);
                var date = "0"+t.getDate();
                var hours = "0"+t.getHours();
                var minutes = "0"+t.getMinutes();
                var seconds = "0"+t.getSeconds();
                month = month.slice(-2);
                date = date.slice(-2);
                var date = t.getFullYear()+"-"+month +"-"+date+" "+hours+":"+minutes+":"+seconds;
                $('#end_date').val(date);
            }

            $('.test_client_link').on('click', function(e) {
                $client_link = $('#client_link').val();
                if ( !$client_link || $client_link == '' ) {
                    console.log('returned');
                    return false;
                }
                console.log('outside');
                $('.client_link_test_iframe').attr('src', $client_link);
                $("#client_link_test").modal('toggle');
                //alert('tada');
            });

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
                            $select.append($('<option />', {value: name, text: name}));
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
