<div class="card">
    <div class="card-body">
        <nav class="nav nav-pills flex-column flex-sm-row">
            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/$project->id")) }}" href="{{route('internal.project.edit.show', [$project->id])}}">
                <i class="nav-icon icon-book-open"></i> Project
            </a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/respondents")) }}" href="{{route('internal.project.edit.respondent.show', [$project->id])}}">
                <i class="nav-icon icon-settings"></i> Quota
            </a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/security-screener")) }}" href="{{route('internal.project.edit.security_screener.show', [$project->id])}}">
                <i class="nav-icon icon-lock"></i> Security & Screener
            </a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/source-quota")) }}" href="{{route('internal.project.edit.sources_quota.show', [$project->id])}}">
                <i class="nav-icon icon-shuffle"></i> Source Quota Assignment
            </a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/panel-invite")) }}" href="{{route('internal.project.edit.panel_invite.show', [$project->id])}}">
                <i class="nav-icon icon-envelope-open"></i> Panel Invite
            </a>

            <a class="flex-sm-fill text-sm-center nav-link {{ active_class(Active::checkUriPattern("project/edit/*/review-launch")) }}" href="{{route('internal.project.edit.review_launch.show', [$project->id])}}">
                <i class="nav-icon icon-rocket"></i> Review & Launch
            </a>
        </nav>
    </div>
</div>
