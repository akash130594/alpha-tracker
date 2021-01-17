{{--<a href="{{route('internal.project.edit.show', [$project->id])}}" class="btn btn-secondary btn-sm">
    Edit
</a>--}}




<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{route('internal.project.edit.show', [$project->id])}}">Edit</a>
        <a href="javascript:void(0);" data-project_id="{{$project->id}}" data-project_status_id="{{$project->status_id}}" data-project_status_label="{{$project->status_label}}" class="dropdown-item project_change_status" data-toggle="modal" data-target="#exampleModal">Change Status</a>
        <a class="dropdown-item" href= "{{route('internal.project.clone',[$project->id])}}" >Clone Project</a>
        <a class="dropdown-item" href="{{route('internal.project.vendors.details',[$project->id])}}">Vendor Management</a>
        <a class="dropdown-item" href="javascript:void(0);" data-project_id="{{$project->id}}" id="view_link" data-toggle="modal">View Links</a>
        <a class="dropdown-item" href="javascript:void(0);" data-project_id="{{$project->id}}" id="view_endpages" data-toggle="modal">View EndPages</a>
        <a class="dropdown-item" href="{{route('internal.project.report.summary.show',[$project->id])}}">Traffic Summary</a>
        <a class="dropdown-item" href="{{route('internal.project.export.traffic',[$project->id])}}">Traffic Export</a>
    </div>
</div>


