@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> Edit Country
                    </strong>


                </div><!--card-header-->

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    {!!Form::open()->post()->autocomplete('off')!!}
                                    {{--<div class="float-right">
                                        <a href="{{route('internal.general.delete.show', ['id' => $id])}}" class="text-danger" onclick="return deleteConfirmation();">
                                            <i class="fas fa-trash-alt"></i>Delete Country</a>
                                    </div>--}}

                                    {{--TODO: Add Rule to only be deleted by Admin or Above--}}

                                    {!!Form::text('country_code', 'Country Code *', $country['country_code'])->required()->placeholder('Code')!!}

                                    {{--Todo:Ajax Check if CLient code already exists then don't allow for change--}}
                                    {!! Form::text('name', 'Country Name *',$country['name']) !!}

                                    {!!Form::text('currency_code', ' Currency Code *', $country['currency_code'])->required()->placeholder('Enter Currency Code')!!}

                                    {!!Form::select('language', 'Choose your language',$languages, $language_name)->multiple()!!}
                                    {!!Form::submit("Update")!!}
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
