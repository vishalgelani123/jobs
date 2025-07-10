<div class="bs-stepper-header d-flex flex-column flex-md-row">
    <div class="step flex-grow-1 @if(Route::is('vendor.vendor.detail*')) active @endif" data-target="#account-details-modern">
        <a href="{{ route('vendor.vendor.detail') }}" class="step-trigger" aria-selected="true">
            <span class="bs-stepper-circle"><i class="ti ti-home-star"></i></span>
            <span class="bs-stepper-label">
                <span class="bs-stepper-title">Vendor Details</span>
                <span class="bs-stepper-subtitle">Edit Vendor Details</span>
            </span>
        </a>
    </div>

    <div class="step flex-grow-1 @if(Route::is('vendor.registration.detail*')) active @endif" data-target="#social-links-modern">
        <a href="{{ route('vendor.registration.detail') }}" class="step-trigger" aria-selected="false">
            <span class="bs-stepper-circle"><i class="ti ti-user-cog"></i></span>
            <span class="bs-stepper-label">
                <span class="bs-stepper-title">Registration Details</span>
                <span class="bs-stepper-subtitle">Add Registration Details</span>
            </span>
        </a>
    </div>

    <div class="step flex-grow-1 @if(Route::is('vendor.bank.detail*')) active @endif" data-target="#personal-info-modern">
        <a href="{{ route('vendor.bank.detail') }}" class="step-trigger" aria-selected="false">
            <span class="bs-stepper-circle"><i class="ti ti-building-bank"></i></span>
            <span class="bs-stepper-label">
                <span class="bs-stepper-title">Bank Details</span>
                <span class="bs-stepper-subtitle">Add Bank Details</span>
            </span>
        </a>
    </div>
    <div class="step flex-grow-1 @if(Route::is('vendor.vendor.document*')) active @endif"
         data-target="#social-links-modern">
        <a href="{{route('vendor.vendor.document')}}" type="button" class="step-trigger"
           aria-selected="false">
            <span class="bs-stepper-circle"><i class="ti ti-file-barcode"></i></span>
            <span class="bs-stepper-label">
              <span class="bs-stepper-title">Vendor Document</span>
              <span class="bs-stepper-subtitle">Upload Vendor Document</span>
            </span>
        </a>
    </div>
</div>
