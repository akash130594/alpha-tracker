
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a href="{{route('internal.source.edit.show', $source->id)}}" class="dropdown-item">
            Edit
        </a>
        <a href="javascript:void(0);" data-source_id="{{$source->id}}" id="links" class="dropdown-item">
            Show Links
        </a>
    </div>
</div>



