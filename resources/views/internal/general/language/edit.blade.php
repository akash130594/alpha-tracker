
@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> Edit Language
                    </strong>


                </div><!--card-header-->

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    {!!Form::open()->post()->autocomplete('off')!!}
                                    {{--<div class="float-right">
                                        <a href="{{route('internal.general.language.delete.show', ['id' => $language['id']])}}" class="text-danger" onclick="return deleteConfirmation();">
                                            <i class="fas fa-trash-alt"></i>Delete Language</a>
                                    </div>--}}

                                    {{--TODO: Add Rule to only be deleted by Admin or Above--}}

                                    {!!Form::text('code', 'Language Code *', $language['code'])->required()->placeholder('Code')!!}

                                    {{--Todo:Ajax Check if CLient code already exists then don't allow for change--}}
                                    {!! Form::text('name', 'Language Name *',$language['name']) !!}

                                    {!!Form::select('status', 'Status', [1 => 'Active', 0 => 'Inactive'],$language['status'])!!}
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
