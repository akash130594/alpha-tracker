@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Projects')
                    </strong>
                    <div class="float-right">
                        <a href="{{route('internal.project.create.show')}}" class="btn btn-primary">Create Project</a>
                    </div>
                </div><!--card-header-->



                <div class="card-body">
                        <div id="filter-panel" class="collapse filter-panel">
                            <div class="panel panel-default">
                            {{ html()->form('GET', route('internal.project.filter.show'))->id('project_filter_form')->open() }}
                            <div class="row">
                                <div class="col-md-12 px-5 py-3">
                                    <div class="form-group row">
                                        <label class="col-sm-1 col-form-label">Filter</label>
                                        <div class="col-sm-11">
                                            <select class="form-control form-control-lg status filterable-status" multiple="multiple" name="status[]"  >
                                                <optgroup label="Status">
                                                    @foreach($status as $status_detail)
                                                        <option value= "status.{{$status_detail->id}}" @if ( !empty($status_filter) && in_array($status_detail->id,$status_filter)) selected="selected" @endif>{{$status_detail->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="Country">
                                                    @foreach($countries as $country)
                                                        <option value= "country.{{$country->country_code}}" @if( !empty($country_filter) && in_array($country->country_code,$country_filter)) selected="selected" @endif>{{$country->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="Project Manager">
                                                    @foreach($project_manager as $user_detail)
                                                        <option value= "project_manager.{{$user_detail->id}}" @if ( !empty($project_manager_filter) && in_array($user_detail->id,$project_manager_filter)) selected="selected" @endif>{{$user_detail->name}}</option>
                                                        {{--<option value="{{$user_detail->id}}">{{$user_detail->name}}</option>--}}
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="Study Type">
                                                    @foreach($study_type as $type)
                                                        <option value= "study_type.{{$type->id}}" @if ( !empty($study_filter) && in_array($type->id,$study_filter)) selected="selected" @endif>{{$type->name}}</option>
                                                    @endforeach
                                                </optgroup>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label"></label>

                                    <button type="button"  class="btn btn-sm btn-primary filter_apply_btn" role="button">
                                        <span class="material-icons">task_alt</span> Apply Filter
                                    </button>
                                    @if(!empty($input))
                                        <a href="{{route('internal.project.index')}}" class="btn btn-sm btn-danger" role="button">
                                            <i class="fas fa-ban"></i> Clear Filters
                                        </a>
                                    @endif
                                </div>
                            </div>
                            {{ html()->form()->close() }}
                            </div>
                        </div>
                {{ html()->form('POST', route('internal.project.update.selected'))->open() }}
                    <div class="row">
                        <div class="col-4 pull-right">
                            <button type="button" class="btn btn-primary report_filter_btn" data-toggle="collapse" data-target="#filter-panel">
                                <span class="material-icons">search</span> Advanced Search
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 pull-left">
                            {!! Form::select('status', 'Change Status', $status->pluck('name','id')->toArray()) !!}
                            <input type="submit" class="btn btn-sm btn-info" value="Apply" name="submit">
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target=".demo-popup" >Export</button>
                        </div>
                    </div>
                <!-- popup box modal starts here -->
                    <div class="modal fade demo-popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                        <div class="col-lg-10 pull-left">
                                            <label class="custom-label pull-left"><h4>Bulk Export</h4></label>&nbsp;&nbsp;
                                        </div>
                                        <div class="col-lg-2 pull-right">
                                            <button type="button" class="form-control close" data-dismiss="modal" aria-hidden="true">x</button> <h3 class="modal-title"></h3>
                                        </div>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                            <div class="col-lg-8">
                                                <label class="custom-label"><strong>All Columns</strong></label>&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="select_column" value="all_column"  class="column_selector pull-left"><br/>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <label class="custom-label"><strong>Custom Columns</strong></label>&nbsp;&nbsp;
                                            <input type="radio" name="select_column" value="custom_column" class="column_selector"><br/>
                                        </div>
                                        <div class="col-lg-12">
                                            <select class="form-control status custom-column-select" multiple="multiple" disabled="disabled" name="column[]" style="display: none;">
                                                <optgroup label="Columns Selection">
                                                    <option value="id" >Respid</option>
                                                    <option value="status_name" >Status</option>
                                                    <option value="mode" >Mode</option>
                                                    <option value="resp_status">Resp Status</option>
                                                    <option value="project_code">Project Code</option>
                                                    <option value="vvars">VVAR</option>
                                                    <option value="started_at">Started</option>
                                                    <option value="ended_at">Ended</option>
                                                    <option value="duration">Duration(mins)</option>
                                                    <option value="source_type_name">Source Type</option>
                                                    <option value="source_code">Source Code</option>
                                                    <option value="source_name">Source</option>
                                                    <option value="survey_id">Survey ID</option>
                                                    <option value="source_name">Survey Name</option>
                                                    <option value="country_name">Survey Country</option>
                                                    <option value="client_name">Client Name</option>
                                                    <option value="client_link">Client Link</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12" align="center">
                                            <label class="custom-label"><strong>Check For Export in ZIP</strong></label>&nbsp;&nbsp;<br>
                                            <input type="checkbox" name="zip" value="zip">
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-info" name="export_data" value="Export">
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal-->
                    <br>
                        <div class="row table_container">
                            <table id="projects-show" class="table table-striped table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Survey-ID</th>
                                <th>CI Code</th>
                                <th class="custom_pm_head">PM</th>
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
                            @foreach($projects as $project)
                                <tr id="tr_{{$project->id}}" class="project_item" data-project_id="{{$project->id}}">
                                    <td class="datatable_checkbox select-checkbox" data-project_id="{{$project->id}}" ></td>
                                    {{--<td><input type="checkbox" class="sub_chk" value="{{$project->id}}" name="id[]"></td>--}}
                                    <td>{{$project->name}}</td>
                                    <td>{{$project->code}}</td>
                                    <td>{{$project->client_code}}</td>
                                    <td>{{getNameInitials($project->user->first_name.' '.$project->user->last_name)}}</td>
                                    <td>@if($project->traffic){{$project->traffic->starts}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->completes}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->terminates}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->quality_terminate}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->quotafull}}@else 0 @endif</td>
                                    <td>@if($project->traffic){{$project->traffic->abandons}}@else 0 @endif</td>
                                    <td>
                                        @if( !empty($project->traffic) )
                                            @php
                                                try{
                                                    $ab_pc = round(($project->traffic->abandons/$project->traffic->starts) * 100);
                                                }catch(Exception $exception){
                                                    $ab_pc = 0;
                                                }
                                            @endphp
                                            {{$ab_pc}}%
                                        @else
                                            0%
                                        @endif
                                    </td>
                                    <td>
                                        {{$project->ir}}%
                                    </td>
                                    <td>
                                        @if($project->traffic)
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
                                    <td>{{(!empty($project->traffic))?round($project->traffic->loi):0}}</td>
                                    <td>{{$project->cpi}}</td>
                                    <td>{{$project->status_label}}</td>
                                    <td>@include('internal.project.includes.index_action')</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- row -->
                    {{ html()->form()->close() }}
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->


    <div class="modal change_status_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{html()->form('POST',route('internal.project.change.status'))->open()}}
                <div class="modal-body">

                    <span>Your Current Status is : <span id="current_status_span"></span></span>

                    {!! Form::select('project_status', 'Change to', []) !!}
                    <input type="hidden" name="project_id" id="change_status_project_id" value="">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>

                </div>
                {{html()->form()->close()}}
            </div>
        </div>
    </div>
    {{------------------View Link Modal---------------------------------------------------------}}

    <div class="modal fade in" tabindex="-1" role="dialog" id="linkModal" aria-labelledby="myLargeModalLabel-1" aria-hidden="true">
        <div class="modal-dialog modal-lg link_modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button> <h3 class="modal-title"></h3>
                </div>
                <div class="question_loader" style="display:none;">
                    <div class="sk-spinner sk-spinner-wave">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>
                </div>
                <div class="modal-body view_link_vendor">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal-->

    {{------------------View Endpages Modal---------------------------------------------------------}}

    <div class="modal fade in" tabindex="-1" role="dialog" id="endpagesModal" aria-labelledby="myLargeModalLabel-2" aria-hidden="true">
        <div class="modal-dialog modal-lg link_modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h3 class="modal-title"></h3>
                </div>
                <div class="endpage_question_loader" style="display:none;">
                    <div class="sk-spinner sk-spinner-wave">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>
                </div>
                <div class="modal-body view_endpages_area">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal-->

@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
    <link rel="stylesheet" href="{{ asset('vendors/jquery-multiselect/jquery.multiselect.css') }}" >
    <style>
        .sk-spinner-wave.sk-spinner {
            margin: 0 auto;
            width: 50px;
            height: 30px;
            text-align: center;
            font-size: 10px;
        }
        .sk-spinner-wave div {
            background-color: #1ab394;
            height: 100%;
            width: 6px;
            display: inline-block;
            -webkit-animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
            animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
        }
        .sk-spinner-wave .sk-rect2 {
            -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s;
        }
        .sk-spinner-wave .sk-rect3 {
            -webkit-animation-delay: -1s;
            animation-delay: -1s;
        }
        .sk-spinner-wave .sk-rect4 {
            -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s;
        }
        .sk-spinner-wave .sk-rect5 {
            -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s;
        }
        @-webkit-keyframes sk-waveStretchDelay {
            0%,
            40%,
            100% {
                -webkit-transform: scaleY(0.4);
                transform: scaleY(0.4);
            }
            20% {
                -webkit-transform: scaleY(1);
                transform: scaleY(1);
            }
        }
        @keyframes  sk-waveStretchDelay {
            0%,
            40%,
            100% {
                -webkit-transform: scaleY(0.4);
                transform: scaleY(0.4);
            }
            20% {
                -webkit-transform: scaleY(1);
                transform: scaleY(1);
            }
        }
        /*#projects-show th, #projects-show td {
            word-break: break-all; !* IE supports this *!
            word-break: break-word; !* IE doesn't support, and will ignore *!
            !* http://caniuse.com/#feat=word-break *!
        }*/
    </style>
@endpush

@push('after-scripts')

    {{-- For DataTables --}}
    <script src="{{ asset('vendors/jquery-are-you-sure/jquery.are-you-sure.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

    <script type="text/javascript" src="{{ mix('js/dataTable.js') }}"></script>
    <script>
        var getInitials = function (string) {
            var names = string.split(' '),
                initials = names[0].substring(0, 1).toUpperCase();

            if (names.length > 1) {
                initials += names[names.length - 1].substring(0, 1).toUpperCase();
            }
            return initials;
        };

        $(document).ready(function() {
            new ClipboardJS('.btn-copy');
            let datatable = $('#projects-show')
                .on('preXhr.dt', function ( e, settings, data ) {
                    data.filter = $('.filterable-status').val()
                } )
                .DataTable({
                    serverSide: true,
                    stateSave: true,
                    destroy: true,
                    'processing': true,
                    ajax: {
                        "url": "{{ route('internal.project.datatable') }}",
                    },
                    columns: [
                        {
                            name: 'id',
                            'orderable': false,
                            searchable: false
                        },
                        { name: 'name' },
                        { name: 'code' },
                        { name: 'client_code' },
                        { name: 'user.name', orderable: false },
                        { name: 'starts', orderable: false, searchable: false },
                        { name: 'completes', orderable: false, searchable: false },
                        { name: 'terminates', orderable: false, searchable: false },
                        { name: 'qualityterminate', orderable: false, searchable: false},
                        { name: 'quotafulls', orderable: false, searchable: false },
                        { name: 'abandons', orderable: false, searchable: false },
                        { name: 'abandon_percentage', orderable: false, searchable: false},
                        { name: 'ir', orderable: false, searchable: false },
                        { name: 'ccr', orderable: false, searchable: false },
                        { name: 'loi', orderable: false, searchable: false },
                        { name: 'cpi', orderable: false, searchable: false },
                        { name: 'status_label' },
                        { name: 'action', orderable: false, searchable: false }
                    ],
                    'columnDefs': [{
                        'targets': 0,
                        'orderable': false,
                        'searchable': false,
                        'className': 'dt-body-center',
                        "title": "<input type='checkbox' class='select-checkbox' name='select-all' id='select-all'/>",
                        'render': function (data, type, full, meta){
                            return '<input type="checkbox" class="project_checkbox select-checkbox" name="id[]" value="' + data + '">';
                        }
                    },
                    {
                        /*PM Name Column*/
                        'targets': 4,
                        'orderable': false,
                        'searchable': false,
                        'render': function (data, type, full, meta){
                            return getInitials(data);
                        }
                    },
                    {
                        /*AB% Column*/
                        'targets': 11,
                        'orderable': false,
                        'searchable': false,
                        'render': function (data, type, full, meta){
                            try {
                                $starts = notZero(full[5]);
                                $rounded = Math.round( (full[10] / $starts) * 100 ) ;
                                return $rounded + '%';
                            } catch ($e){
                                return '0%';
                            }
                        }
                    },
                    {
                        /*CCR% Column*/
                        'targets': 13,
                        'orderable': false,
                        'searchable': false,
                        'render': function (data, type, full, meta){
                            try {
                                $starts = notZero(full[5]);
                                $rounded = Math.round( (full[6] / $starts) * 100 ) ;
                                return $rounded+'%';
                            } catch ($e){
                                return '0%';
                            }
                        }
                    }],
                });
            $(".dataTables_wrapper").css("width","100%");
            $(document).on('change', '#select-all', function(event) {
                $table_body = $(document).find('#projects-show > tbody');
                if($(this).is(":checked")) {
                    $table_body.find('input.project_checkbox').each(function(key, element) {
                        $(element).prop('checked', true);
                    });
                } else {
                    $table_body.find('input.project_checkbox').each(function(key, element) {
                        $(element).prop('checked', false);
                    });
                }
            });

            $('.filter_apply_btn').on('click', function(e) {
                $('#projects-show').DataTable().ajax.reload();
            });

        } );

        function notZero(n) {
            n = +n;  // Coerce to number.
            if (!n) {  // Matches +0, -0, NaN
                throw new Error('Invalid dividend ' + n);
            }
            return n;
        }
    </script>
@endpush

@push('after-scripts')

    <script src="{{asset('vendors/jquery-multiselect/jquery.multiselect.js')}}"></script>

    <script>
            /*$('div#collapse').contents().unwrap().wrap('<span/>');*/
            $(document).on('click',"#view_link",function (e) {
            var project_id = $(this).attr('data-project_id');
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }

            if(!project_id || project_id === 0){
                return false;
            }
            jQuery('.question_loader').show();
            $('.view_link_vendor').html('');
            axios.get("{{ route('internal.project.view_link') }}", {
                params: {
                    project_id: project_id
                }
            }).then(function (response) {
                if( response.status === 200 ){
                    var $html = response.data;
                    $('.view_link_vendor').html($html);
                }
            }).catch(function (error) {
                alert('error occured');
                console.log(error);
            }).then(function () {
                jQuery('.question_loader').hide();
            });

            $('#linkModal').modal('toggle');
        });

            $(document).on('click',"#view_endpages",function (e) {
                var project_id = $(this).attr('data-project_id');
                var headers = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }

                if(!project_id || project_id === 0){
                    return false;
                }
                jQuery('.endpage_question_loader').show();
                $('.view_endpages_area').html('');
                axios.get("{{ route('internal.project.view_endpage_links') }}", {
                    params: {
                        project_id: project_id
                    }
                }).then(function (response) {
                    if( response.status === 200 ){
                        var $html = response.data;
                        $('.view_endpages_area').html($html);
                    }
                }).catch(function (error) {
                    alert('error occured');
                    console.log(error);
                }).then(function () {
                    jQuery('.endpage_question_loader').hide();
                });

                $('#endpagesModal').modal('toggle');
            });

        $(document).on('click',".project_change_status", function (e) {
            var project_id = $(this).attr('data-project_id');
            var project_status_id = $(this).attr('data-project_status_id');
            var project_status_label = $(this).attr('data-project_status_label');
            $('#change_status_project_id').val(project_id);
            $select = $('#project_status');
            $select.find('option').remove();
            console.log(project_status_id);
            getNextChangeableStatus(project_id, project_status_id);

            $('#current_status_span').html(project_status_label);
            $('.change_status_modal').modal('toggle');
        });

        function getNextChangeableStatus(project_id, project_status_id)
        {
            var $project_id = project_id;
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }

            if(!$project_id || $project_id === 0){
                return false;
            }
            // Make a request for a user with a given ID
            axios.get("{{ route('internal.project.status.fetchflow') }}", {
                params: {
                    project_id: $project_id,
                    project_status: project_status_id,
                }
            }).then(function (response) {
                // handle success
                if(response.status === 200){
                    $select = $('#project_status');
                    var statuses = response.data;
                    $.each(statuses, function(value, name) {
                        $select.append($('<option />', {value: value, text: name}));
                    });
                    //$('#dynamic_response_div').html(result);
                }

            }).catch(function (error) {
                // handle error
                //$('#dynamic_response_div').html('Some Error Occurred');
                console.log('Error Occured');
            }).then(function () {
                // always executed
                console.log('always executed');
            });
        }

        $(".column_selector").on('change', function(e){
            var $value = $(this).val();
            if ($value === 'custom_column') {
                console.log('init');
                $('.custom-column-select').removeAttr('disabled').show();
                /*$('.custom-column-select').multiselect({
                    columns: 4,
                    search: false,
                    selectGroup: false,

                });*/
            } else {
                console.log('destroy');
                /*$('.custom-column-select').multiselect('disable', true);
                $('.custom-column-select').multiselect( 'unload' );*/
                $('.custom-column-select').attr('disabled', 'disabled').hide();
                /*$('.custom-column-select').hide();
                $('.custom-column-select').css('display','none');*/

            }
        });
        $('.filterable-status').multiselect({
            columns: 4,
            search: true,
            selectGroup: false,
            texts: {
                placeholder: 'Select filters',
                search: 'Search Filters'
            }
        });

    </script>
@endpush







