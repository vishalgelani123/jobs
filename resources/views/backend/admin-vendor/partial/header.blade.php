<ul class="nav nav-pills flex-column flex-md-row mb-3">
    {{--<li class="nav-item"><a class="nav-link py-2 @if(Route::is('vendors.vendor.detail*')) active @endif"
                            href="{{route('vendors.vendor.detail',$vendor)}}"><i
                class="ti ti-user me-1"></i>Vendor Details</a></li>
    <li class="nav-item"><a class="nav-link py-2 @if(Route::is('vendors.vendor.document*')) active @endif"
                            href="{{route('vendors.vendor.document',$vendor)}}"><i
                class="ti ti-file-upload me-1"></i>Vendor Documents</a></li>--}}
    <li class="nav-item"><a class="nav-link py-2 @if(Route::is('vendors.branch.detail*')) active @endif"
                            href="{{route('vendors.branch.detail',$vendor)}}"><i
                class="ti ti-building-bank me-1"></i>Branch Details</a></li>
    <li class="nav-item flex-grow-1"></li>
    <li class="nav-item">
        <a class="nav-link py-2 btn btn-danger"
           href="{{route('vendors.index')}}">Back
        </a>
    </li>
</ul>
