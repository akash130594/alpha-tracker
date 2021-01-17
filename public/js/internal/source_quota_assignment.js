
$(document).ready(function(e){
    // With a custom message
    $('#source-create').areYouSure( {'message':'Your survey details are not saved!'} );

    var $source_select = jQuery('#source_id');
    var $source_display_container = jQuery('.vendor_container');
    $source_select.select2({
        placeholder: 'Select Sources',
        allowClear: true,
        closeOnSelect: false,
    }).on('change', function() {
        var $selected = $(this).find('option:selected');
        var $container = $source_display_container;


        var $list = $('<div class="content">');
        $selected.each(function(k, value) {
            var $source_html_row = $('<div>').addClass('row');
            $source_name = $(value).text();
            $source_id = $(value).attr('data-vendor_id');
            $source_html_row.append(getSourceNameColumn($source_name, value, $source_id));
            $source_html_row.append(getCPIColumn($source_id, value));
            $source_html_row.append(getQuotaColumn($source_id, value));
            $source_html_row.append(getScreenerColumn($source_id,value));
            $source_html_row.append(getQuotaAssignmentColumn($source_id, value));
            $source_html_row.append(getRemoveColumn(value));

            /*$source_html_row.children('div.remove_source_sel').children('a.remove_source_sel_a')*/


            $list.append($source_html_row);
        });
        $container.html('').append($list);
    }).trigger('change')
    /*.on('select2:close', function (evt) {
        var uldiv = $(this).siblings('span.select2').find('ul');
        var count = $(this).select2('data').length
        uldiv.html("<li>"+count+" items selected</li>")
    })*/;
});


/******************************* Column Generators Start *********************************************/

function getSourceNameColumn($name, value, $source_id) {
    var $source_name_div = $('<div class="col-3 col-sm-3 col-md-3">');

    $project_vendor_input = '';
    if( $(value).attr('data-project_vendor_id') ){
        $project_vendor_id = $(value).attr('data-project_vendor_id');
        $project_vendor_input = $('<input type="hidden" name="project_vendor['+$source_id+']" value="'+$project_vendor_id+'">');
    }
    $nameColumnData = $project_vendor_input;
    $source_name_div.append($nameColumnData).append($name);
    return $source_name_div;
}

function getCPIColumn($source_id, value) {
    var $cpiColumn = $('<div class="col-2 col-sm-2 col-md-2 text-truncate">');
    $cpiValue = '';
    if($(value).attr('data-cpi')){
        $cpiValue = $(value).attr('data-cpi');
    }
    var $cpiColumnData = $('<div class="form-group">')
        .append('<input type="text" class="form-control form-control-lg" name="cpi['+$source_id+']" value="'+$cpiValue+'">')
        .on('change', function(e) {
            var $opt = $(this).data('select2-opt');
            //$(value).attr('data-cpi', e.target.value());
            $input = $(this).find('input');
            console.log($input.val());
            $opt.attr('data-cpi', $input.val());
            $opt.parents('select').trigger('change');
        }).data('select2-opt', $(value));
    $cpiColumn.append($cpiColumnData);
    return $cpiColumn;
}

function getQuotaColumn($source_id, value) {
    var $quotaColumn = $('<div class="col-2 col-sm-2 col-md-2">');
    $quotaValue = '';
    if($(value).attr('data-quota')){
        $quotaValue = $(value).attr('data-quota');
    }
    var $quotaColumnData = $('<div class="form-group">')
        .append('<input type="text" class="form-control form-control-lg" name="quota['+$source_id+']" value="'+$quotaValue+'">')
        .on("change",function (e) {
            var $opt = $(this).data('select2-opt');
            $input = $(this).find('input');
            $opt.attr('data-quota',$input.val());
            $opt.parents('select').trigger('change');
        }).data('select2-opt', $(value));
    $quotaColumn.append($quotaColumnData);
    return $quotaColumn;
}
function getScreenerColumn($source_id, value) {
    var $screenerColumn = $('<div class="col-2 col-sm-2 col-md-2 source_screener source_'+$source_id+'">');

    $screenerData = getScreenerAnchorText($(value));
    var $screenerColumnData = $('<a href="javascript:void(0);"  data-source_id="'+$source_id+'" class="change_screener screener_selection_anchor" data-toggle="modal" data-target="screener_modal">'+$screenerData.text+'</a>');

    var $hiddenFieldsContainer = $('<div>');
    var $screenerHiddenField = $('<input type="hidden" class="global" name="screener['+$source_id+'][global_screener]" value="'+$screenerData.global+'">');
    var $screenerHiddenField2 =  $('<input type="hidden" class="predefined" name="screener['+$source_id+'][predefined_screener]" value="'+$screenerData.predefined+'">');
    var $screenerHiddenField3 =  $('<input type="hidden" class="custom" name="screener['+$source_id+'][custom_screener]" value="'+$screenerData.custom+'">');

    $hiddenFieldsContainer.append($screenerHiddenField);
    $hiddenFieldsContainer.append($screenerHiddenField2);
    $hiddenFieldsContainer.append($screenerHiddenField3);

    $screenerColumn.append($screenerColumnData);
    $screenerColumn.append($hiddenFieldsContainer);

    return $screenerColumn.append($hiddenFieldsContainer);
}

function getQuotaAssignmentColumn($source_id, value) {
    var $quota_assignment_div = $('<div class="col-2 col-sm-2 col-md-2 source_quota quota_section source_'+$source_id+'">');
    $quotaData = getQuotaAssignmentAnchorText($(value));

    var $quota_assignment_column_data = $('<a href="javascript:void(0);"  data-source_id="'+$source_id+'" class="quota_selection quota_assignment_anchor">'+$quotaData.text+'</a>');
    var $quotaHiddenField=[];
    var $hiddenFieldsContainer = $('<div>');
        $quotaHiddenField= $('<input type="hidden" class="quotasel" name="quota_assign[' + $source_id + ']" value="' + $quotaData.value + '">');

    $hiddenFieldsContainer.append($quotaHiddenField);

    $quota_assignment_div.append($quota_assignment_column_data);
    $quota_assignment_div.append($hiddenFieldsContainer);

    return $quota_assignment_div;
}

function getRemoveColumn(value) {
    var $removeColumn = $('<div class="col-1 col-sm-1 col-md-1 remove_source_sel">');
    var $removeColumnData = $('<a class="remove_source_sel_a" href="javascript:void(0)"><span class="material-icons">clear</span></a>')
    $removeColumn.append($removeColumnData)
        .off('click.select2-copy')
        .on('click.select2-copy', function(e) {
            var $opt = $(this).data('select2-opt');
            $opt.prop('selected', false);
            $opt.parents('select').trigger('change');
        }).data('select2-opt', $(value));
    return $removeColumn;
}

/************************************* HTML Generator Columns Ends Here ************************************************/

/************************************* Helper Function Starts Here ************************************************/

function getScreenerAnchorText($option)
{
    console.log($option.attr('data-global_screener'),$option.attr('data-predefined_screener'),$option.attr('data-custom_screener') );
    var $screenerText = '';
    $global = 0, $predefined = 0, $custom = 0;
    if($option.attr('data-global_screener') && $option.attr('data-global_screener') === '1'){
        $screenerText +='<span class="material-icons">done</span>&nbsp;';
        $global = 1;
    } else{
        $screenerText +='<span class="material-icons">clear</span>&nbsp;';
    }

    if( $option.attr('data-predefined_screener') && $option.attr('data-predefined_screener') === '1' ){
        $screenerText +='<span class="material-icons">done</span>&nbsp;';
        $predefined = 1;
    } else{
        $screenerText +='<span class="material-icons">clear</span>&nbsp;';
    }

    if( $option.attr('data-custom_screener') && $option.attr('data-custom_screener') === '1' ){
        $screenerText +='<span class="material-icons">done</span>&nbsp;';
        $custom = 1;
    } else{
        $screenerText +='<span class="material-icons">clear</span>&nbsp;';
    }

    $screenerText = ( $screenerText === '' )?'All':$screenerText;
    return {text:$screenerText, global:$global, predefined:$predefined, custom:$custom };
}

function getQuotaAssignmentAnchorText($option)
{
    var $quotaText = 'All';
    var $quotaAssignVal = '';
    var $all_quota = $option.attr('data-all_quota_id');
    var $current_quota = $option.attr('data-quota_assign');
    var $current_quota_text = $option.attr('data-quota_assign_text');
    if(typeof $current_quota!=="undefined"&& $current_quota!==""){
        var $get_current_quota = $current_quota.split(',')
    }else{
         var $get_current_quota = "";
    }
    if($all_quota){
        var $quota = $all_quota.split(',');
    }else{
        var $quota = "";
    }
   /* var $get_current_quota = $current_quota.split(',');*/
     if(($get_current_quota==="" && $all_quota !=="" && $get_current_quota.length < $quota.length) || ($get_current_quota==="" && $all_quota !==""  && $get_current_quota.length === $quota.length)){
         $quotaText = 'All';
        $quotaAssignVal = $option.attr('data-all_quota_id');
     } else if($get_current_quota !== "" && $all_quota !=="" && $get_current_quota.length < $quota.length) {
        $quotaText = $option.attr('data-quota_assign_text');
         $quotaAssignVal = $option.attr('data-quota_assign');

    } else if($get_current_quota === "" && $all_quota === "" && $get_current_quota.length===$quota.length) {
         $quotaText = 'None';
     }
    return {text:$quotaText, value:$quotaAssignVal};
}

/************************************* Helper Functions Ends Here ************************************************/

/************************************* Event Executions Starts Here ************************************************/

$(document).on('click','.quota_selection', function(e){
    var $source_id = $(this).attr('data-source_id');
    var $option = $('select#source_id').find('option[data-vendor_id="'+$source_id+'"]');

    $quota_list_ids = $option.attr('data-quota_assign');
    $quota_list_text = $option.attr('data-quota_assign_text');
    $get_all_ids = $option.attr('data-all_quota_id');
    $get_all_names = $option.attr('data-all_quota_name');
    console.log($get_all_names);
    $quotaAnchorHtml = $(this).html();
    if ($quotaAnchorHtml !== "All" && $quotaAnchorHtml !== "None") {
        $quota_ids = $quota_list_text.split(', ');
        $quota_ids = $quota_ids.join(",");
        $quota_ids = $quota_ids.split(',');
        $quotaCheckSection = $('form#quota_popup_form').find('.quota_list_group');
        $quotaCheckSection.find('input.quota_list_item').prop('checked', false);
        $.each($quota_ids, function (key, value) {
            $quotaCheckSection.find('input.quota_list_item[data-quota_text="' + value + '"]').prop('checked', 'checked');
        });
    } else if($quotaAnchorHtml === "All"){

            $quota_ids = $get_all_names.split(', ');
            $quota_ids = $quota_ids.join(",");
            $quota_ids = $quota_ids.split(',');
        $quotaCheckSection = $('form#quota_popup_form').find('.quota_list_group');
        $quotaCheckSection.find('input.quota_list_item').prop('checked', false);
        $.each($quota_ids, function (key, value) {
            $quotaCheckSection.find('input.quota_list_item[data-quota_text="' + value + '"]').prop('checked', 'checked');
        });
    } else {
        $quota_ids = "";
        $quotaCheckSection = $('form#quota_popup_form').find('.quota_list_group');
        $quotaCheckSection.find('input.quota_list_item').prop('checked', false);
        $.each($quota_ids, function (key, value) {
            $quotaCheckSection.find('input.quota_list_item[data-quota_text="' + value + '"]').prop('checked', 'checked');
        });
    }
    $quota_change_modal = $('.quota_change');
    $('#vendor_id_quota').val($source_id);
    $quota_change_modal.modal('toggle');
});
$(document).on('click','.quota_pop_save', function(e){
    $source_id = $('#vendor_id_quota').val();
    $quotaPopupform = $('#quota_popup_form');

    $quotaSection = $('.quota_section.source_'+$source_id);
    $quota_anchor = $quotaSection.find('a.quota_assignment_anchor');

    $totalQuota = $quotaPopupform.find('input[type="checkbox"]');

    $checkedQuota = $quotaPopupform.find('input:checked');
    console.log($checkedQuota.length);
    var quotaNames = [];
    var quota_ids = [];
    if ($checkedQuota.length !== 0 && $checkedQuota.length < $totalQuota.length) {
        console.log("hiii");
        $checkedQuota.each(function(){
            $value = $(this).val();
            $name = $(this).attr('data-name');
            quota_ids.push($value);
            quotaNames.push($name);
        });

    } else if( $checkedQuota.length === $totalQuota.length ){
             quotaNames.push('All');
        $checkedQuota.each(function(){
            $value = $(this).val();
            quota_ids.push($value);
        });
    } else if($checkedQuota.length===undefined){
        console.log("hello");
        quotaNames.push('None');
        console.log($totalQuota);
        $totalQuota.each(function(){
            $value = $(this).val();
            quota_ids.push($value);
        });
    }
    quotaNames = quotaNames.join(", ");
    quota_ids = quota_ids.join(",");

    $quota_anchor.html(quotaNames);
    $quotaSection.find('input.quotasel').val(quota_ids);

    jQuery.event.trigger('quotahtmlchanged', {
        elements: $quota_anchor,
        content: {
            source_id: $source_id,
            names: quotaNames,
            values: quota_ids
        }
    });

    $('.quota_change').modal('hide');
    $quotaPopupform.find(':checkbox').prop("checked", true);

    /*$quota_anchor.html('');
    $anchor_html = '';

    for(i=0;i<form.length;i++){
        data+=form.elements[i].value;
    }
    console.log(data);

    $('.quota-list').each(function(){
        value = $(this).val();
        name = $(this).attr('name');
    });*/

});

$(document).on('click','.screener_pop_save', function(e){
    $sourceId = $('#vendor_id_screener').val();
    var global = 0, custom=0, predefined=0;
    $screenerSection = $('.source_screener.source_'+$sourceId);
    $source_anchor = $screenerSection.find('a.screener_selection_anchor');
    $source_anchor.html('');
    $anchor_html = '';
    if( $('#global_screener').is(":checked") ){
        global = 1;
        $anchor_html +='<span class="material-icons">done</span>&nbsp;';
    }else{
        $anchor_html +='<span class="material-icons">done</span>&nbsp;';
    }

    if($('#predefined_screener').is(":checked")){
        predefined = 1;
        $anchor_html +='<span class="material-icons">done</span>&nbsp;';
    }else{
        $anchor_html +='<span class="material-icons">clear</span>&nbsp;';
    }

    if( $('#custom_screener').is(":checked") ){
        custom = 1;
        $anchor_html +='<span class="material-icons">done</span>&nbsp;';
    }else{
        $anchor_html +='<span class="material-icons">clear</span>&nbsp;';
    }
    console.log(global,predefined,custom);
    $source_anchor.html($anchor_html);

    $screenerSection.find('input.global').val(global);
    $screenerSection.find('input.predefined').val(predefined);
    $screenerSection.find('input.custom').val(custom);

    jQuery.event.trigger('screenerhtmlchanged', {
        elements: $source_anchor,
        content: {
            source_id: $sourceId,
            global: global,
            predefined: predefined,
            custom: custom,
        }
    });

    $('.screener_change').modal('hide');
    $('#screener_popup_form')[0].reset();

});


$(document).on('click','.change_screener',function(e) {
    var $source_id = $(this).attr('data-source_id');
    var $option = $('select#source_id').find('option[data-vendor_id="'+$source_id+'"]');
    console.log($option);
    $global_screener = $option.attr('data-global_screener');
    $defined_screener = $option.attr('data-predefined_screener');
    $custom_screener = $option.attr('data-custom_screener');
    var $modal_div = $('.screener_modal');
    if( $global_screener === '1'){
        $('#global_screener').prop('checked',true);
    }else{
        $('#global_screener').prop('checked',false);
    }

    if($custom_screener === '1'){
        $('#custom_screener').prop('checked',true);
    }else{
        $('#custom_screener').prop('checked',false);
    }

    if( $defined_screener === '1'){
        $('#predefined_screener').prop('checked',true);
    }else{
        $('#predefined_screener').prop('checked',false);
    }

    $('#vendor_id_screener').val($source_id);

    $('.screener_change').modal('toggle');

});

$(document).on('screenerhtmlchanged', function(e, data){
    $source_id = data.content.source_id;

    $option = $('select#source_id').find('option[data-vendor_id="'+$source_id+'"]');
    $option.attr('data-global_screener', data.content.global);
    $option.attr('data-predefined_screener', data.content.predefined);
    $option.attr('data-custom_screener', data.content.custom);

});

$(document).on('quotahtmlchanged', function(e, data){
    $source_id = data.content.source_id;
    $option = $('select#source_id').find('option[data-vendor_id="'+$source_id+'"]');
    $option.attr('data-quota_assign_text', data.content.names);
    $option.attr('data-quota_assign', data.content.values);

});

/************************************* Event Execution Ends Here ************************************************/
