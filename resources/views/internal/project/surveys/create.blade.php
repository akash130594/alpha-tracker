@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Survey Creation')
                    </strong>
                </div><!--card-header-->
                <div class="card-body">
                    <div class="card col-sm-8">
                        <div class="card-body">
                            {{html()->form('POST',route('internal.project.post.surveys',[$project_id,$project_vendor_id]))->open()}}
                            <div class="form-group row">
                                <label class="font-weight-bold col-sm-4 col-form-label">Survey Type:</label>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input creation_type" type="radio" name="creation_type" id="create_type_manual" value="manual">
                                        <label class="form-check-label" for="create_type_manual">Manual</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input creation_type" type="radio" name="creation_type" id="create_type_automatic" value="automatic">
                                        <label class="form-check-label" for="create_type_automatic">Automatic</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="font-weight-bold col-sm-4 col-form-label">Vendor Survey Code:</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="vendor_survey_code" id="vendor_survey_code" placeholder="Enter Vendor Survey Code">
                                    <input type="hidden" name="vendor_code" value="{{$vendor_code}}">
                                    {{-- <input type="hidden" name="vendor_id" value="{{$project_vendor_id}}">--}}
                                    <input type="hidden" name="project_code" value="{{$project_code}}">
                                    <input type="hidden" name="status_id" value="{{$status_id}}">

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="font-weight-bold col-sm-4 col-form-label">Dedupe (Current Survey Group):</label>
                                <div class="col-sm-8">
                                    <select name="collection_dedupe" class="link_flag">
                                        <option id="no_link_flag" value="0" selected>No</option>
                                        <option id="link_flag"  value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row de_dupe_collection" style="display:none" disabled="disabled">
                                <label class="font-weight-bold col-sm-4 col-form-label">Respondent Status</label>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input excl_url_link" id="survey_dedupe_attempted"  disabled="disabled"  type="radio" value="attempted" name="dedupe_status">
                                        <label class="form-check-label" for="survey_dedupe_attempted">Attempted</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input excl_url_link" id="survey_dedupe_completed" disabled="disabled"  type="radio" value="completed" name="dedupe_status">
                                        <label class="form-check-label" for="survey_dedupe_completed">Completed</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input excl_url_link" id="survey_dedupe_terminated" disabled="disabled"  type="radio" value="terminated" name="dedupe_status">
                                        <label class="form-check-label" for="survey_dedupe_terminated">Disqualified</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row de_dupe_collection" style="display:none" disabled="disabled">
                                <label class="font-weight-bold col-sm-4 col-form-label">Survey Codes</label>
                                <div class="col-sm-8">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="inputGroupSelect01">Current Group</label>
                                        <select class="custom-select" name="collection_ids[]" disabled="disabled" multiple id="inputGroupSelect01">
                                            @foreach($get_surveys_details as $get_surveys_detail )
                                                <option value="{{$get_surveys_detail['id']}}">
                                                    {{$get_surveys_detail['vendor_survey_code']}}-{{$get_surveys_detail['project_code']}}-{{$get_surveys_detail['code']}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="font-weight-bold col-sm-4 col-form-label">Exclusive End Pages</label>
                                <div class="col-sm-8">
                                    <select name="sy_excl_link_flag" class="link_url_flag" title="exl_link">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row excl_link" style="display:none">
                                <div class="form-group col-md-6">
                                    <label for="syv_complete">Complete</label>
                                    <input class="form-control excl_link_detail" id="syv_complete" disabled="disabled" size="50" type="text" name="syv_complete" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="syv_complete">Terminate</label>
                                    <input class="form-control excl_link_detail" id="syv_complete" disabled="disabled" size="50" type="text" name="syv_terminate" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="syv_complete">QuotaFull</label>
                                    <input class="form-control excl_link_detail" id="syv_complete" disabled="disabled" size="50" type="text" name="syv_quotafull" value="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="syv_complete">Quality Term</label>
                                    <input class="form-control excl_link_detail" id="syv_complete" disabled="disabled" size="50" type="text" name="syv_qualityterm" value="">
                                </div>
                            </div>

                            <div class="row text-center">
                                <button class="btn btn-primary pull-right" type="submit">Create</button>
                            </div>
                            {{html()->form()->close()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div><!-- card -->

    @push('after-scripts')
        <!-- Toastr style -->
        <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>

        <script>
            $(document).ready(function(){
            $('.creation_type').on('change',function(e){
                var value = $(this).val();
                if(value === 'automatic'){
                    $('input#vendor_survey_code').attr('disabled','disabled').val();

                } else{
                    $('input#vendor_survey_code').removeAttr('disabled');
                }
            });
                $('select.link_url_flag').on('change', function (e) {
                    /*$('.excl_link').toggle('');
                    $(this).removeAttr("disabled");*/
                    /*$('.excl_link_detail').removeAttr("disabled");*/
                    $excLink = $('.excl_link');

                    if($(this).val() === "0"){
                        $($excLink).hide();
                        $('.excl_link_detail').attr("disabled","disabled");
                    }else{
                        $($excLink).show();
                        $(this).removeAttr("disabled");
                        $('.excl_link_detail').removeAttr("disabled");
                    }
                }).change();
                $("form")[0].reset();
            });
            $('select.link_flag').on('change', function (e) {
                $exclUrl = $('.de_dupe_collection');

                if($(this).val() === "0"){
                    $($exclUrl).hide();
                    $('.excl_url_link').attr("disabled","disabled");
                    $('select.custom-select').attr("disabled","disabled");
                }else{
                    $($exclUrl).show();
                    $(this).removeAttr("disabled");
                    $('.excl_url_link').removeAttr("disabled");
                    $('select.custom-select').removeAttr("disabled");
                }
            }).change();
            $("form")[0].reset();
            </script>
    @endpush
    @push('after-styles')
        <style>
            .de_dupe_collection{
                display: none;
            }
            </style>
        <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
    @endpush

@endsection
