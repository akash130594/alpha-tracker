<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <a class="navbar-brand" href="javascript:;">Dashboard</a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
        <span class="sr-only">Toggle navigation</span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  <img src="{{ $logged_in_user->picture }}" class="img-avatar" alt="{{ $logged_in_user->email }}">
                  <span class="d-md-down-none">{{ $logged_in_user->full_name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                {{--  <div class="dropdown-header text-center">
                    <strong>Account</strong>
                  </div>
                  <a class="dropdown-item" href="#">
                    <i class="fa fa-bell"></i> Updates
                    <span class="badge badge-info">42</span>
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fa fa-envelope"></i> Messages
                    <span class="badge badge-success">42</span>
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fa fa-tasks"></i> Tasks
                    <span class="badge badge-danger">42</span>
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fa fa-comments"></i> Comments
                    <span class="badge badge-warning">42</span>
                  </a>--}}
                  <div class="dropdown-header text-center">
                    <strong>Settings</strong>
                  </div>
                @can('view backend')
                &nbsp;<a href="{{ route('admin.dashboard')}}" class="dropdown-item">
                    <i class="fas fa-user-secret"></i>@lang('navs.frontend.user.administration')
                </a>
                @endcan
                  <a class="dropdown-item" href="{{route('internal.user.profile')}}">
                    <i class="fa fa-user"></i> Profile
                  </a>
                  <a class="dropdown-item" href="{{route('internal.user.profile.setting')}}">
                    <i class="fa fa-wrench"></i> Settings
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fa fa-file"></i> Projects
                    <span class="badge badge-primary">42</span>
                  </a>
                  <div class="divider"></div>
                  <a class="dropdown-item" href="{{ route('frontend.auth.logout') }}">
                      <i class="fas fa-lock"></i> @lang('navs.general.logout')
                  </a>
                </div>
              </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->
