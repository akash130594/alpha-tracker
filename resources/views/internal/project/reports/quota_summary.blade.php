@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('Quota Summary') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Project Report')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="float-right">
                        <form name="traffic_summary_type" onchange="location = this.value;">
                            <div class="form-group">
                                <label for="traffic_summry_select">Summary Type</label>
                                <select class="form-control" id="traffic_summry_select">
                                    <option value="{{route('internal.project.report.summary.show', [$project->id])}}" @if(strpos(Route::currentRouteName(), 'report.summary.show') !== false ) selected="selected"  @endif>
                                        Source Traffic Summary
                                    </option>
                                    <option value="{{route('internal.project.report.quota.summary.show', [$project->id])}}" @if(strpos(Route::currentRouteName(), 'report.quota.summary.show') !== false ) selected="selected"  @endif>
                                        Quota Traffic Summary
                                    </option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <table class="table table-hover table-sm">
                        <thead>
                        <tr>
                            <th class="collapsible-th" colspan="3"></th>
                            <th class="collapsible-th">ST</th>
                            <th class="collapsible-th">CMP</th>
                            <th class="collapsible-th">TE</th>
                            <th class="collapsible-th">QF</th>
                            <th class="collapsible-th">QTE</th>
                            <th class="collapsible-th">AB</th>
                            <th class="collapsible-th">AB%</th>
                            <th class="collapsible-th">IR%</th>
                            <th class="collapsible-th">CCR%</th>
                            <th class="collapsible-th">CPI</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        <tr>
                            <th class="collapsible-th" colspan="3">
                                <label for="accounting">Summary</label>
                            </th>
                            <th class="collapsible-th">@if($project->traffics->starts){{$project->traffics->starts}}@else 0 @endif </th>
                            <th class="collapsible-th">@if($project->traffics->starts) {{$project->traffics->completes}} @else 0 @endif</th>
                            <th class="collapsible-th">@if($project->traffics->starts) {{$project->traffics->terminates}} @else 0 @endif</th>
                            <th class="collapsible-th"> @if($project->traffics->starts) {{$project->traffics->quotafull}}@else 0 @endif </th>
                            <th class="collapsible-th">@if($project->traffics->starts) {{$project->traffics->quality_terminate}} @else 0 @endif </th>
                            <th class="collapsible-th">@if($project->traffics->starts) {{$project->traffics->abandons}} @else 0 @endif</th>
                            <th class="collapsible-th">
                                @php
                                    try{
                                        $ab = (($project->traffics->abandons/$project->traffics->starts) * 100);
                                    }catch(Exception $exception){
                                        $ab = 0;
                                    }
                                @endphp
                                {{round($ab)}}%
                            </th>
                            <th class="collapsible-th">{{$project->ir}}</th>
                            <th class="collapsible-th">
                                @php
                                    try{
                                        $ccr = (($project->traffics->completes/$project->traffics->starts) * 100);
                                    }catch(Exception $exception){
                                        $ccr = 0;
                                    }
                                @endphp
                                {{round($ccr)}}%
                            <th class="collapsible-th">{{$project->cpi}}</th>
                        </tr>
                        </tbody>
                        @foreach($project_quota as $quota)
                            <tbody class="collapsible-labels">
                            <tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-{{$quota->id}}" aria-expanded="false" aria-controls="group-of-rows-{{$quota->id}}">
                                <td colspan="3">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    {{$quota->name}}
                                </td>
                                <td class="collapsible-td">{{$quota->traffics->starts}}</td>
                                <td class="collapsible-td">{{$quota->traffics->completes}}</td>
                                <td class="collapsible-td">{{$quota->traffics->terminates}}</td>
                                <td class="collapsible-td">{{$quota->traffics->quotafull}}</td>
                                <td class="collapsible-td">{{$quota->traffics->quality_terminate}}</td>
                                <td class="collapsible-td">{{$quota->traffics->abandons}}</td>
                                <td class="collapsible-td">
                                    @php
                                        try{
                                            $ab = (($project->traffics->abandons/$project->traffics->starts) * 100);
                                        }catch(Exception $exception){
                                            $ab = 0;
                                        }
                                    @endphp
                                    {{round($ab)}}%
                                </td>
                                <td class="collapsible-td">
                                    {{$project->ir}}
                                </td>
                                <td class="collapsible-td">
                                    @php
                                        try{
                                            $ccr = (($quota->traffics->completes/$quota->traffics->starts) * 100);
                                        }catch(Exception $exception){
                                            $ccr = 0;
                                        }
                                    @endphp
                                    {{round($ccr)}}%
                                </td>
                                <td class="collapsible-td">{{$quota->cpi}}</td>
                            </tr>
                            </tbody>

                            <tbody id="group-of-rows-{{$quota->id}}" class="collapse">
                            @foreach($quota->vendors as $vendor)
                                <tr>
                                    <td colspan="3">{{$vendor->source->name}}</td>
                                    <td class="collapsible-td">
                                        {{$vendor->traffic->starts}}
                                    </td>
                                    <td class="collapsible-td">
                                        {{$vendor->traffic->completes}}
                                    </td>
                                    <td class="collapsible-td">
                                        {{$vendor->traffic->terminates}}
                                    </td>
                                    <td class="collapsible-td">
                                        {{$vendor->traffic->quotafull}}
                                    </td>
                                    <td class="collapsible-td">
                                        {{$vendor->traffic->quality_terminate}}
                                    </td>
                                    <td class="collapsible-td">
                                        {{$vendor->traffic->abandons}}
                                    </td>
                                    <td class="collapsible-td">
                                        @if( !empty($vendor->traffic->abandons) )
                                            @php
                                                try{
                                                    $ab_pc = round(($vendor->traffic->abandons/$vendor->traffic->starts) * 100);
                                                }catch(Exception $exception){
                                                    $ab_pc = 0;
                                                }
                                            @endphp
                                            {{$ab_pc}}%
                                        @else
                                            0%
                                        @endif
                                    </td>
                                    <td class="collapsible-td">
                                        {{$project->ir}}
                                    </td>
                                    <td class="collapsible-td">
                                        @php
                                            try{
                                                $ccr = (($vendor->traffic->completes/$vendor->traffic->starts) * 100);
                                            }catch(Exception $exception){
                                                $ccr = 0;
                                            }
                                        @endphp
                                        {{round($ccr)}}%
                                    </td>
                                    <td class="collapsible-td">
                                        {{$vendor->cpi}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        @endforeach
                    </table>
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
        .collapsible-table.table {
            border-collapse: collapse;
            margin:50px auto;
        }
        th.collapsible-th {
            background: #3498db;
            color: white;
            font-weight: bold;
        }
        td.collapsible-td, th.collapsible-th {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 18px;
        }
        .collapsible-labels tr td {
            background-color: #2cc16a;
            font-weight: bold;
            color: #fff;
        }
        .collapsible-labels tr td label {
            display: block;
        }
    </style>
@endpush

@push('after-scripts')
    <script>
        $("#traffic_summry_select").click(function() {
            var open = $(this).data("isopen");
            if(open) {
                window.location.href = $(this).val()
            }
            //set isopen to opposite so next time when use clicked select box
            //it wont trigger this event
            $(this).data("isopen", !open);
        });
    </script>
@endpush
