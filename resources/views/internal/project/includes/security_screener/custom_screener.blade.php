@push('after-styles')
    <style>
        .answers_action > button{

        }
        .scrollspy-example{
            position: relative;
            height: 507px;
            margin-top: .5rem;
            overflow: auto;
        }
        .sk-spinner-wave.sk-spinner {
            margin: 0 auto;
            width: 50px;
            height: 30px;
            text-align: center;
            font-size: 10px;
        }
        .sk-spinner-wave div {
            background-color: #1ab394;
            height: 100%;
            width: 6px;
            display: inline-block;
            -webkit-animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
            animation: sk-waveStretchDelay 1.2s infinite ease-in-out;
        }
        .sk-spinner-wave .sk-rect2 {
            -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s;
        }
        .sk-spinner-wave .sk-rect3 {
            -webkit-animation-delay: -1s;
            animation-delay: -1s;
        }
        .sk-spinner-wave .sk-rect4 {
            -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s;
        }
        .sk-spinner-wave .sk-rect5 {
            -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s;
        }
        @-webkit-keyframes sk-waveStretchDelay {
            0%,
            40%,
            100% {
                -webkit-transform: scaleY(0.4);
                transform: scaleY(0.4);
            }
            20% {
                -webkit-transform: scaleY(1);
                transform: scaleY(1);
            }
        }
        @keyframes sk-waveStretchDelay {
            0%,
            40%,
            100% {
                -webkit-transform: scaleY(0.4);
                transform: scaleY(0.4);
            }
            20% {
                -webkit-transform: scaleY(1);
                transform: scaleY(1);
            }
        }
    </style>
@endpush
@php
    $totalAnswersCount = 0;
@endphp
<div class="card">
    <div class="card-header">
        <strong>Custom Screener</strong>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="row">
                    <button class="btn btn-primary btn-lg btn-block dropdown-toggle mr-3" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="material-icons">add_circle</span> Add Question
                    </button>
                    <div class="dropdown-menu add_question_button" x-placement="bottom-start">
                        <a class="dropdown-item new_question_action multiple_selection_question" data-action="multiple" href="javascript:void(0);">Multiple Selection</a>
                        <a class="dropdown-item new_question_action single_selection_question" data-action="single" href="javascript:void(0);">Single Selection</a>
                        <a class="dropdown-item new_question_action message_box_question" data-action="message" href="javascript:void(0);">Message Box</a>
                    </div>
                    <ul class="list-group col question_menu" id="list-questions">

                        @if(!empty($custom_screener))
                            @foreach($custom_screener as $question_id => $question_item)
                                <li class="list-group-item list-group-item-action" data-question_no="{{$question_id}}">
                                    <a href="#custom_question_{{$question_id}}">
                                        {!! cs_getQuestionTypeIcon($question_item['type']) !!}
                                        Question {{$question_id}}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                        {{--Space for Custom Question Menus--}}

                    </ul>
                </div>
                <div class="row">

                </div>
                <hr/>
                <div class="">
                    <button class="btn btn-primary custom_screener_preview_btn btn-block" type="button">
                        <span class="material-icons">remove_red_eye</span> Preview
                    </button>
                </div>

            </div>

            <div class="col-9 scrollspy-example custom_screener_data_section" data-spy="scroll" data-target="#list-questions" data-offset="0">

                @if(!empty($custom_screener))
                    @foreach($custom_screener as $question_id => $question_item)
                        @php
                            $type = $question_item['type'];
                            $questionText = $question_item['text'];
                            $order = $question_item['order'];
                            $question_text = $question_item['text'];
                            $answers = $question_item['answer'];
                            $is_required = (!empty($question_item['is_required']))?true:false;
                        @endphp
                        {{--Outer Card Container Opens--}}
                        <div class="card question_section" id="custom_question_{{$question_id}}" data-question_label="Question - {{$question_id}}" data-question_id="{{$question_id}}" data-question_type="{{$type}}">


                            <div class="card-header">{{--Card Header Start--}}
                                <strong>{{$questionText}}</strong>
                                <span class="float-right">
                                    {!! cs_getQuestionTypeIcon($type) !!}
                                    {!! cs_getQuestionTypeText($type) !!}
                                </span>
                                <input class="custom_screener_input_element" type="hidden" name="custom[{{$question_id}}][type]" value="{{$type}}">
                                <input class="custom_screener_input_element" type="hidden" name="custom[{{$question_id}}][name]" value="{{$question_id}}">

                            </div>{{--Card Header Ends--}}

                            <div class="card-body">{{--Card Body Start--}}
                                {{--Question Text Start--}}
                                <div class="form-group">
                                    <label for="custom_question_{{$question_id}}">Question</label>
                                    @if($type !== 'message')
                                        <input type="text" name="custom[{{$question_id}}][text]" value="{{$question_text}}" autocomplete="false" id="custom_question_{{$question_id}}" class="form-control custom_screener_input_element">
                                    @else
                                        <textarea rows="3" type="textarea" name="custom[{{$question_id}}][text]" autocomplete="false" id="custom_question_{{$question_id}}" class="form-control custom_screener_input_element">{{$question_text}}</textarea>
                                    @endif
                                </div>
                                {{--Question Text End--}}

                                {{--Answers Section Start--}}
                                <div class="answersection_container">
                                    @if($type !== 'message')
                                        <h6>Action on respondents click :</h6>
                                    @else
                                        <h6>Answers And Logic:</h6>
                                    @endif
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="answer_container">
                                                @foreach($answers as $answerID => $answerValue)
                                                    @php
                                                        ++$totalAnswersCount;
                                                    @endphp
                                                    <div class="input-group my-2">
                                                        @if($type !== 'message')
                                                            <input type="text" class="form-control custom_screener_input_element" value="{{$answerValue['text']}}" name="custom[{{$question_id}}][answer][{{$answerID}}][text]" placeholder="Enter Answer Text Here" aria-label="Enter Answer Text Here">
                                                        @endif
                                                        <div class="input-group-append answers_action">
                                                            @if($type !== 'message')
                                                                <span class="input-group-text btn delete_answer">
                                                                <i class="text-danger far fa-trash-alt"></i>
                                                            </span>
                                                            @endif
                                                            <button class="btn @if($answerValue['action'] === "default_action" ) btn-primary @else btn-outline-secondary @endif default_action" data-action="default_action" type="button">
                                                                <i class="fas fa-play"></i>
                                                            </button>
                                                            <button class="btn @if($answerValue['action'] === "screen_in" ) btn-primary @else btn-outline-secondary @endif screen_in" data-action="screen_in" type="button">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button class="btn @if($answerValue['action'] === "screen_out" ) btn-primary @else btn-outline-secondary @endif screen_out" data-action="screen_out" type="button">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            <button class="btn @if($answerValue['action'] === "skip_action" ) btn-primary @else btn-outline-secondary @endif skip_action" data-action="skip_action" type="button">
                                                                <i class="fas fa-forward"></i>
                                                            </button>
                                                            <input type="hidden" class="answer_action_input custom_screener_input_element" name="custom[{{$question_id}}][answer][{{$answerID}}][action]" value="{{$answerValue['action']}}">
                                                            <select class="custom-select custom_screener_input_element @if($answerValue['action'] !== "skip_action") d-none @endif skipto_select" name="custom[{{$question_id}}][answer][{{$answerID}}][skip_to]">
                                                                @if($answerValue['action'] !== "skip_action")
                                                                    <option selected="selected">Choose Question</option>
                                                                @else
                                                                    <option value="{{$answerValue['skip_to']}}" selected="selected">Question - {{$answerValue['skip_to']}}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if($type !== 'message')
                                                    <div class="input-group mt-2 mb-2">
                                                        <button type="button" class="btn btn-primary add_answer">+ Add Answer</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--Answers Section Ends--}}

                                {{-- After Answers Section --}}
                                @if ($type !== 'message')
                                    <div class="clearfix">
                                        <ul class="clearfix">
                                            <li>
                                                <div class="form-check">
                                                    <input class="form-check-input custom_screener_input_element" type="checkbox" @if($is_required) checked="checked" @endif value="true" name="custom[{{$question_id}}][is_required]">
                                                    <label class="form-check-label" for="require answer">Require Answer</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                                {{--After Answers Section Ends Here--}}


                            </div>{{--Card Body Ends--}}

                            <div class="card-footer">{{--Card Footer Start--}}
                                <div class="delBtn">
                                    <button type="button" class="btn btn-danger delete_question">Delete Questions</button>
                                </div>
                                <input class="custom_screener_input_element" type="hidden" name="custom[{{$question_id}}][order]" value="10">
                            </div>{{--Card Footer Ends--}}
                        </div>
                        {{--Outer Card Container Closed--}}
                    @endforeach
                @endif
                {{--Space for Custom Questions Section--}}
            </div>
        </div>
    </div>
</div>
<div>
    <input type="hidden" id="custom_screener_serialized" value="">
</div>
<div class="modal fade" id="newQuestionModal" tabindex="-1" role="dialog" aria-labelledby="newQuestionModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Question</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group ">
                    <label for="newcustom_question">New Question</label>
                    <input type="text" id="newcustom_question" class="form-control" placeholder="Enter Question name in lower letters">
                </div>
                <input type="hidden" id="custom_question_type" value="">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-primary modal_add_new_custom_question" type="button">Add</button>
            </div>
        </div>

    </div>
</div>
@push('after-scripts')
    <script>
        var $screenerInfo = {
            used_queue: @if(!empty($custom_screener)){!! json_encode(array_keys($custom_screener)) !!}@else{{'[]'}}@endif,
            answer_count: {{$totalAnswersCount}},
        }
        window.screenerInfo = $screenerInfo;
        function generateNewQuestionName($name) {
            $globalvar = window.screenerInfo;

            if (!$name) {
                return false;
            }

            $name = filterCustomQuestionName($name);

            if($.inArray($name, $globalvar.used_queue) !== -1){
                return false;
            }
            $globalvar.used_queue.push($name);
            return $name;
        }

        function filterCustomQuestionName($name) {
            return $name.replace(/[^A-Z0-9]+/ig, "_");
        }
        var $newQuestionModal = $('#newQuestionModal');
        var $customScreenerPreviewModal = $('#customScreenerPreviewModal');
    </script>

    {!! script(asset('js/internal/custom_screener.js')) !!}
    <script>
        $addQuestionButton = $('.add_question_button');
        $addQuestionButton.on('click', '.new_question_action', function(e){
            $action = $(this).attr('data-action');
            $newQuestionModal.find('input#custom_question_type').val($action);
            $newQuestionModal.modal('toggle');
            //addMultiSelectionQuestion();
        });


        $('.custom_screener_preview_btn').on('click', function(e){
            $serializedInput = $('#custom_screener_serialized');
            $custom_screener_section = $('.custom_screener_data_section').find('.custom_screener_input_element');
            $serializedOutput = $custom_screener_section.serialize();
            showScreenerPreviewModal($serializedOutput);
        });

        function showScreenerPreviewModal($serializedOutput)
        {
            $customScreenerPreviewModal.modal('toggle');
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
            if(!$serializedOutput){
                $customScreenerPreviewModal.modal('toggle');
                return false;
            }

            // Make a request for a user with a given ID
            axios.post("{{ route('internal.project.customscreener.preview') }}", {
                params: $serializedOutput
            }).then(function (response) {
                // handle success
                console.log(response);
                if(response.status === 200){
                    $customScreenerPreviewModal.find('.modal-body').html(response.data);
                }

            }).catch(function (error) {
                // handle error
                //$('#dynamic_response_div').html('Some Error Occurred');
                console.log('Error Occured');
            }).then(function () {
                // always executed
                console.log('always executed');
            });

        }

        $('.modal_add_new_custom_question').on('click', function (e) {
            $type = $newQuestionModal.find('input#custom_question_type').val();
            $name = $newQuestionModal.find('input#newcustom_question').val();

            $generatedName = generateNewQuestionName($name);

            if(!$generatedName){
                return false;
            }

            if ($type === "multiple") {
                addMultiSelectionQuestion($generatedName);
            }else if ($type === "single") {
                addSingleSelectionQuestion($generatedName);
            }else if ($type === "message") {
                addMessageBoxQuestion($generatedName);
            }

            $newQuestionModal.find('input').each(function(){
                $(this).val('');
            });
            $newQuestionModal.modal('hide');


        });


        $(document).on('click', '.answers_action > button', function(e){
            $buttonGroup = $(this).closest('.answers_action');
            $skipSelect = $(this).siblings('select.skipto_select');
            $answerActionInput = $(this).siblings('input.answer_action_input');
            $skipSelect.addClass('d-none');
            $questionSection = $buttonGroup.closest('.card.question_section');
            $buttonGroup.find('button').removeClass('btn-primary').addClass('btn-outline-secondary');


            var $activeAction = $(this).attr('data-action');

            if ( $activeAction === "skip_action") {
                $afterQuestions = $questionSection.nextAll();
                if ($afterQuestions.length > 0) {
                    $activeAction = 'skip_action';
                    $skipSelect.removeClass('d-none');
                    $skipSelect.find('option').remove();
                    populateAfterQuestions($questionSection, $afterQuestions, $skipSelect);
                }else{
                    $activeAction = 'default_action'
                }

            }

            $answerActionInput.val($activeAction);
            $activeActionButton = $buttonGroup.find('button.'+$activeAction);
            $activeActionButton.addClass('btn-primary').removeClass('btn-outline-secondary');

        });

        $(document).on('click', '.delete_question', function(e){
            $deleteButton = $(this);
            $questionSection = $deleteButton.closest('.card.question_section');
            deleteQuestion($questionSection);
        });

        $(document).on('click', '.add_answer', function(e){
            $addButton = $(this);
            $questionSection = $addButton.closest('.card.question_section');
            addSelectionAnswers($questionSection);
        });

        $(document).on('click', 'span.delete_answer', function(e){
            $removeButton = $(this);
            $questionSection = $removeButton.closest('.card.question_section');
            $currentInputSection = $removeButton.closest('div.input-group');
            deleteSelectionAnswer($questionSection, $currentInputSection);
        });

    </script>

@endpush
