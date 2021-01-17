<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="btn {{ active_class(Active::checkUriPattern("all-setting/")) }}" id="home-tab"  href="{{route('internal.setting.index')}}" role="tab" aria-selected="true">Home</a>
    </li>
    <li class="nav-item">
        <a class="btn {{ active_class(Active::checkUriPattern("all-setting/*/router-setting")) }} " id="profile-tab"  href="{{route('internal.setting.router')}}" role="tab" aria-controls="contact" aria-selected="false">Router</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
    </li>
</ul>
