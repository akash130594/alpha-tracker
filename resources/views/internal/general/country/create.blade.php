
@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> Create Country
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    {!!Form::open()->post()->autocomplete('off')!!}

                                    {{--TODO: Add Rule to only be deleted by Admin or Above--}}

                                    {!!Form::text('country_code', 'Country Code *')->required(false)->placeholder('Enter Country Code')!!}

                                    {{--Todo:Ajax Check if CLient code already exists then don't allow for change--}}
                                    {!! Form::text('name', 'Country Name *')->placeholder('Enter Country Name')!!}

                                    {!!Form::text('currency_code', ' Currency Code *')->required(false)->placeholder('Enter Currency Code')!!}

                                    {!!Form::select('language', 'Choose your language',$languages)->multiple()!!}
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

@endpush

@push('after-scripts')
    <script>
        function deleteConfirmation() {
            if(!confirm("Are You Sure to delete this"))
                event.preventDefault();
        }
    </script>
@endpush
