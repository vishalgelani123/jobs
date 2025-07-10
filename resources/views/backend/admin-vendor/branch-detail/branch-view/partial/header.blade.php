<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item"><a class="nav-link py-2 @if(Route::is('branches.detail*')) active @endif "
                            href="{{route('branches.detail',[$vendor,$branch])}}"><i
                class="ti ti-building-bank me-1"></i>Branch Details</a></li>
    <li class="nav-item"><a class="nav-link py-2 @if(Route::is('branches.branch.document*')) active @endif"
                            href="{{route('branches.branch.document',[$vendor,$branch])}}"><i
                class="ti ti-file-upload me-1"></i>Branch Documents</a></li>
    <li class="nav-item"><a class="nav-link py-2 @if(Route::is('branches.audit.log*')) active @endif"
                            href="{{route('branches.audit.log',[$vendor,$branch])}}"><i
                class="ti ti-history me-1"></i>Audit Log</a></li>
    <li class="nav-item flex-grow-1"></li>
    <li class="nav-item">
        <a class="nav-link py-2 btn btn-danger"
           href="{{route('vendors.branch.detail', $vendor)}}">
            Back
        </a>
    </li>
</ul>
