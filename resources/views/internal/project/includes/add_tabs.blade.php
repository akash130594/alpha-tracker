<div class="card">
    <div class="card-body">
        <nav class="nav nav-pills flex-column flex-sm-row">
            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/pid")) }}" href="{{route('internal.project.create.show')}}">Project</a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/sources")) }}" href="{{route('internal.project.create.show')}}">Sources</a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/respondents")) }}" href="{{route('internal.project.create.show')}}">Quota</a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/screener")) }}" href="{{route('internal.project.create.show')}}">Screener</a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/panelinvite")) }}" href="{{route('internal.project.create.show')}}">Panel Invite</a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/panelinvite")) }}" href="{{route('internal.project.create.show')}}">Source Quota Assignment</a>

            <a class="flex-sm-fill text-sm-center nav-link disabled" href="#">Review & Launch</a>
        </nav>
    </div>
</div>
