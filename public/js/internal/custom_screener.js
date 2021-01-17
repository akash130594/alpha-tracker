Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};


function deleteQuestion($questionSection)
{
    $question_id = $questionSection.attr('data-question_id');
    window.screenerInfo.used_queue.remove($question_id);


    $question_menu = $('.question_menu');

    $question_menu.find('li[data-question_no="'+$question_id+'"]').remove();
    $questionSection.remove();
}

function addSingleSelectionQuestion($questionID)
{

    $questionContainer = $('.scrollspy-example');
    $questionContainer.append(generateQuestionSection($questionID, 'single'));
}

function addMultiSelectionQuestion($questionID)
{
    $questionContainer = $('.scrollspy-example');
    $question = generateQuestionSection($questionID, 'multiple');

    $questionContainer.append($question);
}

function addMessageBoxQuestion($questionID)
{

    $questionContainer = $('.scrollspy-example');
    $questionContainer.append(generateQuestionSection($questionID, 'message'));
}

function generateQuestionSection($question_id, $type)
{
    $element_id = "custom_question_"+$question_id;
    $question_label = 'Question - '+$question_id;
    $element = $('<div class="card question_section" id="'+$element_id+'" data-question_label="'+$question_label+'" data-question_id="'+$question_id+'" data-question_type="'+$type+'">');

    $header = generateQuestionHeader($question_id, $question_label, $type);
    $element.append($header);

    $questionBody = generateQuestionBody($question_id, $type);
    $element.append($questionBody);

    $questionFooter = generateQuestionFooter($question_id, $type);
    $element.append($questionFooter);

    generateQuestionMenuSection($question_id, $type);

    return $element;
}

function generateQuestionMenuSection($question_id, $type)
{
    $menu_list = $('ul.question_menu');
    $list_item = $('<li class="list-group-item list-group-item-action" data-question_no="'+$question_id+'">');

    $anchorHref = '#custom_question_'+$question_id;
    $questionName = 'Question '+$question_id;

    $item_anchor = $('<a href="'+$anchorHref+'">').append(getQuestionTypeIcon($type)).append($questionName);

    $menu_list.append($list_item.append($item_anchor));
}

function addSelectionAnswers($questionSection)
{
    $question_id = $questionSection.attr('data-question_id');
    $type = $questionSection.attr('data-question_type');

    $answer_section = $questionSection.find('.answer_container');

    $answer = generateSelectionAnswerHtml($question_id, $type);
    $answer_section.append($answer);
}

function deleteSelectionAnswer($questionSection, $currentInputSection)
{
    $inputSiblings = $currentInputSection.siblings('.input-group');
    if($inputSiblings.length > 1)
    {
        $currentInputSection.remove();
    }

}

function previewCustomScreener()
{

}

function generateSelectionAnswerHtml($question_id, $type) {
    $element_container  = $('<div class="input-group my-2">');

    $answerID = ++window.screenerInfo.answer_count;

    $answer_id = 'custom_question_'+$question_id+'_answer_'+$answerID;
    $answer_name = 'custom['+$question_id+'][answer]['+$answerID+'][text]';
    $answer_action_name = 'custom['+$question_id+'][answer]['+$answerID+'][action]';
    $skip_select_name = 'custom['+$question_id+'][answer]['+$answerID+'][skip_to]';

    $inputField = $('<input type="text" class="form-control custom_screener_input_element" name="'+$answer_name+'" placeholder="Enter Answer Text Here" aria-label="Enter Answer Text Here">');
    $element_container.append($inputField);

    $answerActionButtons = generateAnswerActionButtons($question_id, $answerID, $answer_action_name, $skip_select_name,  $type);

    $element_container.append($inputField);
    $element_container.append($buttonsGroupContainer);

    return $element_container;

}

function generateAnswerActionButtons($question_id, $answerID, $answer_action_name, $skip_select_name, $type)
{
    if(!$answerID){
        $answerID = ++window.screenerInfo.answer_count;
    }

    if(!$answer_action_name){
        $answer_action_name = 'custom['+$question_id+'][answer]['+$answerID+'][action]';
    }

    if(!$skip_select_name){
        $skip_select_name = 'custom['+$question_id+'][answer]['+$answerID+'][skip_to]';
    }

    $buttonsGroupContainer = $('<div class="input-group-append answers_action">');

    if($type !== 'message'){
        $deleteAnswer = $('<span class="input-group-text btn delete_answer">').append('<i class="text-danger far fa-trash-alt">');
        $buttonsGroupContainer.append($deleteAnswer);
    }

    $nextQuestion = $('<button class="btn btn-outline-secondary default_action" data-action="default_action" type="button">').append('<i class="fas fa-play"></i>')
    $buttonsGroupContainer.append($nextQuestion);

    $screenIn = $('<button class="btn btn-outline-secondary screen_in" data-action="screen_in" type="button">').append('<i class="fas fa-check"></i>')
    $buttonsGroupContainer.append($screenIn);

    $screenOut = $('<button class="btn btn-outline-secondary screen_out" data-action="screen_out" type="button">').append('<i class="fas fa-times"></i>')
    $buttonsGroupContainer.append($screenOut);

    $skip = $('<button class="btn btn-outline-secondary skip_action" data-action="skip_action" type="button">').append('<i class="fas fa-forward"></i>')
    $buttonsGroupContainer.append($skip);

    $answerAction = $('<input type="hidden" class="answer_action_input custom_screener_input_element" name="'+$answer_action_name+'" value="default_action">');
    $buttonsGroupContainer.append($answerAction);

    $skipTo = $('<select class="custom-select d-none skipto_select custom_screener_input_element" name="'+$skip_select_name+'">');
    $skipTo.append('<option selected="selected">Choose Question</option>');
    $buttonsGroupContainer.append($skipTo);

    return $buttonsGroupContainer;
}

function populateAfterQuestions($questionSection, $afterQuestions, $skipSelect)
{
    $afterQuestions.each(function(){
        $question_id = $(this).attr('data-question_id');
        $questionLabel = $(this).attr('data-question_label');
        $skipSelect.append('<option value="'+$question_id+'">'+$questionLabel+'</option>');
    });
}

function getQuestionTypeIcon($type)
{
    if ($type === 'multiple') {
        return $('<i class="far fa-check-square">');
    }else if ($type === 'single') {
        return $('<i class="far fa-dot-circle">');
    }else if ($type === 'message') {
        return $('<i class="fas fa-quote-left">');
    }else{
        return '';
    }
}

function getQuestionTypeText($type)
{
    if ($type === 'multiple') {
        return 'Multiple Selection';
    }else if ($type === 'single') {
        return 'Single Selection';
    }else if ($type === 'message') {
        return 'Message Box';
    }else{
        return '';
    }
}

function generateQuestionHeader($question_id, $question_label, $type)
{
    $headerElement = $('<div class="card-header">');
    $question_name = $('<strong>'+$question_label+'</strong>');
    $question_type_input = $('<input class="custom_screener_input_element" type="hidden" name="custom['+$question_id+'][type]" value="'+$type+'">');
    $question_name_input = $('<input class="custom_screener_input_element" type="hidden" name="custom['+$question_id+'][name]" value="'+$question_id+'">');
    $questionType = $('<span class="float-right">').append(getQuestionTypeIcon($type)).append(getQuestionTypeText($type));
    $headerElement.append($question_name).append($questionType).append($question_type_input).append($question_name_input);
    return $headerElement;
}

function generateQuestionBody($question_id, $type)
{
    $body_container = $('<div class="card-body">');

    $questionText = generateQuestionText($question_id, $type);
    $answerBody = generateAnswersSection($question_id, $type);
    $afterAnswerArea = generateAfterAnswerSection($question_id, $type);

    $body_container.append($questionText);
    $body_container.append($answerBody);
    $body_container.append($afterAnswerArea);
    return $body_container;

}

function generateQuestionFooter($question_id, $type)
{
    $footer_container = $('<div class="card-footer">');
    $footerButton = $('<div class="delBtn">').append('<button type="button" class="btn btn-danger delete_question">Delete Questions</button>');
    $question_order_input = $('<input class="custom_screener_input_element" type="hidden" name="custom['+$question_id+'][order]" value="10">');
    $footer_container.append($footerButton).append($question_order_input);

    return $footer_container;
}

function generateQuestionText($question_id, $type)
{
    $question_html_id = 'custom_question_'+$question_id;

    $question_container = $('<div class="form-group">');
    $question_container.append('<label for="'+$question_html_id+'">Question</label>');

    if($type !== 'message'){
        $question_container.append('<input type="text" name="custom['+$question_id+'][text]" autocomplete="false" id="'+$question_html_id+'" class="form-control custom_screener_input_element">');
    }else{
        $question_container.append('<textarea rows="3" type="textarea" name="custom['+$question_id+'][text]" autocomplete="false" id="'+$question_html_id+'" class="form-control custom_screener_input_element"></textarea>');
    }


    return $question_container;
}

function generateAnswersSection($question_id, $type) {
    $answerSectionContainer = $('<div class="answersection_container">');

    if($type !== 'message'){
        $answerSectionContainer.append('<h6>Answers And Logic:</h6>');
        $answerContainer = $('<div class="answer_container">');
        $answerContainer.append(generateSelectionAnswerHtml($question_id, $type));
        $answerContainer.append(generateSelectionAnswerHtml($question_id, $type));
        $addAnswerColumn = $('<div class="input-group mt-2 mb-2">').append('<button type="button" class="btn btn-primary add_answer">+ Add Answer</button>');


        $answerColumn = $('<div class="col-10">').append($answerContainer).append($addAnswerColumn);

        $answerRowContainer = $('<div class="row">').append($answerColumn);
        $answerSectionContainer.append($answerRowContainer);
    }else{
        $answerSectionContainer.append('<h6>Action on respondents click :</h6>');
        $answerContainer = $('<div class="answer_container">');

        $addAnswerColumn = $('<div class="input-group mt-2 mb-2">').append(generateAnswerActionButtons($question_id, false, false, false, $type));
        $answerColumn = $('<div class="col-10">').append($addAnswerColumn);
        $answerRowContainer = $('<div class="row">').append($answerColumn);
        $answerSectionContainer.append($answerRowContainer);
    }


    return $answerSectionContainer;
}

function getModalCSSLoader()
{
    return $('<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>');
}

function generateAfterAnswerSection($question_id, $type)
{
    $afterAnswerContainer = $('<div class="clearfix">');

    if ($type !== 'message') {
        $listContainer = $('<ul class="clearfix">');

        $checkboxContainer = $('<div class="form-check">')
            .append('<input class="form-check-input custom_screener_input_element" type="checkbox" value="true" name="custom['+$question_id+'][is_required]">')
            .append('<label class="form-check-label" for="require answer">Require Answer</label>');
        $listItem = $('<li>').append($checkboxContainer);

        $listContainer.append($listItem);

        $afterAnswerContainer.append($listContainer);
    }

    return $afterAnswerContainer;
}
