
<nav id="layout-navbar" class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
    <div class="container">
        <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-4">
            <div class="navbar-brand app-brand demo d-flex py-0 py-lg-2 me-4">
                <a href="" class="app-brand-link">
                    <img rel="icon" src="{{asset('assets/images/logo.png')}}" alt="logo">



                </a>
            </div>
            <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    @if(\Illuminate\Support\Facades\Auth::user())
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{route('login')}}">Home</a>
                    </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link fw-medium" href="{{route('login')}}">Login</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#">Contact us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="#">Faqs</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
