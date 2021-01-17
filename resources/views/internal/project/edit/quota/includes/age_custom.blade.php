<li class="dd-item dd-nodrag question_options_item" data-id="profileQues_{{$question->id}}_Answer_custom" data-targetprofile="0" data-targetQuestion="profileQuestions_{{$question->id}}">
    <div class="dd-handle dd-nodrag">
        <div class="checkbox">
            <div class="">
                <label>
                    <input type="checkbox" value="custom" name="global[GLOBAL_AGE][]" onclick="questionCustomAgeClicked(this);">
                    Custom Age
                </label>
            </div>
        </div>
        <div class="modal fade basic_answers_age_modal" id="basicAnswersModal_profileAnswer_customAge" tabindex="-1" role="dialog" aria-labelledby="CustomAge">
            <div class="modal-dialog modal-primary" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Custom Age Group</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 input_dynamic_group">
                                <div class="row">
                                    <div class="form-group col-sm-5">
                                        <input disabled="disabled" class="form-control" type="number" value="" name="global[custom_age][1][start]" min="16" max="99" title="age_start">
                                    </div>
                                    <div class="form-group col-sm-2" style="margin: 7px 0;">
                                        to
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <input disabled="disabled" class="form-control" type="number" value="" name="global[custom_age][1][end]" min="16" max="99" title="age_end">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary add_new_age" onclick="generateAgeGroup(this)">Add New Range</button>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary target_attributes_save" onclick="saveTargetAgeAttributes(this);">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
