<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">
                @lang('menus.backend.sidebar.general')
            </li>
            <li class="nav-item">
                <a class="nav-link {{ active_class(Active::checkUriPattern('dashboard/*')) }}" href="{{ route('internal.dashboard') }}">
                    <i class="nav-icon icon-speedometer"></i> @lang('menus.backend.sidebar.dashboard')
                </a>
            </li>

            @can('access projects')
                <li class="nav-title">
                    @lang('Projects')
                </li>

                <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('project/*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('/project/*')) }}" href="#">
                        <i class="nav-icon icon-book-open"></i> @lang('Projects')
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('project/')) }}" href="{{ route('internal.project.index') }}">
                                <i class="nav-icon icon-chart"></i>@lang('All Projects')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('project/create/*')) }}" href="{{ route('internal.project.create.show') }}">
                                <i class="nav-icon icon-plus"></i> @lang('Create Project')
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('access surveys')

            @endcan

            @can('access clients')
                <li class="nav-item">
                    <a class="nav-link {{ active_class(Active::checkUriPattern('client/')) }}" href="{{ route('internal.client.index') }}">
                        <i class="nav-icon icon-chart"></i> @lang('Clients')
                    </a>
                </li>
             {{--   <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('client/*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('client/*')) }}" href="#">
                        <i class="nav-icon icon-people"></i> @lang('Clients')
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('client/')) }}" href="{{ route('internal.client.index') }}">
                                <i class="nav-icon icon-chart"></i> @lang('All Clients')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('client/create/*')) }}" href="{{ route('internal.client.create.show') }}">
                                <i class="nav-icon icon-plus"></i> @lang('Create Client')
                            </a>
                        </li>
                    </ul>
                </li>--}}
            @endcan

            <li class="divider"></li>

            @can('access sources')
                <li class="nav-item">
                    <a class="nav-link {{ active_class(Active::checkUriPattern('sources/')) }}" href="{{ route('internal.source.index') }}">
                        <i class="nav-icon icon-briefcase"></i> @lang('Sources')
                    </a>
                </li>
               {{-- <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('source/*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('source/*')) }}" href="#">
                        <i class="nav-icon icon-briefcase"></i> @lang('Sources')
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('sources/')) }}" href="{{ route('internal.source.index') }}">
                                <i class="nav-icon icon-chart"></i> @lang('All Sources')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('sources/create/*')) }}" href="{{ route('internal.source.create.show') }}">
                                <i class="nav-icon icon-plus"></i> @lang('Create Source')
                            </a>
                        </li>
                    </ul>
                </li>--}}
            @endcan

            @can('access reports')
                <li class="nav-item">
                <a class="nav-link {{ active_class(Active::checkUriPattern('reports/all/*')) }}" href="{{route('internal.report.index')}}">
                    <i class="nav-icon icon-book-open"></i> @lang('Reports')
                </a>
                </li>
                {{--<li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('reports/*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('reports/*')) }}" href="#">
                        <i class="nav-icon icon-docs"></i> @lang('Report')
                    </a>


                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('reports/all/*')) }}" href="{{route('internal.report.index')}}">
                                @lang('All Report')
                            </a>
                        </li>
                    </ul>
                </li>--}}
            @endcan


            @can('access archives')
                <li class="nav-item">
                    <a class="nav-link {{ active_class(Active::checkUriPattern('archives/all/*')) }}" href="{{ route('internal.archive.user.index') }}">
                        <i class="nav-icon icon-briefcase"></i> @lang('Archives')
                    </a>
                </li>
                {{--<li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('archives/*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('archives/*')) }}" href="#">
                        <i class="nav-icon icon-folder-alt"></i> @lang('Archives')
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('archives/all/*')) }}" href="{{ route('internal.archive.user.index') }}">
                                @lang('All Archives')
                            </a>
                        </li>
                    </ul>
                </li>--}}
            @endcan

            @can('access general')
            <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('general/*'), 'open') }}">
                <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('general/*')) }}" href="#">
                    <i class="nav-icon icon-people"></i> General Management
                </a>

                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link {{ active_class(Active::checkUriPattern('general/')) }}" href="{{ route('internal.general.country.index') }}">
                            <i class="nav-icon icon-chart"></i> Country
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ active_class(Active::checkUriPattern('general/language/*')) }}" href="{{ route('internal.general.language.index') }}">
                            <i class="nav-icon icon-plus"></i> Language
                        </a>

                    <li class="nav-item">
                        <a class="nav-link {{ active_class(Active::checkUriPattern('general/study_type/*')) }}" href="{{ route('internal.general.study_type.index') }}">
                            <i class="nav-icon icon-plus"></i> Study Type
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ active_class(Active::checkUriPattern('general/survey_topic/*')) }}" href="{{ route('internal.general.survey_topic.index') }}">
                            <i class="nav-icon icon-plus"></i> Survey Topic
                        </a>
                    </li>
                    </li>
                </ul>
            </li>
            @endcan

            @can('access setting')
            <li class="nav-item">
                <a class="nav-link {{ active_class(Active::checkUriPattern('setting/')) }}" href="{{ route('internal.setting.index') }}">
                    <i class="nav-icon icon-settings"></i> @lang('Settings')
                </a>
            </li>
            @endcan

            @can('access security')
                <li class="nav-item">
                    <a class="nav-link {{ active_class(Active::checkUriPattern('security/')) }}" href="{{ route('internal.client.security.show') }}">
                        <i class="nav-icon icon-settings"></i> @lang('Client Security')
                    </a>
                </li>
            @endcan
            {{--<li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('setting/*'), 'open') }}">
                <a class="nav-link nav-dropdown-toggle {{ active_class(Active::checkUriPattern('setting/*')) }}" href="#">
                    <i class="nav-icon icon-settings"></i> @lang('Setting')
                </a>

                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link {{ active_class(Active::checkUriPattern('setting/')) }}" href="{{ route('internal.setting.index') }}">
                            <i class="nav-icon icon-chart"></i> @lang('Setting')
                        </a>
                    </li>
                </ul>
            </li>--}}

            <li class="nav-item">
                <a class="nav-link {{ active_class(Active::checkUriPattern('/dashboard')) }}" href="{{ route('internal.dashboard') }}">
                    <i class="nav-icon icon-globe"></i> @lang('Standard End Pages')
                </a>
            </li>

        </ul>
    </nav>
    <button class="sidebar-minimizer close" type="button"></button>
</div><!--sidebar-->
