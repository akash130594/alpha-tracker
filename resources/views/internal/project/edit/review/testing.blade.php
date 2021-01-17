<div class="card">
    <div class="card-header">
        <strong>Testing</strong>
    </div>
    <div class="card-body">
        <table class="col-sm-12">
            @if(!empty($internalTestSurvey))
            <tr>
                @php
                    $link = $internalTestSurvey->generateSurveyLiveLink(['replace_vars' => true, 'autoclose' => true]);
                    $testingflag = ( !empty($testingData) )?true:false;
                @endphp
                <td>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Testing URL</span>
                            </div>
                            <input readonly class="form-control" type="text" autocomplete="off" value="{{$link}}" title="test link">
                            @if(!$testingflag)
                            <div class="input-group-append">
                                <a target="_blank" href="{{$link}}" class="testing_btn btn btn-primary float-right">
                                    <i class="fas fa-external-link-alt"></i> Test URL
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <input class="form-check-input" type="hidden" name="linktested" id="testing" value="@if($testingData) 1 @endif">
                    Status : @if ($testingData) AMRID : {{$testingData['amrid']}} ----  TEST ID : {{$testingData['test_id']}} @endif
                </td>
            </tr>
            @endif
            <tr>
                <td>
                    <hr/>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="skiptest" id="skiptesting" value="1">
                        <label class="form-check-label" for="skiptesting">Skip Testing</label>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
@push('after-scripts')
    <script>
        /*Disabling Launch Button if Skip Testing is Not Clicked*/
        $(function(){
            $('input[name="skiptest"]').on('change', function(e)
            {
                if ($(this).is(':checked')) {
                    $('button.project_launch_btn').removeAttr('disabled');
                }else{
                    $('button.project_launch_btn').attr('disabled', 'disabled');
                }
            });
        });
    </script>
@endpush
