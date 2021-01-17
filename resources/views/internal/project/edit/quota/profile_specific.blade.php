@foreach($profileSpecificQuestions as $profile_id => $profileQuestions)
    <li class="dd-item dd-nodrag profile_item" data-id="profile_head_{{$profile_id}}" id="profile_head_{{$profile_id}}">
        <div class="dd-handle profile_label">
            {{ $profileQuestions->first()->profile_section }}
        </div>
        <ol class="dd-list profile_question_list" id="profileQuestions_{{$profile_id}}">
            @foreach($profileQuestions as $questions)
                <li class="dd-item dd-nodrag profile_question_item" data-id="profileQuestions_{{$questions->general_name}}" data-targetProfile="{{$profile_id}}">
                    <div class="dd-handle dd-nodrag profile_question_label">
                        {{$questions->display_name}}
                    </div>
                    {{--@php
                        $is_allocatable = false;
                        if( in_array($questions->general_name, $detailed_question_with_allocation) ){
                            $is_allocatable = true;
                        }
                    @endphp--}}
                    <ol class="dd-list question_options_list" id="profileOptionList_{{$questions->id}}">
                        @if($questions->type == 'Single Punch' || $questions->type == 'Multi Punch')

                            @php
                                $translatedQuestion = $questions->translated[0];
                            @endphp

                            @foreach($translatedQuestion['answers'] as $answer)
                                <li class="dd-item dd-nodrag question_options_item" data-id="profileAnswer_{{$questions->id}}_{{$answer['precode']}}" data-targetProfile="{{$profile_id}}" data-targetQuestion="profileQuestions_{{$questions->id}}">
                                    <div class="dd-handle dd-nodrag">
                                        <div class="checkbox">
                                            <div class="">
                                                <label><input type="checkbox" value="{{$answer['precode']}}" name="detailed[{{$questions->general_name}}][]" onClick="questionOptionClicked(this);">{{$answer['display_name']}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ol>
                </li>
            @endforeach
        </ol>
    </li>
@endforeach
