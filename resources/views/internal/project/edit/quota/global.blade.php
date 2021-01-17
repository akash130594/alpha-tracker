<li class="dd-item dd-nodrag profile_item" data-id="profile_head_0" id="profile_head_0">
    <div class="dd-handle profile_label">
        Global Quota
    </div>

    <ol class="dd-list profile_question_list" id="profileQuestions_0">
        @foreach($globalQuestions as $question)
            @php
                $answers = [];
                if(!empty($question->translated)) {
                    $translatedQuestion = $question->translated[0];
                    $answers = $translatedQuestion['answers'];
                }

            @endphp
            <li class="dd-item dd-nodrag profile_question_item" data-id="profileQuestions_{{$question->id}}" data-targetProfile="0">
                <div class="dd-handle dd-nodrag profile_question_label">
                    {{$question->display_name}}
                </div>
                <ol class="dd-list question_options_list" id="profileOptionList_{{$question->id}}">

                    @if( $question->id == 'GLOBAL_AGE' )
                        @php
                            $answers = getGlobalAgeOptions();
                        @endphp
                    @elseif($question->id == 'GLOBAL_POSTCODE')
                        @php
                            $answers = getGlobalZipcodeOptions();
                        @endphp
                    @endif
                    @foreach($answers as $answer)
                        <li class="dd-item dd-nodrag question_options_item" data-id="profileQues_{{$question->id}}_Answer_{{$answer['precode']}}" data-targetProfile="0" data-targetQuestion="profileQuestions_{{$question->id}}">
                            <div class="dd-handle dd-nodrag">
                                <div class="checkbox">
                                    <div class="">
                                        <label>
                                            <input type="checkbox" value="{{$answer['precode']}}" name="global[{{$question->general_name}}][]" onClick="@if(isset($answer['on_click'])){!! $answer['on_click'] !!}@else{{'questionOptionClicked(this);'}}@endif">
                                            {{$answer['display_name']}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    @if( $question->id == 'GLOBAL_AGE' )
                        @include('internal.project.edit.quota.includes.age_custom', ['question' => $question, 'profile_id' => 0 ])
                    @elseif($question->id == 'GLOBAL_ZIP')
                        @include('internal.project.edit.quota.includes.postcode_custom', ['question' => $question, 'profile_id' => 0 ])
                    @endif
                </ol>
            </li>
        @endforeach
    </ol>
</li>
