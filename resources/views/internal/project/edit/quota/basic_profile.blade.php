@foreach($basicProfileQuestions as $profile)
    <li class="dd-item dd-nodrag profile_item" data-id="profile_head_{{$profile->id}}" id="profile_head_{{$profile->id}}">
        <div class="dd-handle profile_label">
            {{ $profile->display_name}}
        </div>
        <ol class="dd-list profile_question_list" id="profileQuestions_{{$profile->id}}">
            @foreach($profile->questions_plucked as $questions)
                <li class="dd-item dd-nodrag profile_question_item" data-id="profileQuestions_{{$questions->general_name}}" data-targetProfile="{{$profile->id}}">
                    <div class="dd-handle dd-nodrag profile_question_label">
                        {{$questions->display_name}}
                    </div>
                    @php
                        $is_allocatable = false;
                        if( in_array($questions->general_name, $detailed_question_with_allocation) ){
                            $is_allocatable = true;
                        }
                    @endphp
                    <ol class="dd-list question_options_list" id="profileOptionList_{{$questions->id}}">
                        @if($questions->type == 'Single Punch' || $questions->type == 'Multi Punch')
                            @foreach($questions->answers_plucked as $key => $answer)
                                <li class="dd-item dd-nodrag question_options_item" data-id="profileAnswer_{{$answer->id}}" data-targetProfile="{{$profile->id}}" data-targetQuestion="profileQuestions_{{$questions->id}}">
                                    <div class="dd-handle dd-nodrag">
                                        <div class="checkbox @if($is_allocatable) row @endif">
                                            <div class="@if($is_allocatable) col-md-6 @endif">
                                                <label><input type="checkbox" value="{{$answer->precode}}" name="basic[{{$questions->general_name}}][]" onClick="questionOptionClicked(this);">{{$answer->display_name}}</label>
                                            </div>
                                            @if($is_allocatable)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-md-8 col-sm-8 allocation_div">
                                                            <input type="text" name="basic[{{$questions->general_name}}][allocation][{{$answer->id}}]" id="basic_allocation_{{$questions->general_name}}_{{$answer->id}}" class="form-control allocation_input_field" disabled="true" style="" size="50">
                                                        </div>
                                                        <label for="basic_allocation_{{$questions->general_name}}_{{$answer->id}}" class="col-md-3 col-sm-3 form-control-label">%</label>
                                                    </div>
                                                </div>
                                            @endif
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
