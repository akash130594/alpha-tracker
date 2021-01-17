<style>
    .tg_header{
        width:100%;
    }

    .allocation_div{
        padding:0;
    }
</style>
<div class="prefetch_questions" class="col-md-12 col-sm-12">
    <div class="row">
        <div class="tg_header">
            <div class="">
                <p class="m-t-lg">Select from these targeting attributes</p>

                <div class="dd nestable" id="nestable1">
                    <form class="profile_questions_form nestable1_form">
                        <ol class="dd-list dd-nodrag profile_list">

                            @includeWhen((!empty($globalQuestions)), 'internal.project.edit.quota.global')

                            @php
                                $detailed_question_with_allocation = [
                                    'gender','age','STANDARD_EDUCATION','STANDARD_Personal_Income_US','ETHNICITY'
                                ];
                            @endphp

                            {{--@include('internal.project.edit.quota.basic_profile', $detailed_question_with_allocation)--}}

                            @includeWhen((!empty($profileSpecificQuestions)), 'internal.project.edit.quota.profile_specific', $detailed_question_with_allocation)

                        </ol>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
@push('after-styles')

@endpush

@push('after-respondent-scripts')
    <script>
        $('.prefetch_questions').find( "div.nestable" ).each(function() {
            var element = $(this);
            makeElementNestable(element);
        });
    </script>
@endpush
