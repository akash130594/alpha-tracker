<div class="card-header">
    <div class="question_section">
        @if($current_question['type'] === "message")
            Message
        @else
          {{$current_question['text']}}
        @endif
    </div>
</div>
<div class="card-body">
    <div class="answers_section">
        <div class="errorTxt text-danger"></div>
        @foreach($current_question['answer'] as $ans_id => $answer)
            <div class="form-check form-check-inline">
                @if($current_question['type'] === "single" || $current_question['type'] === "multiple")
                    <input @if( isset($current_question['is_required']) && $current_question['type']=="single") required="required" @endif class="form-check-input" id="answer_{{$ans_id}}" value="{{$ans_id}}" @if($current_question['type'] === "single") type="radio"  name="answer" @else type="checkbox" name="answer[{{$ans_id}}]" @endif >
                    <label class="form-check-label" for="answer_{{$ans_id}}">{{$answer['text']}}</label>
                @elseif( $current_question['type'] === "message" )
                    {{$current_question['text']}}
                @endif
            </div>
        @endforeach
    </div>
</div>
<div class="card-footer">
    <button type="button" class="btn btn-primary" id="fetch_next" @if( isset($current_question['is_required']) && $current_question['type']=="multiple") onclick="checkRequired();" @else onclick=" saveAndFetchNextQuestion();" @endif >Continue</button>
</div>
<input type="hidden" name="current_data" value="{{$current_question_json}}"/>
<input type="hidden" name="screenerdata" value="{{$screener_data}}"/>


<script>
    function checkRequired()
    {
        if ($('input[type=checkbox]').prop('checked')===true){
            console.log("hii");
            saveAndFetchNextQuestion();
        } else if($('input[type=checkbox]').prop('checked')===false) {
            alert('Please select something!');
        }

    $(".answers_section").find("checkbox").each(function(){
    });
    }
</script>
