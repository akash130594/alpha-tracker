
function makeElementNestable(element){
    element.nestable({
        maxDepth: 1,
        group: $(this).prop('id'),
        listClass       : 'dd-list',
        itemClass       : 'dd-item',
        dragClass       : 'dd-dragel',
        handleClass     : 'dd-handleee',
        collapsedClass	: 'dd-collapsed',
        noDragClass     : 'dd-nodrag',

    }).nestable('collapseAll');
}

$(function () {
    $('#createTargetModel').on('click', function(e){
        $('.newQuotaForm').find('form')[0].reset();
        jQuery('.newQuotaForm').show();
    });
});

function refreshNestable(element)
{
    element.nestable('destroy');
    element.nestable('init');
}

function questionCustomAgeClicked(questionElement)
{
    var element = jQuery(questionElement);
    var quotaPreviewArea = jQuery('#nestable2');
    var activeQuotaItem = quotaPreviewArea.find('ol').find('li[data-status="active"]');
    var closestAllocation = element.closest('.dd-handle').find('input.allocation_input_field');

    var closestQuestion = element.closest('.profile_question_item');
    var closestOption = element.closest('.question_options_item');

    var answerModalid = '.basic_answers_age_modal';

    if(!element.is(':checked')){
        jQuery(answerModalid).find( "input" ).each(function() {
            jQuery(this).attr('disabled', true);
        });
        console.log('returned false checked');
        return false;
    }
    if( activeQuotaItem.length <= 0 ){
        element.prop('checked', false);
        console.log('returned false active quota');
        return false;
    }

    jQuery(answerModalid).find( "input" ).each(function() {
        jQuery(this).removeAttr('disabled');
    });
    var answerModal = jQuery(answerModalid);
    answerModal.modal('show');
}

function questionCustomZipcodeClicked(questionElement)
{
    var element = jQuery(questionElement);
    var quotaPreviewArea = jQuery('#nestable2');
    var activeQuotaItem = quotaPreviewArea.find('ol').find('li[data-status="active"]');
    var closestAllocation = element.closest('.dd-handle').find('input.allocation_input_field');

    var closestQuestion = element.closest('.profile_question_item');
    var closestOption = element.closest('.question_options_item');

    var answerModalid = '.postcode_custom_modal';

    if(!element.is(':checked')){
        jQuery(answerModalid).find( "textarea" ).each(function() {
            jQuery(this).attr('disabled', true);
        });
        console.log('returned false checked');
        return false;
    }
    if( activeQuotaItem.length <= 0 ){
        element.prop('checked', false);
        console.log('returned false active quota');
        return false;
    }

    jQuery(answerModalid).find( "textarea" ).each(function() {
        jQuery(this).removeAttr('disabled');
    });
    var answerModal = jQuery(answerModalid);
    answerModal.modal('show');
}

var max_age_fields  = 10; //maximum input boxes allowed
var ageCountx = 1; //initlal text box count
function generateAgeGroup(object){
    var ageWrapper      = $(object).closest('.modal-body').find(".input_dynamic_group"); //Fields wrapper
    var add_button      = $(object).closest('.modal-body').find(".add_new_age"); //Add button ID

    if(ageCountx < max_age_fields){ //max input box allowed
        ageCountx++; //text box increment

        var rowId = 'dynamic_age_row_'+ageCountx;

        var output_wrapper = '<div class="row" id="'+rowId+'">';
        var output_wrapper_close = '</div>';

        var input_wrapper = '<div class="form-group col-sm-5">';
        var input_wrapper_close = '</div>';

        var inputStartHtml = input_wrapper+'<input class="touchspin1 form-control" type="number" value="" name="global[custom_age]['+ageCountx+'][start]" min="16" max="99">'+input_wrapper_close;
        var inputEndHtml = input_wrapper+'<input class="touchspin1 form-control" type="number" value="" name="global[custom_age]['+ageCountx+'][end]" min="16" max="99">'+input_wrapper_close;
        var inputbetweenHtml = '<div class="form-group col-sm-1" style="margin: 7px 0;">to</div>';

        var outputHtml = output_wrapper+inputStartHtml+inputbetweenHtml+inputEndHtml+'<a href="javascript:void(0);" class="remove_field" onclick="removeDynamicField(this);"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>'+output_wrapper_close;
        ageWrapper.append(outputHtml);
    }
}

function removeDynamicField(e){
    var element = e;
    jQuery(element).parent('div').remove(); ageCountx--;
}

function saveTargetAgeAttributes(object)
{
    var element = jQuery(object);
    var modal = element.closest('div.modal');
    var panel_element = modal.closest('li.dd-item');
    var title = modal.find('.modal-title').html();
    modal.modal('hide');

    var quotaPreviewArea = jQuery('#nestable2');
    var activeQuotaItem = quotaPreviewArea.find('ol').find('li[data-status="active"]');
    if( activeQuotaItem.length <= 0 ){
        element.prop('checked', false);
        return false;
    }

    var activeQuotaItem = activeQuotaItem.first();

    var closestQuestion = element.closest('.profile_question_item');

    var data_id = closestQuestion.attr('data-id');
    var data_name = closestQuestion.find('.profile_question_label').html();


    if(activeQuotaItem.find('ol').find('li[data-id="'+data_id+'"]').length > 0){
        return false;
    }

    var attribute = generateAttributeHtml(data_name, data_id);

    activeQuotaItem.find('ol').append(attribute);
    prepareNestedList();


}







function generateQuotaHtml(name, number, cpi, quotaCount, quotaSpecs = false)
{
    var html = '<li class="dd-item dd-nodrag quota_item" data-status="disabled">';
    html += '<div class="dd-handle dd-nodrag">';
    html += '<input type="checkbox" class="quota_bulk_select_checkbox">';
    html += name;
    html += '<input type="hidden" name="quotaitem['+quotaCount+'][id]" class="quota_item_input_name" value="">';
    html += '<input type="hidden" name="quotaitem['+quotaCount+'][name]" class="quota_item_input_name" value="'+name+'">';
    html += '<input type="hidden" name="quotaitem['+quotaCount+'][number]" class="quota_item_input_number" value="'+number+'">';
    html += '<input type="hidden" name="quotaitem['+quotaCount+'][cpi]" class="quota_item_input_cpi" value="'+cpi+'">';
    html += getQuotaActions();
    html += '<br/>';
    html += '<span class="text-muted attr_parameters">&nbsp;</span>';
    if(quotaSpecs != false){
        html += '<input type="hidden" name="quotaitem['+quotaCount+'][quota_spec]" class="quota_item_input" value="'+quotaSpecs+'">';
    }else{
        html += '<input type="hidden" name="quotaitem['+quotaCount+'][quota_spec]" class="quota_item_input" value="">';
    }
    html += '&nbsp;</span>';
    html += '</div><ol class="dd-list"></ol>';
    html += '</li>';

    return html;
}

function getQuotaActions()
{
    var html='';
    html += '<div class="quota_edit_div float-right">';
    html += '<div class="col-xs-12 col-md-12">';
    html += '<div class="btn-group ml-2 editable" role="group">';
    html += '<button type="button" class="btn btn-primary quota_actions save">Save</button>';
    html += '<button type="button" class="btn btn-primary quota_actions edit">Edit</button>';
    html += '<button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
    html += '<span class="sr-only">Toggle Dropdown</span>';
    html += '</button>';
    html += '<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(169px, 44px, 0px); top: 0px; left: 0px; will-change: transform;">';
    html += '<a href="javascript:void(0);" class="dropdown-item quota_actions review">Review</a>';
    html += '<a href="javascript:void(0);" class="dropdown-item quota_actions pull_invites">Pull Invites</a>';
    html += '<a href="javascript:void(0);" class="dropdown-item quota_actions duplicate">Duplicate</a>';
    html += '<div class="dropdown-divider"></div>';
    html += '<a href="javascript:void(0);" class="dropdown-item quota_actions delete">Delete</a>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    return html;
}

var quotaCount = 0;
function addNewQuota(e)
{
    //Incrementing QuotaCount;
    window.quotaCount = window.quotaCount + 1;
    var element = jQuery(e);

    var quotaFormDiv = element.closest('.newQuotaForm');
    var quotaForm = element.closest('form');
    var quotaName = quotaForm.find('input[name="S_TGNAME"]').val();
    var quotaCPI = quotaForm.find('input[name="S_TGCPI"]').val();
    var quotaNumber = quotaForm.find('input[name="S_TGNUMBER"]').val();

    if(!(quotaName) || !(quotaNumber)){
        alert('Please provide all details for Quota Setup');
        return false;
    }

    var quotali = generateQuotaHtml(quotaName, quotaNumber, quotaCPI, window.quotaCount);
    quotali = $.parseHTML( quotali );

    var quotaPreview = jQuery('#nestable2');
    quotaPreview.show();
    quotaPreview.find('ol.quota_preview:last').append(quotali);


    refreshNestable(quotaPreview);

    /*quotaFormDiv.hide("slow", {}, 500, function() {
        quotaForm[0].reset();
    });*/
    quotaForm[0].reset();
    quotaFormDiv.hide();

}

function generateAttributeHtml(name, id)
{
    var html = '<li class="dd-item dd-nodrag" data-id="'+id+'">';
    html += '<div class="dd-handle dd-nodrag">';
    html += name;

    html += '<a href="javascript:void(0);" class="remove_attr float-right text-danger" onclick="removeSelectedAttribute(this)"><i class="fas fa-trash"></i></a>';
    html += '</div>';
    html += '</li>';

    return html;
}

function removeSelectedAttribute(e)
{
    var element = jQuery(e);
    element.addClass('hidden');

    var currentOption = element.closest('li');
    var optionId = currentOption.attr('data-id');

    var optionNestable = jQuery('#nestable1');

    var profileOption = optionNestable.find('ol.profile_list').find('li[data-id="'+optionId+'"]');

    profileOption.find('input:checked').each(function(){
        jQuery(this).prop('checked',false);
    });

    currentOption.fadeOut(300, function(){
        jQuery(this).remove();
    });
}

function prepareNestedList()
{
    /*$(".dd-nodrag").on("mousedown", function(event) { // mousedown prevent nestable click
        event.preventDefault();
        return false;
    });*/
}

function prepareAnswersSection()
{
    /*$('div.target_group_panel_body').find('.prefetch_questions').find( ".touchspin1" ).each(function() {
        $(this).TouchSpin({
            buttondown_class: 'btn btn-white',
            buttonup_class: 'btn btn-white'
        });
    });*/
    $('div.apace_quota_section').find( "div.nestable" ).each(function() {
        var element = $(this);
        makeElementNestable(element);
    });
    /*$(".dd-nodrag").on("mousedown", function(event) { // mousedown prevent nestable click
        event.preventDefault();
        return false;
    });*/
}

function questionOptionClicked(e)
{
    var element = jQuery(e);
    var closestAllocation = element.closest('.dd-handle').find('input.allocation_input_field');

    if(!element.is(':checked')){
        closestAllocation.attr("disabled", "disabled");
        return false;
    }

    var quotaPreviewArea = jQuery('#nestable2');
    var activeQuotaItem = quotaPreviewArea.find('ol').find('li[data-status="active"]');
    if( activeQuotaItem.length <= 0 ){
        element.prop('checked', false);
        return false;
    }

    var activeQuotaItem = activeQuotaItem.first();

    var closestQuestion = element.closest('.profile_question_item');

    var data_id = closestQuestion.attr('data-id');
    var data_name = closestQuestion.find('.profile_question_label').html();

    closestAllocation.removeAttr("disabled");

    if(activeQuotaItem.find('ol').find('li[data-id="'+data_id+'"]').length > 0){
        return false;
    }

    var attribute = generateAttributeHtml(data_name, data_id);

    activeQuotaItem.find('ol').append(attribute);
    prepareNestedList();
}

jQuery(document).on('click', '.quota_actions.edit', function(e){
    var currentElement = jQuery(e.target);
    var currentOrderedl = currentElement.closest('ol.quota_preview');
    var currentQuotaItem = currentElement.closest('li.quota_item');

    setQuotaItemEditableEnv(currentQuotaItem);

    currentElement.closest('.btn-group').removeClass('editable').addClass('savable');
    currentQuotaItem.attr('data-status', 'active');

});

jQuery(document).on('click', '.quota_actions.save', function(e){
    var currentElement = jQuery(e.target);

    var currentOrderedl = currentElement.closest('ol.quota_preview');
    var currentQuotaItem = currentElement.closest('li.quota_item');

    setQuotaItemSavableEnv(currentQuotaItem);

    currentElement.closest('.btn-group').removeClass('savable').addClass('editable');
});

jQuery(document).on('click', '.quota_actions.delete', function(e){
    var currentElement = jQuery(e.target);

    var currentQuotaItem = currentElement.closest('li.dd-item.quota_item');

    //currentQuotaItem.hide("puff", {}, 1000, function() {
    currentQuotaItem.hide();
    currentQuotaItem.remove();
    //});

    resetOptionForm();
    //setQuotaItemSavableEnv(currentQuotaItem);

    //currentElement.closest('.btn-group').removeClass('savable').addClass('editable');
});


jQuery(document).on('click', '.quota_actions.duplicate', function(e){
    var currentElement = jQuery(e.target);

    var currentOrderedl = currentElement.closest('ol.quota_preview');
    var currentQuotaItem = currentElement.closest('li.quota_item');

    quotaDuplicate(currentQuotaItem);
    //setQuotaItemSavableEnv(currentQuotaItem);

    //currentElement.closest('.btn-group').removeClass('savable').addClass('editable');
});

function quotaDuplicate(quotaElement)
{
    //Incrementing QuotaCount;
    window.quotaCount = window.quotaCount + 1;

    var quotaName = quotaElement.find('input.quota_item_input_name').val();
    var quotaCPI = quotaElement.find('input.quota_item_input_cpi').val();
    var quotaNumber = quotaElement.find('input.quota_item_input_number').val();
    var quotaSpecs = quotaElement.find('input.quota_item_input').val();
    var quotaOrderedList = quotaElement.find('ol.dd-list').html();

    if(quotaName == '' || quotaNumber == ""){
        alert('Invalid Quota data');
        return false;
    }

    var quotali = generateQuotaHtml(quotaName, quotaNumber, quotaCPI, window.quotaCount);
    quotali = $.parseHTML( quotali );

    var quotali = jQuery(quotali);

    quotali.find('input.quota_item_input').val(quotaSpecs);
    quotali.find('ol.dd-list').html(quotaOrderedList);

    var quotaPreview = jQuery('#nestable2');
    quotaPreview.show();
    quotaPreview.find('ol.quota_preview:last').append(quotali);


    refreshNestable(quotaPreview);

}

function resetQuotaItems()
{
    var nestable = jQuery('#nestable2');

    var nestableOlist = jQuery('#nestable2 > ol.quota_preview');
    nestableOlist.find( "li.quota_item" ).each(function() {
        jQuery(this).attr('data-status', 'disabled');
        jQuery(this).find('.quota_edit_div').find('.btn-group').removeClass('savable').addClass('editable');
    });
    nestable.nestable('collapseAll');
}

function setQuotaItemEditableEnv(currentQuotaItem)
{
    resetQuotaItems();
    currentQuotaItem.find('button.dd-expand').trigger('click');
    fetchOptionsAndResetForm(currentQuotaItem);

    var quotaFormDiv = jQuery('.newQuotaForm');
    var quotaForm = quotaFormDiv.find('form.newQuotaForm_element');

    //quotaFormDiv.show("slide", {}, 500, function() {
    quotaFormDiv.show();
        var quotaName = currentQuotaItem.find('.dd-handle > input.quota_item_input_name').val();
        var quotaNumber = currentQuotaItem.find('.dd-handle > input.quota_item_input_number').val();
        var quotaCPI = currentQuotaItem.find('.dd-handle > input.quota_item_input_cpi').val();

        quotaForm.find('.S_TGNAME').val(quotaName);
        quotaForm.find('.S_TGNUMBER').val(quotaNumber);
        quotaForm.find('.S_TGCPI').val(quotaCPI);

    //});

}

function setQuotaItemSavableEnv(currentQuotaItem)
{
    resetQuotaItems();
    jQuery('#nestable2').nestable('collapseAll');
    saveAndResetQuestionForm(currentQuotaItem);

    var quotaFormDiv = jQuery('.newQuotaForm');
    var quotaForm = quotaFormDiv.find('form.newQuotaForm_element');

    //quotaFormDiv.hide("puff", {}, 500, function() {
    quotaFormDiv.hide();
        quotaForm[0].reset();
    //});
}

function resetOptionForm()
{
    var optionNestable = jQuery('#nestable1');
    var optionForm = jQuery('.nestable1_form');

    optionForm[0].reset();
    optionNestable.nestable('collapseAll');
}

function saveAndResetQuestionForm(currentQuotaItem)
{
    var optionNestable = jQuery('#nestable1');
    var optionForm = jQuery('.nestable1_form');
    var formData = optionForm.serialize();

    var formdataArray = optionForm.serializeArray();
    var formJson = JSON.stringify(formdataArray);
    currentQuotaItem.find('.quota_item_input').val(formJson);


    resetOptionForm();

    currentQuotaItem.attr('data-status','disabled');


    /* var deserializeArray = jQuery.parseJSON(formJson);
    optionForm.deserialize(deserializeArray); */
}

function fetchOptionsAndResetForm(currentQuotaItem)
{
    var optionNestable = jQuery('#nestable1');
    var optionForm = jQuery('.nestable1_form');
    var formJson = currentQuotaItem.find('.quota_item_input').attr('value');
    if(formJson){
        var deserializeArray = jQuery.parseJSON(formJson);
        optionForm.deserialize(deserializeArray);
        //optionNestable.nestable('expandAll');
    }
}
