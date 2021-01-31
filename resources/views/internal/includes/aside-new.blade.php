<div class="sidebar" data-color="purple" data-background-color="white" data-image="{{asset('assets/img/sidebar-1.jpg')}}">
    <div class="logo"><a href="/" class="simple-text logo-normal">
        Alpha Tracker
      </a></div>

    <div class="sidebar-wrapper">
      <ul class="nav">
        <li class="nav-item">
            <a class="nav-link {{ active_class(Active::checkUriPattern('dashboard/*')) }}" href="{{ route('internal.dashboard') }}">
                <i class="nav-icon icon-speedometer"></i> @lang('menus.backend.sidebar.dashboard')
            </a>
        </li>
        @can('access projects')

            <li class="nav-item dropdown ">
                <a class="nav-link {{ active_class(Active::checkUriPattern('/project/*')) }}" href="#" id="projetDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="nav-icon icon-book-open"></i> @lang('Projects')
                </a>

                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="projetDropDown">
                    <li class="nav-item">
                        <a class="nav-link {{ active_class(Active::checkUriPattern('project/')) }}" href="{{ route('internal.project.index') }}">
                            <i class="material-icons">assignment</i>@lang('All Projects')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ active_class(Active::checkUriPattern('project/create/*')) }}" href="{{ route('internal.project.create.show') }}">
                            <i class="material-icons">add_task</i> @lang('Create Project')
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @can('access clients')
            <li class="nav-item">
                <a class="nav-link {{ active_class(Active::checkUriPattern('client/')) }}" href="{{ route('internal.client.index') }}">
                    <i class="material-icons">article</i> @lang('Clients')
                </a>
            </li>
        @endcan
        @can('access sources')
            <li class="nav-item">
                <a class="nav-link {{ active_class(Active::checkUriPattern('sources/')) }}" href="{{ route('internal.source.index') }}">
                    <i class="material-icons">source</i> @lang('Sources')
                </a>
            </li>
        @endcan
        @can('access reports')
            <li class="nav-item">
            <a class="nav-link {{ active_class(Active::checkUriPattern('reports/all/*')) }}" href="{{route('internal.report.index')}}">
                <i class="material-icons">book</i> @lang('Reports')
            </a>
            </li>
        @endcan
        @can('access archives')
            <li class="nav-item">
                <a class="nav-link {{ active_class(Active::checkUriPattern('archives/all/*')) }}" href="{{ route('internal.archive.user.index') }}">
                    <i class="material-icons">archive</i> @lang('Archives')
                </a>
            </li>
        @endcan

        @can('access general')
        <li class="nav-item dropdown">
            <a class="nav-link {{ active_class(Active::checkUriPattern('general/*')) }}" data-toggle="dropdown" id="generalDropdown" aria-expanded="false" aria-haspopup="true" href="#">
                <i class="nav-icon icon-people"></i> General Management
            </a>

            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="generalDropdown">
                <li class="nav-item">
                    <a class="nav-link {{ active_class(Active::checkUriPattern('general/')) }}" href="{{ route('internal.general.country.index') }}">
                        <i class="material-icons">flag</i> Country
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ active_class(Active::checkUriPattern('general/language/*')) }}" href="{{ route('internal.general.language.index') }}">
                        <i class="material-icons">g_translate</i> Language
                    </a>

                <li class="nav-item">
                    <a class="nav-link {{ active_class(Active::checkUriPattern('general/study_type/*')) }}" href="{{ route('internal.general.study_type.index') }}">
                        <i class="material-icons"></i> Study Type
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
      </ul>
    </div>
  </div>
