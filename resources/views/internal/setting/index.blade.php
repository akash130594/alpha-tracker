@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="icon icon-settings"></i> @lang('All Settings')
                    </strong>
                    </div><!--card-header-->
                    <div class="card-title">
                        @include('internal.setting.includes.setting_navtab')
                    </div>
                     <div class="card-body fade show active col-sm-6" id="home" role="tabpanel" aria-labelledby="home-tab">
                         {{html()->form('post',route('internal.setting.post.data'))->open()}}
                        <div class="form-group">
                        <label for="report">Report Pagination:</label><br>
                       {{--<select class="form-control" name="report_pagination">
                       @for($i=1;$i<=10;$i++)
                               <option @if($report_pagination)value="{{$report_pagination}}" selected @else value="{{$i}}" @endif>{{$i}}</option>
                           @endfor
                       </select>--}}
                            <input type="text" class="form-control" id="report" name="report_pagination" @if($report_pagination) value="{{$report_pagination}}"@endif><br>
                            <br>
                        </div>
                        <div class="form-group">
                            <label for="unique_folder_name">Unique Id Folder Name:</label><br>
                            <input type="text" class="form-control" id="unique_folder_name" name="unique_id_folder_name" @if($unique_folder_name) value="{{$unique_folder_name}}"@endif><br>
                        </div>
                        <div class="form-group">
                            <label for="project_quota">Unique Id Folder Name:</label><br>
                            <input type="text" id="project_quota" class="form-control" name="project_quota" @if($project_quota) value="{{$project_quota}}"@endif><br>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" name="submit" value="Submit"><br>
                        </div>
                         {{html()->form()->close()}}
                     </div>
                            {{--<div class="card-body fade show active col-sm-6" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="form-group">
                                    <label>Router Setting:</label>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">hmmmm</div>--}}
                        </div>
                    </div><!-- row -->
                </div> <!-- card-body -->

@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
    <link rel="stylesheet" href="{{ asset('vendors/jquery-multiselect/jquery.multiselect.css') }}" >
@endpush
@push('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.23.0/moment.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="{{asset('vendors/jquery-multiselect/jquery.multiselect.js')}}"></script>

@endpush

