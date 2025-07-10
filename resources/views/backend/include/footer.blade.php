<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div
            class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
            @if(Auth::user()->hasRole('admin'))
                <div>© {{date('Y')}}, by
                    <a href="#"
                       class="footer-link text-primary fw-medium">{{config('app.name')}}</a>
                </div>
            @endif
        </div>
    </div>
</footer>
