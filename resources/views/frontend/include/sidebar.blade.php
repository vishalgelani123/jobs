<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">

        <a href="{{route('dashboard')}}"
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
        <li class="menu-item @if(Route::is('dashboard')) active @endif">
            <a href="{{route('dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>Dashboard</div>
            </a>
        </li>
        <li class="menu-item @if(Route::is('users.*')) active @endif">
            <a href="{{route('users.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-user-circle"></i>
                <div>Users</div>
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
                        <div>Vendor T&C</div>
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

        <li class="menu-item @if(Route::is('pre-vendor-categories.*') || Route::is('pre-vendor-sub-categories.*') || Route::is('vendor-doc-types.*') || Route::is('vendor-types.*') || Route::is('smtp-settings.*') || Route::is('whatsapp-settings.*')) open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div data-i18n="Setting">Setting</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if(Route::is('pre-vendor-categories.*') || Route::is('pre-vendor-sub-categories.*') || Route::is('vendor-doc-types.*') || Route::is('vendor-types.*'))  open @endif">
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
                    </ul>
                </li>
                <li class="menu-item @if(Route::is('smtp-settings.*')) active @endif">
                    <a href="{{route('smtp-settings.index')}}" class="menu-link">
                        <div>SMTP</div>
                    </a>
                </li>
                <li class="menu-item @if(Route::is('whatsapp-settings.*')) active @endif">
                    <a href="{{route('whatsapp-settings.index')}}" class="menu-link">
                        <div>Whatsapp</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
