<nav class="layout-navbar navbar navbar-expand-xl d-xl-none navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="d-flex justify-content-center">
    </div>
</nav>


{{--    <div class="navbar-nav-right  align-items-center" id="navbar-collapse">--}}
{{--        <ul class="navbar-nav flex-row align-items-center --}}{{--ms-auto--}}{{--">--}}
{{--            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">--}}
{{--                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">--}}
{{--                    <i class="ti ti-md"></i>--}}
{{--                </a>--}}
{{--                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">--}}
{{--                    <li>--}}
{{--                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">--}}
{{--                            <span class="align-middle"><i class="ti ti-sun me-2"></i>Light</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">--}}
{{--                            <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">--}}
{{--                            <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>System</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--            <li class="text-xl-left mr-auto">--}}
{{--            </li>--}}
{{--            --}}{{-- <li class="text-xl-left mr-auto">--}}
{{--                 <a href="{{route('event-calendar.index')}}"--}}
{{--                    class="border-radius-5 d-flex text-black bg-label-primary p-2">--}}
{{--                     <i class="menu-icon tf-icons ti ti-calendar"></i>--}}
{{--                     <div>Event Calendar</div>--}}
{{--                 </a>--}}
{{--             </li>--}}

{{--            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">--}}
{{--                <a--}}
{{--                    class="nav-link dropdown-toggle hide-arrow"--}}
{{--                    href="javascript:void(0);"--}}
{{--                    data-bs-toggle="dropdown"--}}
{{--                    data-bs-auto-close="outside"--}}
{{--                    aria-expanded="false">--}}
{{--                    <i class="ti ti-bell ti-md"></i>--}}
{{--                    <span class="badge bg-danger rounded-pill badge-notifications" id="notification_count">0</span>--}}
{{--                </a>--}}
{{--                <ul class="dropdown-menu dropdown-menu-end py-0">--}}
{{--                    <li class="dropdown-menu-header border-bottom">--}}
{{--                        <div class="dropdown-header d-flex align-items-center py-3">--}}
{{--                            <h5 class="text-body mb-0 me-auto">Notification</h5>--}}
{{--                            <a--}}
{{--                                href="javascript:void(0)"--}}
{{--                                class="dropdown-notifications-all text-body"--}}
{{--                                data-bs-toggle="tooltip"--}}
{{--                                data-bs-placement="top"--}}
{{--                                title="Mark all as read"--}}
{{--                            ><i class="ti ti-mail-opened fs-4"></i--}}
{{--                                ></a>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                    <li class="dropdown-notifications-list scrollable-container">--}}
{{--                        <ul class="list-group list-group-flush" id="notification-content">--}}

{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="dropdown-menu-footer border-top">--}}
{{--                        <a--}}
{{--                            href="{{route('notifications.index')}}"--}}
{{--                            class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">--}}
{{--                            View all notifications--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}

{{--            <li class="nav-item navbar-dropdown dropdown-user dropdown">--}}
{{--                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">--}}
{{--                    <div class="avatar avatar-online">--}}
{{--                        <img--}}
{{--                            src="@if(is_file('user_profile/' .Auth::user()->user_profile)) {{asset('user_profile/' .Auth::user()->user_profile)}} @else {{asset('user_profile/no-profile-image.png')}} @endif"--}}
{{--                            alt class="h-auto rounded-circle"/>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--                <ul class="dropdown-menu dropdown-menu-end">--}}
{{--                    <li>--}}
{{--                        <a class="dropdown-item" href="javascript:void(0);">--}}
{{--                            <div class="d-flex">--}}
{{--                                <div class="flex-shrink-0 me-3">--}}
{{--                                    <div class="avatar avatar-online">--}}
{{--                                        <img--}}
{{--                                            src="@if(is_file('user_profile/' .Auth::user()->user_profile)) {{asset('user_profile/' .Auth::user()->user_profile)}} @else {{asset('user_profile/no-profile-image.png')}} @endif"--}}
{{--                                            alt class="h-auto rounded-circle"/>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="flex-grow-1">--}}
{{--                                    <span class="fw-medium d-block">{{Auth::user()->name}}</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <div class="dropdown-divider"></div>--}}
{{--                    </li>--}}

{{--                    --}}{{--  @if(Auth::user()->hasRole('staff'))--}}
{{--                          <li>--}}
{{--                              <a class="dropdown-item" href="{{route('profile.index')}}">--}}
{{--                                  <i class="ti ti-user-check me-2 ti-sm"></i>--}}
{{--                                  <span class="align-middle">My Profile</span>--}}
{{--                              </a>--}}
{{--                          </li>--}}
{{--                      @endif--}}
{{--                    <li>--}}
{{--                        <a class="dropdown-item" href="{{ route('logout') }}"--}}
{{--                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">--}}
{{--                            <i class="ti ti-logout me-2 ti-sm"></i>--}}
{{--                            <span class="align-middle">Log Out</span>--}}
{{--                        </a>--}}
{{--                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">--}}
{{--                            @csrf--}}
{{--                        </form>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </div>--}}

{{--    <div class="navbar-search-wrapper search-input-wrapper d-none">--}}
{{--        <input--}}
{{--            type="text"--}}
{{--            class="form-control search-input container-xxl border-0"--}}
{{--            placeholder="Search..."--}}
{{--            aria-label="Search..."/>--}}
{{--        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>--}}
{{--    </div>--}}
{{--</nav>--}}
