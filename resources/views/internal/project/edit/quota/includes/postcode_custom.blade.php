<li class="dd-item dd-nodrag question_options_item" data-id="profileQues_{{$question->id}}_Answer_status" data-targetprofile="0" data-targetQuestion="profileQuestions_{{$question->id}}">
    <div class="dd-handle dd-nodrag">
        <div class="checkbox">
            <div class="">
                <label>
                    <input type="checkbox" value="status" name="global[GLOBAL_ZIP][]" onclick="questionCustomZipcodeClicked(this);">
                    Custom Zipcodes
                </label>
            </div>
        </div>
        <div class="modal fade postcode_custom_modal" id="postcode_custom_modal" tabindex="-1" role="dialog" aria-labelledby="postcode_custom_modal">
            <div class="modal-dialog modal-primary" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Zipcode</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="height: 322px;overflow-y: scroll;">
                        <div class="row">
                            <div class="col-12 input_dynamic_group">
                                <div class="form-group">
                                    <label for="zipcodeTextArea">Paste Zipcodes (1 Per line)</label>
                                    <textarea class="form-control" name="global[GLOBAL_ZIP][values]" id="zipcodeTextArea" rows="10" style="min-width: 100%" disabled="disabled"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="question_loader" style="display:none;">
                            <div class="sk-spinner sk-spinner-wave">
                                <div class="sk-rect1"></div>
                                <div class="sk-rect2"></div>
                                <div class="sk-rect3"></div>
                                <div class="sk-rect4"></div>
                                <div class="sk-rect5"></div>
                            </div>
                        </div>
                        <div class="row invalid_div" style="display: none">
                            <div class="col-12 input_dynamic_group">
                                <label for="zipcodeTextArea">Invalid Postcodes</label>
                                <div class="form-group invalid">
                                    <textarea class="form-control" id="invalid_text" rows="10" style="min-width: 50%" disabled="disabled"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger mr-auto" data-project_id="{{$project->id}}" onclick="validatePostCodes({{$project->id}})">Validate PostCodes</button>
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary target_attributes_save" onclick="saveTargetAgeAttributes(this);">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>

<style>
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
    @keyframes  sk-waveStretchDelay {
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

<script>
    function validatePostCodes(project_id)
    {
        $('.invalid_div').hide();
        $('textarea#invalid_text').html("");
        $('textarea#invalid_text').attr('disabled','disabled');
        var post_code_val = $("textarea#zipcodeTextArea").val();
        var display_txt = post_code_val.replace(/\n/g, ",");
        if(!post_code_val){
            return false;
        }
        var headers = {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
        jQuery('.question_loader').show();
        axios.get("{{ route('internal.project.verify.postcodes') }}", {
        params: {
            post_codes: display_txt,
            project_id: project_id
        }
         }).then(function (response) {
            if( response.status === 200 ){
                jQuery('.question_loader').hide();
                var $html = response.data;
                console.log($html.valid.data);
                if($html.valid.data){
                    var valid_data = $html.valid.data.replace(/,/g,"\n");
                }
                $("textarea#zipcodeTextArea").val(valid_data);
                if($html.invalid.count){
                    console.log("tada");
                 $('.invalid_div').show();
                    $('textarea#invalid_text').html("Invalid Postcodes are '"+$html.invalid.data+"'");
                }
            }
        }).catch(function (error) {
            alert('error occured');
            console.log(error);
        }).then(function () {
            $('#loader').hide();
        });
    }
    </script>
