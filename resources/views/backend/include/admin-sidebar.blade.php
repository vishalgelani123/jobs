<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">

        <a href="{{route('admin.dashboard')}}"
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
        <li class="menu-item @if(Route::is('admin.dashboard')) active @endif">
            <a href="{{route('admin.dashboard')}}" class="menu-link">
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
                <i class="menu-icon tf-icons ti ti-package"></i>
                <div>Submited Applications</div>
            </a>
        </li>
        {{--<li class="menu-item @if(Route::is('users.*')) active @endif">
            <a href="{{route('users.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-user-circle"></i>
                <div>User Management</div>
            </a>
        </li>

        <li class="menu-item @if(Route::is('general-term-condition-categories.*') || Route::is('term-condition-categories.*')
                || Route::is('documents.*') || Route::is('general-term-conditions.*') || Route::is('term-conditions.*')) open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-description"></i>&nbsp;
                <div>Manage T&C</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if(Route::is('general-term-condition-categories.*') || Route::is('general-term-conditions.*')) active @endif">
                    <a href="{{route('general-term-condition-categories.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-file-description"></i>
                        <div>General T&C</div>
                    </a>
                </li>
                <li class="menu-item @if(Route::is('term-condition-categories.*') || Route::is('term-conditions.*')) active @endif">
                    <a href="{{route('term-condition-categories.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-file-description"></i>
                        <div>Category Wise T&C</div>
                    </a>
                </li>
                <li class="menu-item @if(Route::is('documents.*')) active @endif">
                    <a href="{{route('documents.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-file-description"></i>
                        <div>T&C Documents</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item @if(Route::is('pre-vendor-details.*')) active @endif">
            <a href="{{route('pre-vendor-details.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-package"></i>
                <div>Invite Vendor</div>
            </a>
        </li>

        <li class="menu-item @if(Route::is('vendors.*') || Route::is('branches.*') ) active @endif">
            <a href="{{route('vendors.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-augmented-reality"></i>
                <div>Vendor Details</div>
            </a>
        </li>

        <li class="menu-item @if(Route::is('inquiry-master.*')) active @endif">
            <a href="{{route('inquiry-master.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-3d-cube-sphere"></i>
                <div>Inquiry Master</div>
            </a>
        </li>

        @if(Auth::user()->hasRole('approver') && Auth::user()->hasRole('admin'))
            <li class="menu-item">
                <a href="{{route('approver-inquiry.approver.inquiry')}}" class="menu-link"
                   style="color:#fff !important;background-color: #398b29 !important;">
                    <i class="menu-icon tf-icons ti ti-currency-shekel"></i>
                    <div>Approver Inquiry</div>
                </a>
            </li>
        @endif

        <li class="menu-item @if(Route::is('notifications.index')) active @endif">
            <a href="{{route('notifications.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-bell"></i>
                <div>Notifications</div>
                @php
                    use Illuminate\Support\Facades\Auth;
                    use App\Models\Notification;
                    use Carbon\Carbon;
                    $userId = Auth::id();
                    $today = Carbon::now()->toDateString();
                    $notificationCount = Notification::where('vendor_id', $userId)
                        ->where(function ($query) {
                            $query->where('from', 'vendor')
                                  ->orWhere('from', 'drafter');
                        })
                        ->whereDate('created_at', $today)
                        ->count();
                @endphp
                <div class="badge bg-danger rounded-pill ms-auto">{{$notificationCount}}</div>
            </a>
        </li>

        <li class="menu-item @if(Route::is('pre-vendor-categories.*') || Route::is('pre-vendor-sub-categories.*') || Route::is('vendor-doc-types.*') || Route::is('vendor-types.*') || Route::is('smtp-settings.*') || Route::is('whatsapp-settings.*') || Route::is('general-charges.*')) open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div data-i18n="Setting">Setting</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if(Route::is('pre-vendor-categories.*') || Route::is('pre-vendor-sub-categories.*') || Route::is('vendor-doc-types.*') || Route::is('vendor-types.*') || Route::is('general-charges.*'))  open @endif">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons ti ti-settings"></i>
                        <div>&nbsp;Vendor Master</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item @if(Route::is('pre-vendor-categories.*')) active @endif">
                            <a href="{{route('pre-vendor-categories.index')}}" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-file-description"></i>
                                <div>Category</div>
                            </a>
                        </li>
                        <li class="menu-item @if(Route::is('pre-vendor-sub-categories.*')) active @endif">
                            <a href="{{route('pre-vendor-sub-categories.index')}}" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-file-description"></i>
                                <div>Sub-Category</div>
                            </a>
                        </li>
                        <li class="menu-item @if(Route::is('vendor-doc-types.*')) active @endif">
                            <a href="{{route('vendor-doc-types.index')}}" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-augmented-reality"></i>
                                <div>Documents</div>
                            </a>
                        </li>
                        <li class="menu-item @if(Route::is('vendor-types.*')) active @endif">
                            <a href="{{route('vendor-types.index')}}" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-augmented-reality"></i>
                                <div>Vendor-Types</div>
                            </a>
                        </li>
                        <li class="menu-item @if(Route::is('general-charges.*')) active @endif">
                            <a href="{{route('general-charges.index')}}" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-packages"></i>
                                <div>General Charges</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item @if(Route::is('smtp-settings.*')) active @endif">
                    <a href="{{route('smtp-settings.index')}}" class="menu-link">
                        <div>SMTP</div>
                    </a>
                </li>
                --}}{{--<li class="menu-item @if(Route::is('whatsapp-settings.*')) active @endif">
                    <a href="{{route('whatsapp-settings.index')}}" class="menu-link">
                        <div>Whatsapp</div>
                    </a>
                </li>--}}{{--
            </ul>
        </li>

        <li class="menu-item @if(Route::is('inquiry-report.*') || Route::is('vendor-report.*')) open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-report"></i>&nbsp;
                <div>Reports</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if(Route::is('inquiry-report.*')) active @endif">
                    <a href="{{route('inquiry-report.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-report"></i>
                        <div>Inquiry Report</div>
                    </a>
                </li>
                <li class="menu-item @if(Route::is('vendor-report.*')) active @endif">
                    <a href="{{route('vendor-report.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons ti ti-report"></i>
                        <div>Vendor Report</div>
                    </a>
                </li>
            </ul>
        </li>--}}

    </ul>

    <div class="text-left mb-5">
        <ul class="menu-inner py-1">
            {{--<li class="menu-item @if(Route::is('profile')) active @endif">
                <a href="{{route('profile.index')}}" class="menu-link text-primary">
                    <i class="menu-icon tf-icons ti ti-user-bolt"></i>
                    <div>Profile</div>
                </a>
            </li>--}}
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
