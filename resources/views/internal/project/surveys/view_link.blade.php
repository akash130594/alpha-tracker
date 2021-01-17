<div class="card">
    <div class="card-header">
        <strong>
            <i class="fas fa-tachometer-alt"></i> @lang('View Project Links')
        </strong>
    </div><!--card-header-->
    @php $i=0; @endphp
    @foreach($project_vendors as $project_vendor)
        <div class="card-body border link">
            <h5>{{$project_vendor->source->name}}</h5>
            @if( count( $project_vendor->surveys ) == 1 )
                @php
                    $survey = $project_vendor->surveys->first();
                @endphp
                <label class="text-dark font-weight-bold">Vendor Survey No.:</label>
                <span>{{$survey->vendor_survey_code}}</span><br>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Live URL</span>
                        </div>
                        <input readonly class="form-control" id="live_{{$survey->id}}" type="text" autocomplete="off" value="{{$survey->generateSurveyLiveLink()}}">
                        <div class="input-group-append">
                            <button data-clipboard-target="#live_{{$survey->id}}" class="btn btn-outline-info btn-copy copy_live" type="button">
                                <span class="material-icons">content_copy</span> Copy
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Test URL</span>
                        </div>
                        <input readonly class="form-control" type="text" autocomplete="off" id="test_{{$survey->id}}" value="{{$survey->generateSurveyTestLink()}}">
                        <div class="input-group-append">
                            <button data-clipboard-target="#test_{{$survey->id}}" class="btn btn-outline-info btn-copy copy_live_ " type="button">
                                <span class="material-icons">content_copy</span> Copy
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <a href="javascript:void(0);" class="collapse_link" data-id="{{$project_vendor->id}}" data-toggle="collapse"  aria-expanded="false" aria-controls="collapse">Show Links</a>
                <div class="collapse" id="collapse"  data-id="{{$project_vendor->id}}">
                @foreach( $project_vendor->surveys as $survey)
                        <label class="text-dark font-weight-bold">Vendor Survey No.:</label>
                        <span>{{$survey->vendor_survey_code}}</span><br>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Live URL</span>
                            </div>
                            <input readonly class="form-control" type="text" autocomplete="off" id="test_{{$survey->id}}" value="{{$survey->generateSurveyLiveLink()}}">
                            <div class="input-group-append">
                                <button data-clipboard-target="#test_{{$survey->id}}" class="btn btn-outline-info btn-copy" type="button">
                                    <span class="material-icons">content_copy</span> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Test URL</span>
                            </div>
                            <input readonly class="form-control" type="text" autocomplete="off" id="test_{{$survey->id}}" value="{{$survey->generateSurveyTestLink()}}">
                            <div class="input-group-append">
                                <button data-clipboard-target="#test_{{$survey->id}}" class="btn btn-outline-info btn-copy" type="button">
                                    <span class="material-icons">content_copy</span> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr/>
                @endforeach
                </div>
            @endif
        </div>
        @php $i++;@endphp
    @endforeach
</div>
<script>
    $(document).ready(function () {
        $(document).find('.collapse_link').on('click', function (e) {
            var i = $(this).attr('data-id');
            $(this).closest('div').find('div#collapse[data-id="' + i + '"]').collapse("toggle");
        });
    })
</script>

