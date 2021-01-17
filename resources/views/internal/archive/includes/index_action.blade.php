{{--<a href="{{route('internal.project.edit.show', [$project->id])}}" class="btn btn-secondary btn-sm">
    Edit
</a>--}}




<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{route('internal.archive.view.details',[$archive->project_id])}}">View Details</a>
        <a class="dropdown-item" href="{{ route('internal.archive.quick.export',[$archive->id])}}" data-archive_id="{{$archive->id}}" id="export">Quick Export</a>
        <a class="dropdown-item" href="{{route('internal.archive.view.summary',[$archive->id])}}">Traffic Summary</a>
        <a class="dropdown-item" id="clone" data-archive_id="[{{$archive->id}}]" href= "{{route('internal.archive.clone.archive',[$archive->id])}}">Re-build Project</a>
    </div>
</div>
