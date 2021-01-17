@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                {!!Form::open()->patch()->autocomplete('off')->id('survey-edit')->route('internal.project.edit.review_launch.post', [$project->id])!!}
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Review & Launch Project')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @include('internal.project.includes.edit_tabs')

                            @include('internal.project.edit.review.project')

                            @include('internal.project.edit.review.quota')

                            @include('internal.project.edit.review.security_screener')

                            @include('internal.project.edit.review.source_quota_assign')

                            @include('internal.project.edit.review.testing')
                        </div>
                    </div>
                </div><!-- card -->
                <div class="card-footer text-center">

                    <button class="btn btn-lg btn-primary pull-right project_launch_btn"  style="width:20em" type="submit" @if(!$testingData) disabled="disabled" @endif>
                        <i class="fas fa-rocket"></i> Launch
                    </button>
                </div>
                {!!Form::close()!!}
            </div><!-- row -->
        </div><!-- row -->
    </div>


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
