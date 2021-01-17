@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('View Details')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="col-sm-12">
                                        <tr>
                                            <td> <label>Survey Code: </label>&nbsp&nbsp{{$archive['code']}}</td>
                                            <td> <label>Survey Name:</label>&nbsp&nbsp {{$archive['name']}}</td>
                                            <td><label>Study Type:</label>&nbsp&nbsp{{$archive['study_type']}}</td>
                                        </tr>
                                        <tr>
                                            <td> <label>Client Name: </label>&nbsp&nbsp{{$archive['client_name']}}</td>
                                            <td><label>Client Variable:</label>&nbsp&nbsp{{$archive['client_var']}}</td>
                                            <td> <label>Client Link:</label>&nbsp&nbsp{{$archive['client_link']}}</td>
                                            <td><label>Client Project No.:</label>&nbsp&nbsp{{$archive['client_project_no']}}</td>
                                        </tr>
                                        <tr>
                                            @if($archive['unique_id']==0)
                                                <td><label>Unique Link:</label>&nbsp&nbsp No</td>
                                            @else
                                                <td><label>Unique Links:</label>&nbsp&nbsp Yes</td>
                                            @endif
                                            @if($archive['unique_ids_file']==null)
                                                <td><label>Unique File Attached:</label>&nbsp&nbsp No</td>
                                            @else
                                                <td><label>Unique File Attached:</label>&nbsp&nbsp Yes</td>
                                            @endif
                                            <td><label>Start Date:</label>&nbsp&nbsp {{date('d-m-Y',strtotime($archive['start_date']))}}</td>
                                            <td><label>End Date:</label>&nbsp&nbsp{{date('d-m-Y',strtotime($archive['end_date']))}}</td>
                                        </tr>
                                        <tr>
                                            <td><label>Project Manager:</label>&nbsp&nbsp {{$archive['created_by']}}</td>
                                            <td><label>Project Quota:</label>&nbsp&nbsp {{$archive['quota']}}</td>
                                            <td><label>Project CPI:</label>&nbsp&nbsp {{$archive['cpi']}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <strong>Quota Selection</strong>
                            <span class="float-right"><a href="#">Modify</a></span>
                        </div>
                        <div class="card-body">
                            @foreach($archive['project_quota'] as $quota)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Quota Name</th>
                                        <th>Count</th>
                                        <th>CPI</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{$quota['name']}}</td>
                                        <td>{{$quota['count']}}</td>
                                        <td>{{$quota['cpi']}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Profile Type</th>
                                                    <th>Attributes Names</th>
                                                    <th>Selected Options</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php $quota_specs = json_decode($quota['formatted_quota_spec'], true);
                                                @endphp
                                                @foreach($quota_specs as $profile_type => $specs)
                                                    @foreach($specs as $question => $sel_answers)
                                                        @php
                                                            $selectedAnswers = collect($sel_answers);
                                                            $currentAnswer = $selectedAnswers->flatten()->all();
                                                            if (( $key = array_search('status', $currentAnswer)) !== false) {
                                                                unset($currentAnswer[$key]);
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                {{strtoupper($profile_type)}}
                                                            </td>
                                                            <td>{{$question}}</td>
                                                            <td>@if($question=="GLOBAL_ZIP")@php $var=nl2br(trim($currentAnswer[1]));
                                                $zip_data = explode('<br />',$var);
                                                                @endphp
                                                                {{implode(',',$zip_data)}}
                                                                @else{{implode(',', $currentAnswer)}}@endif</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <strong>Security And Screener</strong>
                            <span class="float-right">
                                <a href="#">Modify</a>
                            </span>
                        </div>
                        <div class="card-body">
                            <table class="col-sm-12">
                                <tr>
                                    <td> <label>Loi Validation:</label>&nbsp{!! (!empty($archive['loi_validation']))? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>&nbsp;&nbsp' !!}</td>
                                    <td> <label>Loi Validation Time:</label>&nbsp{!! (!empty($archive['loi_validation_time']))? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>&nbsp;&nbsp' !!}</td>
                                    <td> <label>Redirect Flag:</label>&nbsp{!! (!empty($clients['redirector_flag']))? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>&nbsp;&nbsp' !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <strong>Source Quota Assignment</strong>
                            <span class="float-right">
                                <a href="">Modify</a>
                            </span>
                        </div>
                        <div class="card-body">
                            <table id="source_quota" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Sources</th>
                                    <th>CPI</th>
                                    <th>Quota</th>
                                    <th>Screener(Global,Defined,Custom)</th>
                                    <th>Quota Selection</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($archive['project_vendors'] as $vendor)
                                    <tr>
                                        <td>{{$vendor['source']['name']}}</td>
                                        <td>{{$vendor['cpi']}}</td>
                                        <td>{{$vendor['quota']}}</td>
                                        <td>
                                        <span>
                                       {!! (!empty($vendor['global_screener']))? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>&nbsp;&nbsp' !!}
                                            {!! (!empty($vendor['predefined_screener']))? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>&nbsp;&nbsp' !!}
                                            {!! (!empty($vendor['custom_screener']))? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>&nbsp;&nbsp' !!}
                                        </span>
                                        </td>
                                        @if($vendor['spec_quota_ids']==0)
                                            <td>
                                                All
                                            </td>
                                        @else
                                            <td>
                                                {{$vendor['spec_quota_names']}}
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- row -->
                <div class="card-footer text-center">
                    <button class="btn btn-lg btn-primary pull-right"  style="width:20em" type="button">
                         Clone
                    </button>
                </div>
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->


    @push('after-styles')

    @endpush
    @push('after-scripts')
        <!-- Toastr style -->
        <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
    @endpush
    @push('after-styles')
        <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
    @endpush

    @push('after-styles')
        <!-- Latest compiled and minified CSS -->
        <style>
            .card_panel_header{
                background-color: #1c84c6;
                border-color: #1c84c6;
                color: white;
            }

            .source-list-result > .content > .row {
                padding: 8px 0px 0px 0;
            }

            .source-list-result > .content > .row:hover {
                background: #f5f5f5;
                box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
                transition: 0.3s;
            }
        </style>

        {{--Todo: Add in own Asset--}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    @endpush
@endsection
