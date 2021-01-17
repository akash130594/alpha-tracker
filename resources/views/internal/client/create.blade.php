@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> @lang('Create Client')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">


                                    {!!Form::open()->post()!!}

                                    {!!Form::text('name', 'Client Name *')->placeholder('Enter Client Name')->required()!!}

                                    {!!Form::text('code', 'Client Code *')->placeholder('Enter Client Code')->required()!!}

                                    {!!Form::text('cvars', 'Client Variables *')->placeholder('Enter Client Variables')->help('Variables should be seperated by comma.')->required()!!}

                                    {!!Form::text('website', 'Website URL')->placeholder('Enter Website URL')->required(false)!!}

                                    {!!Form::text('email', 'Email')->type('email')->placeholder('Enter Client Email')->required(false)!!}

                                    {!!Form::text('phone', 'Phone')->placeholder('Enter Client Phone')->required(false)!!}

                                    {!!Form::select('status', 'Status', [1 => 'Active', 0 => 'Inactive'])->required()!!}


                                    {!!Form::submit("Create")!!}
                                    {!!Form::close()!!}

                                </div>
                            </div>
                        </div>
                    </div><!-- row -->
                </div> <!-- card-body -->
            </div><!-- card -->
        </div><!-- row -->
    </div><!-- row -->
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ mix('css/datatable.css') }}" >
@endpush
