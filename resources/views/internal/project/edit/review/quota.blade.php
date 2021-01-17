<div class="card">
    <div class="card-header">
        <strong>Quota Selection</strong>
        <span class="float-right"><a href="{{route('internal.project.edit.respondent.show', [$project->id])}}">Modify</a></span>
    </div>
    <div class="card-body">
        @foreach($quotas as $quota)
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Quota Name</th>
                    <th>Count</th>
                    <th>CPI</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$quota->name}}</td>
                    <td>{{$quota->count}}</td>
                    <td>{{$quota->cpi}}</td>
                </tr>
                <tr>
                    <td>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Profile Type</th>
                                <th>Attributes Names</th>
                                <th>Selected Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $quota_specs = json_decode($quota->formatted_quota_spec, true);
                            @endphp
                            @foreach($quota_specs as $profile_type => $specs)
                                @foreach($specs as $question => $sel_answers)
                                    @php
                                        $selectedAnswers = collect($sel_answers);
                                        $currentAnswer = $selectedAnswers->flatten()->all();
                                        if (( $key = array_search('status', $currentAnswer)) !== false) {
                                            unset($currentAnswer[$key]);
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            {{strtoupper($profile_type)}}
                                        </td>
                                        <td>{{$question}}</td>
                                        <td>@if($question=="GLOBAL_ZIP")@php $var=nl2br(trim($currentAnswer[1]));
                                                $zip_data = explode('<br />',$var);
                                            @endphp
                                                {{implode(',',$zip_data)}}
                                            @else{{implode(',', $currentAnswer)}}@endif</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        @endforeach
    </div>
</div>
