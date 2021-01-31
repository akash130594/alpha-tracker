<div class="card">
    <div class="card-header">
        <strong>Source Quota Assignment</strong>
        <span class="float-right">
                                <a href="{{route('internal.project.edit.sources_quota.show', [$project->id])}}">Modify</a>
                            </span>
    </div>
    <div class="card-body">
        <table id="source_quota" class="table table-bordered">
            <thead>
            <tr>
                <th>Sources</th>
                <th>CPI</th>
                <th>Quota</th>
                <th>Screener(Global,Defined,Custom)</th>
                <th>Quota Selection</th>
            </tr>
            </thead>
            <tbody>
            @foreach($vendors as $vendor)
                <tr>
                    <td>{{$vendor->source->name}}</td>
                    <td>{{$vendor->cpi}}</td>
                    <td>{{$vendor->quota}}</td>
                    <td>
                        <span>
                       {!! (!empty($vendor->global_screener))? '<span class="material-icons">check_circle</span>' : '<span class="material-icons">cancel</span>&nbsp;&nbsp' !!}
                        {!! (!empty($vendor->predefined_screener))? '<span class="material-icons">check_circle</span>' : '<span class="material-icons">cancel</span>&nbsp;&nbsp' !!}
                        {!! (!empty($vendor->custom_screener))? '<span class="material-icons">check_circle</span>' : '<span class="material-icons">cancel</span>&nbsp;&nbsp' !!}
                        </span>
                    </td>
                    @if($vendor->spec_quota_ids==0)
                        <td>
                            All
                        </td>
                    @else
                        <td>
                            {{$vendor->spec_quota_names}}
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
