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
        <li class="menu-item @if(Route::is('candidate.dashboard')) active @endif">
            <a href="{{route('candidate.dashboard')}}" class="menu-link">
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
        <li class="menu-item @if(Route::is('submited-application.*')) active @endif">
            <a href="{{route('submited-application.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-building-bank"></i>
                <div>Submited Applications</div>
            </a>
        </li>
        {{--<li class="menu-item @if(Route::is('vendor.*')) active @endif">
            <a href="{{route('vendor.vendor.detail')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div>Profile Details</div>
            </a>
        </li>--}}
        {{--<li class="menu-item @if(Route::is('vendor-branches.*')) active @endif">
            <a href="{{route('vendor-branches.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-building-bank"></i>
                <div>Branch Details</div>
            </a>
        </li>
        <li class="menu-item @if(Route::is('vendor-inquiry.*')) active @endif">
            <a href="{{route('vendor-inquiry.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-3d-cube-sphere"></i>
                <div>Inquiry</div>
            </a>
        </li>
        <li class="menu-item @if(Route::is('notification.*')) active @endif">
            <a href="{{ route('notification.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-bell"></i>
                <div>Notification</div>
                @php
                $notification =\App\Models\Notification::where('admin_status','Approved')->where('vendor_id',Auth::id())->whereDate('created_at', \Carbon\Carbon::now()->toDateString())->count();
                @endphp
                <div class="badge bg-danger rounded-pill ms-auto">{{$notification}}</div>
            </a>
        </li>--}}
    </ul>

    <div class="text-left mb-5">
        <ul class="menu-inner py-1">
            <li class="menu-item @if(Route::is('profile')) active @endif">
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
