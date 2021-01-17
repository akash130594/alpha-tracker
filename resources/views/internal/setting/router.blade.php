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
                <div class="card-body fade show active col-sm-6" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    {{html()->form('post',route('internal.setting.router.post'))->open()}}
                    <div class="form-group">
                        <label>Domain Setting:</label><br>
                        <input type="text" class="form-control" name="domain" @if($router_details['domain']) value="{{$router_details['domain']}}"@endif><br>
                        <br>
                    </div>
                    <div class="form-group">
                        <label>Start:</label><br>
                        <input type="text" class="form-control" name="start_page" @if($router_details['start_page']) value="{{$router_details['start_page']}}"@endif><br>
                    </div>
                    <div class="form-group">
                        <label>End Page:</label><br>
                        <input type="text" class="form-control" name="end_page" @if($router_details['end_page']) value="{{$router_details['end_page']}}"@endif><br>
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

