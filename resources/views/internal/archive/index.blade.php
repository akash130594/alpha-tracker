@extends('internal.layouts.new-app')
@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Archives Data')
                    </strong>
                </div><!--card-header-->
                {{ html()->form('post', route('internal.archive.filter.show'))->open() }}
                <div class="row">
                    <div class="col-md-12 px-5 py-3">
                        <div class="form-group row">
                            <label class="col-sm-1 col-form-label">Filter</label>
                            <div class="col-sm-11">
                                <select class="form-control form-control-lg status filterable-status" multiple="multiple" name="status[]"  >
                                    <optgroup label="Country">
                                        @foreach($countries as $country)
                                            <option value= "country.{{$country->id}}" @if ( !empty($country_filter) && in_array($country->id,$country_filter)) selected="selected" @endif>{{$country->name}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Study Type">
                                        @foreach($study_types as $study_type)
                                            <option value= "study_type.{{$study_type->id}}" @if ( !empty($study_filter) && in_array($study_type->id,$study_filter)) selected="selected" @endif>{{$study_type->name}}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Project Manager">
                                        @foreach($project_managers as $project_manager)
                                            <option value= "project_manager.{{$project_manager->id}}" @if ( !empty($project_manager_filter) && in_array($project_manager->id,$project_manager_filter)) selected="selected" @endif>{{$project_manager->name}}</option>
                                        @endforeach
                                    </optgroup>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-1 col-form-label"></label>

                            <button type="submit"  class="btn btn-sm btn-primary filter_apply_btn" role="button">
                                <span class="material-icons">filter_alt</span>Apply Filter</button>
                            @if(!empty($input))
                                <a href="{{route('internal.archive.user.index')}}" class="btn btn-sm btn-danger" role="button">
                                    <span class="material-icons">ban</span> Clear Filters
                                </a>
                            @endif
                        </div>
                    </div>
                    {{ html()->form()->close() }}
                </div>
                <div class="card-body">
                    <!-- popup box modal starts here -->
                    <div class="row">
                        <table id="archive-show" class="table table-striped table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
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
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($archives as $archive)
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
                                <tr id="tr_{{$archive->id}}" class="project_item" data-project_id="{{$archive->id}}">
                                    <td class="datatable_checkbox select-checkbox" data-project_id="{{$archive->id}}" ></td>
                                    {{--<td><input type="checkbox" class="sub_chk" value="{{$project->id}}" name="id[]"></td>--}}
                                    <td>{{$archive->name}}</td>
                                    <td>{{$archive->code}}</td>
                                    <td>{{$archive->client_code}}</td>
                                    <td>{{$archive->created_by}}</td>
                                    <td>{{$archive->starts}}</td>
                                    <td>{{$archive->completes}}</td>
                                    <td>{{$archive->terminates}}</td>
                                    <td>{{$archive->quality_terminate}}</td>
                                    <td>{{$archive->quotafull}}</td>
                                    <td>{{$archive->abandons}}</td>
                                    <td>
                                        @php
                                            try{
                                                $ab = (($archive->abandons/$archive->starts) * 100);
                                            }catch(Exception $exception){
                                              $ab = 0;
                                            }
                                        @endphp
                                        {{round($ab)}}%
                                    </td>
                                    <td>
                                        {{$archive->ir}}
                                    </td>
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
                                    <td>@include('internal.archive.includes.index_action')</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- row -->
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->


@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
    <link rel="stylesheet" href="{{ asset('vendors/jquery-multiselect/jquery.multiselect.css') }}" >
    <style>

    </style>
@endpush

@push('after-scripts')
    {{-- For DataTables --}}
    <script type="text/javascript" src="{{ mix('js/dataTable.js') }}"></script>
    <script src="{{asset('vendors/jquery-multiselect/jquery.multiselect.js')}}"></script>
@endpush

@push('after-scripts')
    <script>

        $(document).ready(function() {
            let datatable = $('#archive-show')
                .on('preXhr.dt', function (e, settings, data) {
                    data.filter = $('.filterable-status').val()
                });
            $(".dataTables_wrapper").css("width", "100%");

            $('.filterable-status').multiselect({
                columns: 4,
                search: true,
                selectGroup: false,
                texts: {
                    placeholder: 'Select filters',
                    search: 'Search Filters'
                }
            })
        });
    </script>
    {{--<script>
         $(document).on('click','#export',function (e) {

             var archive_id = $(this).attr('data-archive_id');
                console.log(archive_id);
             if(!archive_id  || archive_id === 0){
                 return false;
             }
             // Make a request for a user with a given ID
             axios.post("{{ route('internal.archive.quick.export')}}", {
                archive_id:archive_id,
             }).then(function (response) {
                 if(response.status === 200) {
                     console.log(response.data);
                 }
             }).catch(function (error) {
                 // handle error
                 //$('#dynamic_response_div').html('Some Error Occurred');
                 console.log('Error Occured');
             }).then(function () {
                 // always executed
                 console.log('always executed');
             });
         })
    </script>--}}
@endpush







