@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> Your Profile Details
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col col-sm-4 order-1 order-sm-2  mb-4">
                            <div class="card mb-4 bg-light">

                                <div class="card-body">
                                    <h4 class="card-title">
                                        {{ $logged_in_user->name }} <br/>
                                    </h4>

                                    <p class="card-text">
                                        <small>
                                            <i class="fas fa-envelope"></i> {{ $logged_in_user->email }}<br/>
                                            <i class="fas fa-calendar-check"></i> @lang('strings.frontend.general.joined') {{ timezone()->convertToLocal($logged_in_user->created_at, 'F jS, Y') }}
                                        </small>
                                    </p>

                                    <p class="card-text">

                                        <a href="{{ route('internal.dashboard')}}" class="btn btn-info btn-sm mb-1">
                                            <span class="material-icons">
                                                account_circle
                                                </span> @lang('navs.frontend.user.account')
                                        </a>

                                        @can('view backend')
                                            &nbsp;<a href="{{ route('admin.dashboard')}}" class="btn btn-danger btn-sm mb-1">
                                                <i class="fas fa-user-secret"></i> @lang('navs.frontend.user.administration')
                                            </a>
                                        @endcan
                                    </p>
                                </div>
                            </div>

                        </div><!--col-md-4-->

                        <div class="col-md-8 order-2 order-sm-1">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                                    {!!Form::open()->get()->autocomplete('off')!!}
                                                    {{--{!!Form::text('name', 'Client Name *', $client_details->name)->required()->placeholder('Enter Client Name')!!}--}}

                                                    {!!Form::text('first name', 'First Name', $user_details->first_name)->readonly()!!}
                                                    {!!Form::text('last name', 'Last Name', $user_details->last_name)->readonly()!!}
                                                    {!!Form::text('email', 'Email Id', $user_details->email)->readonly()!!}

                                                    {{--Todo:Ajax Check if CLient code already exists then don't allow for change--}}

                                                    <a href="{{route('internal.user.profile.update', $user_details->id)}}" class="btn btn-secondary btn-sm">
                                                        Update
                                                    </a>
                                                    <a href="{{route('internal.user.profile.change.password', $user_details->id)}}" class="btn btn-secondary btn-sm">
                                                        Change Password
                                                    </a>
                                                    {!!Form::close()!!}
                                                </div>
                                        </div>
                                    </div>
                                </div><!-- row -->
                            </div>



                            </div><!--row-->
                        </div><!--col-md-8-->
                    </div><!-- row -->
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
@endsection
