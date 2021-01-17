@extends('internal.layouts.new-app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-tachometer-alt"></i> Edit Survey Topics
                    </strong>


                </div><!--card-header-->

                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    {!!Form::open()->post()->autocomplete('off')!!}
                                   {{-- <div class="float-right">
                                        <a href="{{route('internal.general.survey_topic.delete.show', ['id' => $survey_topics->id])}}" class="text-danger" onclick="return deleteConfirmation();">
                                            <i class="fas fa-trash-alt"></i>Delete Survey Topic</a>
                                    </div>--}}

                                    {!!Form::text('name', 'Name of Survey Topic*', $survey_topics->name)->required(false)->placeholder('Enter Topic')!!}

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
