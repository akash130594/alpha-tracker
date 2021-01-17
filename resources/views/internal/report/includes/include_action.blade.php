<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{route('internal.report.traffic.export',[$item->id,$state])}}">Traffic Export</a>
        <a class="dropdown-item" href="{{route('internal.report.screener.export',[$item->id,$state])}}">Export Screener Data</a>
    </div>
</div>


