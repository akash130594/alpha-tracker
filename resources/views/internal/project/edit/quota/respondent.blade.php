<style>
    /*Imported Start*/
    /* Nestable list */
    .dd {
        position: relative;
        display: block;
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 13px;
        line-height: 20px;
    }
    .dd-list {
        display: block;
        position: relative;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .dd-list .dd-list {
        padding-left: 30px;
    }
    .dd-collapsed .dd-list {
        display: none;
    }
    .dd-item,
    .dd-empty,
    .dd-placeholder {
        display: block;
        position: relative;
        margin: 0;
        padding: 0;
        min-height: 20px;
        font-size: 13px;
        line-height: 20px;
    }
    .dd-handle {
        display: block;
        margin: 5px 0;
        padding: 5px 10px;
        color: #333;
        text-decoration: none;
        border: 1px solid #e7eaec;
        background: #f5f5f5;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .dd-handle span {
        font-weight: bold;
    }
    .dd-handle:hover {
        background: #f0f0f0;
        cursor: pointer;
        font-weight: bold;
    }
    .dd-item > button {
        /* display: block; */
        position: relative;
        cursor: pointer;
        float: left;
        width: 25px;
        height: 20px;
        margin: 5px 0;
        padding: 0;
        text-indent: 100%;
        white-space: nowrap;
        overflow: hidden;
        border: 0;
        background: transparent;
        font-size: 12px;
        line-height: 1;
        text-align: center;
        font-weight: bold;
    }
    .dd-item > button:before {
        content: '+';
        display: block;
        position: absolute;
        width: 100%;
        text-align: center;
        text-indent: 0;
    }
    .dd-item > button[data-action="collapse"]:before {
        content: '-';
    }
    #nestable2 .dd-item > button {
        /*font-family: FontAwesome;*/
        height: 34px;
        width: 33px;
        color: #c1c1c1;
        font-weight: 700;
        font-size: 37px;
    }
    /*#nestable2 .dd-item > button:before {
        content: "\f067";
    }
    #nestable2 .dd-item > button[data-action="collapse"]:before {
        content: "\f068";
    }*/
    .dd-placeholder,
    .dd-empty {
        margin: 5px 0;
        padding: 0;
        min-height: 30px;
        background: #f2fbff;
        border: 1px dashed #b6bcbf;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .dd-empty {
        border: 1px dashed #bbb;
        min-height: 100px;
        background-color: #e5e5e5;
        background-image: -webkit-linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff), -webkit-linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff);
        background-image: -moz-linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff), -moz-linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff);
        background-image: linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff), linear-gradient(45deg, #ffffff 25%, transparent 25%, transparent 75%, #ffffff 75%, #ffffff);
        background-size: 60px 60px;
        background-position: 0 0, 30px 30px;
    }
    .dd-dragel {
        position: absolute;
        z-index: 9999;
        pointer-events: none;
    }
    .dd-dragel > .dd-item .dd-handle {
        margin-top: 0;
    }
    .dd-dragel .dd-handle {
        -webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.1);
        box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.1);
    }
    /**
    * Nestable Extras
    */
    .nestable-lists {
        display: block;
        clear: both;
        padding: 30px 0;
        width: 100%;
        border: 0;
        border-top: 2px solid #ddd;
        border-bottom: 2px solid #ddd;
    }
    #nestable-menu {
        padding: 0;
        margin: 10px 0 20px 0;
    }
    #nestable-output,
    #nestable2-output {
        width: 100%;
        font-size: 0.75em;
        line-height: 1.333333em;
        font-family: open sans, lucida grande, lucida sans unicode, helvetica, arial, sans-serif;
        padding: 5px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    #nestable2 .dd-handle {
        color: inherit;
        border: 1px dashed #e7eaec;
        background: #f3f3f4;
        padding: 10px;
    }
    #nestable2 .dd-handle:hover {
        /*background: #bbb;*/
    }
    #nestable2 span.label {
        margin-right: 10px;
    }
    #nestable-output,
    #nestable2-output {
        font-size: 12px;
        padding: 25px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    /*Imported End*/

    /*Imported from Partial Main CSS*/
    .hidden{
        display:none;
    }
    #nestable2{
        /* height: 328px; */
        /* overflow-y: scroll; */
        overflow-y: visible;
    }
    .panel-body-padded{
        padding: 0 15px 15px 15px;
    }
    .target_group_section{
        padding: 10px;
    }
    input.touchspin1[type=number]::-webkit-inner-spin-button,
    input.touchspin1[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }

    .quota_preview{
        margin: 0 6px;
    }

    .dd-item > button {
        position: relative;
        cursor: pointer;
        float: left;
        width: 25px;
        height: 20px;
        margin: 5px 0;
        padding: 0;
        text-indent: 100%;
        white-space: nowrap;
        overflow: hidden;
        border: 0;
        background: transparent;
        font-size: 12px;
        line-height: 1;
        text-align: center;
        font-weight: bold;
    }
    .dd-item > button:before {
        display: block;
        position: absolute;
        width: 100%;
        text-align: center;
        text-indent: 0;
    }
    .dd-item > button.dd-expand:before {
        content: '+';
    }
    .dd-item > button.dd-collapse:before {
        content: '-';
    }

    .dd-expand {
        display: none;
    }

    .dd-collapsed .dd-list,
    .dd-collapsed .dd-collapse {
        display: none;
    }

    .dd-collapsed .dd-expand {
        display: block;
    }

    .dd-empty,
    .dd-placeholder {
        display:none;
        margin: 5px 0;
        padding: 0;
        min-height: 30px;
        background: inherit;
    }

    .dd-nochildren .dd-placeholder {
        display: none;
    }
    .quota_edit_div{
        margin-left:3%;
        display: inline-block
    }
    li.quota_item[data-status="disabled"] {
        /* background-color:grey; */
    }
    li.quota_item[data-status="active"] {
        background-color: #23c6c8;
        border-color: #23c6c8;
        border: 1px solid;
    }

    .editable .save{
        display:none;
    }
    .savable .edit{
        display:none;
    }
    li.quota_item[data-status="disabled"] a.remove_attr {
        display: none;
    }
    .sw-container .tab-content{
        min-height: 220px;
    }
    /*Imported End*/


    .card_panel_header{
        background-color: #1c84c6;
        border-color: #1c84c6;
        color: white;
    }

    .target_group_panel_left_section{
        border-right: 1px solid;
    }

    /*
 *  Usage:
 *
 *    <div class="sk-spinner sk-spinner-wave">
 *      <div class="sk-rect1"></div>
 *      <div class="sk-rect2"></div>
 *      <div class="sk-rect3"></div>
 *      <div class="sk-rect4"></div>
 *      <div class="sk-rect5"></div>
 *    </div>
 *
 */
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

<div class="card">
    <div class="card-header card_panel_header">
        <div class="panel-heading row">
            <div class="col-4 col-sm-4 col-md-4">Profiles</div>
            <div class="col-6 col-sm-6 col-md-6">Quota Info</div>

            <div class="col-2 col-sm-2 col-md-2">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item btn btn-primary" href="javascript:void(0);" id="createTargetModel"> + New Quota</a>
                        <a class="dropdown-item" href="javascript:void(0);" id="bulk_invites_pull">Pull Invites</a>
                        <a class="dropdown-item" href="#">Something...</a>
                    </div>
                    {{--<button type="button" class="btn btn-info" id="bulk_invites_pull">
                        Pull Invites
                    </button>--}}
                </div>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-4 col-md-4 target_group_panel_left_section">

                {{--@if(!empty($profileQuestions))--}}
                @include('internal.project.edit.quota.profile_questions', [
                'globalQuestions' => $globalQuestions,
                //'basicProfileQuestions' => $profileQuestions[0],
                'profileSpecificQuestions' => $profileQuestions,
                ])
                {{--@else
                    No Profile Questions
                @endif--}}
            </div>
            <div class="col-12 col-sm-8 col-md-8 target_group_panel_right_section targeted_attributes_section">
                <div class="newQuotaForm" style="display:none;">
                    <form class="newQuotaForm_element">
                        <p class="m-t-lg">
                            Add New Quota
                        </p>
                        <div class="tg_header row">
                            <div class="col-xs-6 col-md-6">
                                <div class="form-group">
                                    <label>Quota Name</label>
                                    <input type="text" name="S_TGNAME" class="form-control S_TGNAME" value="" placeholder="Quota Name">
                                </div>

                                <div class="form-group">
                                    <label>Quota CPI</label>
                                    <input type="text" name="S_TGCPI" class="form-control S_TGCPI" value="{{$project->cpi}}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <div class="form-group">
                                    <label>Quota Count</label>
                                    <input type="text" name="S_TGNUMBER" class="form-control S_TGNUMBER" value="{{$project->quota}}">
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary newQuotaAddButton" onclick="addNewQuota(this);">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="dd nestable" id="nestable2" style="border:1px solid #cecece;">
                    {{--<div class="quota_loader" @if(empty($quotaData)) style="display:none;"  @endif>
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                    </div>--}}
                    <form class="quota_target_form">
                        <ol class="dd-list quota_preview">
                            @if(!empty($quotaData))
                                @foreach($quotaData as $key => $quotaItem)
                                    <li class="dd-item dd-nodrag quota_item" data-status="disabled">
                                        <div class="dd-handle dd-nodrag">
                                            <input type="checkbox" class="quota_bulk_select_checkbox">
                                            {{$quotaItem["name"]}}
                                            <input type="hidden" name="quotaitem[{{$key}}][id]" class="quota_item_input_name" value="{{$quotaItem['id']}}">
                                            <input type="hidden" name="quotaitem[{{$key}}][name]" class="quota_item_input_name" value="{{$quotaItem['name']}}">
                                            <input type="hidden" name="quotaitem[{{$key}}][number]" class="quota_item_input_number" value="{{$quotaItem['count']}}">
                                            <input type="hidden" name="quotaitem[{{$key}}][cpi]" class="quota_item_input_cpi" value="{{$quotaItem['cpi']}}">

                                            <div class="quota_edit_div float-right">
                                                <div class="col-xs-12 col-md-12">
                                                    <div class="btn-group ml-2 editable" role="group">
                                                        <button type="button" class="btn btn-primary quota_actions save">Save</button>
                                                        <button type="button" class="btn btn-primary quota_actions edit">Edit</button>
                                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" x-placement="bottom-start">
                                                            <a href="javascript:void(0);" class="dropdown-item quota_actions review">Review</a>
                                                            <a href="javascript:void(0);" class="dropdown-item quota_actions pull_invites">Pull Invites</a>
                                                            <a href="javascript:void(0);" class="dropdown-item quota_actions duplicate">Duplicate</a>
                                                            <div class="dropdown-divider"></div>
                                                            @if($quotaItem)
                                                                <a href="{{route('internal.project.edit.quota.status',[$quotaItem->id])}}"  class="dropdown-item quota_actions disable">Disable</a>
                                                            @else
                                                                <a href="javascript:void(0);" class="dropdown-item quota_actions delete">Delete</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br/>
                                            <span class="text-muted attr_parameters">&nbsp;</span>
                                            <input type="hidden" name="quotaitem[{{$key}}][quota_spec]" class="quota_item_input" value="{{$quotaItem['raw_quota_spec']}}">
                                            </span>
                                        </div>
                                        <ol class="dd-list">
                                            @php
                                                $selectedAttrs = json_decode($quotaItem['formatted_quota_spec']);
                                            @endphp
                                            @foreach($selectedAttrs as $profile_options)
                                                @foreach($profile_options as $question => $values)
                                                    <li class="dd-item dd-nodrag" data-id="profileQuestions_{{$question}}">
                                                        <div class="dd-handle dd-nodrag">
                                                            {{$question}}
                                                            <a href="javascript:void(0);" class="remove_attr float-right testing_class" onclick="removeSelectedAttribute(this)"><i class="fas fa-trash"></i></a>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @endforeach
                                        </ol>
                                    </li>
                                @endforeach
                            @endif
                        </ol>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script src="{{asset('vendors/nestable/jquery.nestable.js')}}"></script>
    {!! script(asset('js/internal/quota.js')) !!}

    <script>
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        $(function(){
            prepareAnswersSection();
        });
    </script>

    @stack('after-respondent-scripts')
@endpush

