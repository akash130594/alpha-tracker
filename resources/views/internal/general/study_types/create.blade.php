
@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> Create Study Type
                    </strong>


                </div><!--card-header-->

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    {!!Form::open()->post()->autocomplete('off')!!}

                                    {{--TODO: Add Rule to only be deleted by Admin or Above--}}

                                    {!!Form::text('code', 'Study Type Code *')->required(false)->placeholder('Enter Study Type Code')!!}

                                    {{--Todo:Ajax Check if CLient code already exists then don't allow for change--}}
                                    {!! Form::text('name', 'Study Type Name *')->placeholder('Enter Study Type Name')!!}
                                    {!! Form::text('description', 'Study Type Description*')->placeholder('Enter Study Type Description')!!}


                                    {!!Form::select('status', 'Status', [1 => 'Active', 0 => 'Inactive'])!!}
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

