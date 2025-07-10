@php use Illuminate\Support\Facades\Auth; @endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">

        <a href=""
           class="app-brand-link">
              <span class="app-brand-logo demo">
              </span>
            <span class="app-brand-text demo menu-text fw-bold"><img src="{{asset('assets/images/logo.png')}}"
                                                                     class="w-75"></span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>
    {{--    <sub class="version_position">Version 1.0</sub>--}}
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item @if(Route::is('employer.dashboard')) active @endif">
            <a href="{{route('employer.dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>Dashboard</div>
            </a>
        </li>
        <li class="menu-item @if(Route::is('jobs.*')) active @endif">
            <a href="{{route('jobs.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-file-description"></i>
                <div>Jobs</div>
            </a>
        </li>
        <li class="menu-item @if(Route::is('employer-submited-application.*')) active @endif">
            <a href="{{route('submited-application.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-package"></i>
                <div>Submited Applications</div>
            </a>
        </li>


    </ul>

    <div class="text-left mb-5">
        <ul class="menu-inner py-1">
            <li class="menu-item">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                   class="menu-link text-primary">
                    <i class="menu-icon tf-icons ti ti-logout"></i>
                    <div>Logout</div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <div class="text-left m-4">© {{date('Y')}}, by
        <a href="#"
           class="footer-link text-primary fw-medium">{{config('app.name')}}</a>
    </div>

</aside>
