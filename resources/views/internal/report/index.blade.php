@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('REPORTS')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div id="filter-panel" class="collapse filter-panel">
                        <div class="panel panel-default">
                            <div class="card card-body">
                                {{ html()->form('POST', route('internal.report.filter.show'))->id('project_filter_form')->open() }}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="project_status">Status</label>
                                        <select multiple="multiple" data-live-search="true" type="select" id="project_status" class="form-control" name="status[]" title="Status">
                                            @foreach($status as $status_detail)
                                                <option value= "{{$status_detail->id}}" @if ( !empty($status_filter) && in_array($status_detail->id,$status_filter)) selected="selected" @endif>{{$status_detail->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="country">Country</label>
                                        <select multiple="multiple" data-live-search="true" type="select" id="country" class="form-control"  name="country[]" title="Country">
                                            @foreach($countries as $country)
                                                <option value= "{{$country->id}}" @if( !empty($country_filter) && in_array($country->id,$country_filter)) selected="selected" @endif>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="project_manager">Project Manager</label>
                                        <select multiple="multiple" data-live-search="true" type="select" id="project_manager" class="form-control" name="project_manager[]" title="Project Manager">
                                            @foreach($project_manager as $user_detail)
                                                <option value= "{{$user_detail->id}}" @if ( !empty($project_manager_filter) && in_array($user_detail->id,$project_manager_filter)) selected="selected" @endif>{{$user_detail->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="study">Study Type</label>
                                        <select data-live-search="true" type="select" id="study"  class="form-control" multiple="multiple"  name="study_type[]" title="Study Type">
                                            @foreach($study_type as $type)
                                                <option value= "{{$type->id}}" @if ( !empty($study_filter) && in_array($type->id,$study_filter)) selected="selected" @endif>{{$type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="client">Client</label>
                                        <select data-live-search="true" type="select" id="client" class="form-control" multiple="multiple"  name="client[]" title="Client">
                                            @foreach($clients as $client)
                                                <option value= "{{$client->id}}" @if ( !empty($client_filter) && in_array($client->id,$client_filter)) selected="selected" @endif>{{$client->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        {!! Form::text('from_date', 'From Date:')->attrs(['data-toggle' => 'datetimepicker', 'data-target'=> '#from_date']) !!}
                                        {!! Form::text('to_date', 'To Date:')->attrs(['data-toggle' => 'datetimepicker', 'data-target'=> '#to_date']) !!}
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="project_name">Project Name</label>
                                        <input type="text" class="form-control" size="10" id="project_name" name="char" @if ( !empty($char_filter)) value="{{$char_filter}}" @endif placeholder="Consumer..."><br>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="active_archive">Project State</label>
                                        <select data-live-search="true" type="select" id="active_archive" class="form-control" multiple="multiple"  name="active_archive[]">
                                            @if($archive_filter)
                                                @foreach($archive_filter as $archive_active)
                                                    <option @if($archive_active=='active') selected @endif value= "active" >Active</option>
                                                    <option @if($archive_active=='archive') selected @endif  value= "archive">Archive</option>
                                                @endforeach
                                            @else
                                                <option value= "active" selected>Active</option>
                                                <option value= "archive">Archive</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="project_code">Project Code</label>
                                        <input type="text" class="form-control" size="10" id="project_code" name="project_char" @if ( !empty($project_char_filter)) value="{{$project_char_filter}}" @endif placeholder="180005USEN">
                                    </div>

                                </div>
                                <hr/>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <button type="submit"  class="btn btn-sm btn-primary filter_apply_btn" role="button">
                                            <span class="material-icons">filter_alt</span> Apply Filter
                                        </button>
                                    </div>
                                    @if(!empty($input))
                                        <div class="form-group col-md-4">
                                            <a href="{{route('internal.report.index')}}" class="btn btn-sm btn-danger" role="button">
                                                <span class="material-icons">block</span> Clear Filters
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                    </div>
                    {{html()->form('post',route('internal.report.export'))->open()}}
                    <div class="row">
                        <div class="col-6 pull-left">
                            <button type="button" class="btn btn-primary report_filter_btn" data-toggle="collapse" data-target="#filter-panel">
                                <span class="material-icons">settings</span> Advanced Search
                            </button>
                        </div>
                        <div class="col-6 pull-right">
                            <button type="submit" class="btn btn-sm btn-primary float-right" role="button">
                                <span class="material-icons">import_export</span>Export Data
                            </button>
                        </div>
                    </div>
                    @if($filterable)
                    <div class="row">
                        <table id="clients-show" class="table table-striped table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all" />
                                </th>
                                <th>Name</th>
                                <th>Survey-ID</th>
                                <th>CI Code</th>
                                <th>PM</th>
                                <th>ST</th>
                                <th>CMP</th>
                                <th>TE</th>
                                <th>QTE</th>
                                <th>QF</th>
                                <th>AB</th>
                                <th>AB%</th>
                                <th>IR%</th>
                                <th>CCR%</th>
                                <th>LOI</th>
                                <th>CPI</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty(!$archive_data->isEmpty()))
                                @foreach($archive_data as $archive)
                                    @php
                                        if(!empty($archive->traffics)){
                                        $json_encode = json_encode($archive->traffics);
                                            $get_traffic = json_decode($json_encode,true);
                                            $duration = array_column($get_traffic,'duration');
                                            if(count($duration)>0){
                                              $avg_loi = (array_sum($duration)/count($duration));
                                            } else{
                                            $avg_loi = 0;
                                            }
                                        } else{
                                            $avg_loi = 0;
                                        }
                                    @endphp
                                    <tr id="tr_{{$archive->project_id}}" class="project_item" data-project_id="{{$archive->project_id}}">
                                        <td class="datatable_checkbox select-checkbox" data-project_id="{{$archive->project_id}}" ><input type="checkbox" class="checkboxes" name="archive_id[]" value="{{$archive->project_id}}"></td>
                                        <td><span class="badge badge-danger float-right">Archive</span>{{$archive->name}}</td>
                                        <td>{{$archive->code}}</td>
                                        <td>{{$archive->client_code}}</td>
                                        <td>{{$archive->created_by}}</td>
                                        <td>@if($archive->starts){{$archive->starts}}@else 0 @endif</td>
                                        <td>@if($archive->completes){{$archive->completes}}@else 0 @endif</td>
                                        <td>@if($archive->terminates){{$archive->terminates}}@else 0 @endif</td>
                                        <td>@if($archive->quality_terminate){{$archive->quality_terminate}}@else 0 @endif</td>
                                        <td>@if($archive->quotafull){{$archive->quotafull}}@else 0 @endif</td>
                                        <td>@if($archive->abandons){{$archive->abandons}}@else 0 @endif</td>
                                        <td>
                                            @if($archive->starts)
                                                @php
                                                    try{
                                                        $ab = (($archive->abandons/$archive->starts) * 100);

                                                    }catch(Exception $exception){
                                                        $ab = 0;
                                                    }
                                                @endphp
                                                {{round($ab)}}%
                                            @else
                                                0%
                                            @endif
                                        </td>
                                        <td>{{$archive->ir}}</td>
                                        <td>
                                            @if($archive->starts)
                                                @php
                                                    try{
                                                        $ccr = (($archive->completes/$archive->starts) * 100);

                                                    }catch(Exception $exception){
                                                        $ccr = 0;
                                                    }
                                                @endphp
                                                {{round($ccr)}}%
                                            @else
                                                0%
                                            @endif
                                        </td>
                                        <td>{{round($avg_loi)}}</td>
                                        <td>{{$archive->cpi}}</td>
                                        <td>Archived</td>
                                        <td>@include('internal.report.includes.include_action', ['item' => $archive, 'state' => 'A'])</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if(!$projects->isEmpty())
                            @foreach($projects as $project)
                                <tr id="tr_{{$project->id}}" class="project_item" data-project_id="{{$project->id}}">
                                    <td class="datatable_checkbox select-checkbox" data-project_id="{{$project->id}}" ><input type="checkbox" class="checkboxes" name="project_id[]" value="{{$project->id}}"></td>
                                <td><span class="badge badge-success float-right">Live</span>{{$project->name}}</td>
                                <td>{{$project->code}}</td>
                                <td>{{$project->client_code}}</td>
                                <td>{{$project->user->first_name}}</td>
                                    <td>@if($project->traffic){{$project->traffic->starts}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->completes}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->terminates}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->quality_terminate}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->quotafull}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->abandons}}@else 0 @endif</td>
                                    <td>
                                        @if( $project->traffic && isset($project->traffic->starts) )
                                            @php
                                                try{
                                                    $ab = (($project->traffic->abandons/$project->traffic->starts) * 100);
                                                }catch(Exception $exception){
                                                    $ab = 0;
                                                }
                                            @endphp
                                            {{round($ab)}}%
                                        @else
                                            0%
                                        @endif
                                    </td>
                                    <td>{{$project->ir}}</td>
                                <td> @if($project->traffic)
                                        @php
                                            try{
                                                $ccr = (($project->traffic->completes/$project->traffic->start) * 100);
                                            }catch(Exception $exception){
                                                $ccr = 0;
                                            }
                                        @endphp
                                        {{round($ccr)}}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td>{{($project->traffic)?round($project->traffic->loi):0}}</td>
                                <td>{{$project->cpi}}</td>
                                <td>{{$project->status_label}}</td>
                                <td>@include('internal.report.includes.include_action', ['item' => $project, 'state' => 'P'])</td>
                            </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{--{!! $projects->render() !!}--}}
                    </div><!-- row -->
                    {{html()->form()->close()}}
                        @else
                        @include('internal.report.includes.blank_table')
                    @endif
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
@endsection

@push('after-styles')
    <style>

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endpush
@push('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.23.0/moment.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
        $(document).ready(function(e) {

            $("#select-all").click(function (e) {
                $(".checkboxes").prop('checked', $(this).prop('checked'));
            });

            $select2Opttions = {
                width: '100%',
                allowClear: false,
                height: '100%'
            }

            $('#project_status').select2($select2Opttions);
            $('#country').select2($select2Opttions);
            $('#project_manager').select2($select2Opttions);

            $('#study').select2($select2Opttions);
            $('#client').select2($select2Opttions);
            $('#active_archive').select2($select2Opttions);

            $(document).find('.report_filter_btn').on('click', function (e) {


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
                }
            });
            var date = new Date();
            var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            $('#from_date').datetimepicker({
                format: 'YYYY-MM-DD hh:mm:ss'
            });
            $('#to_date').datetimepicker({
                format: 'YYYY-MM-DD hh:mm:ss',
                showClose: true
            });

        });
    </script>
    @endpush

