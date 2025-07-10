<div class="bs-stepper-header d-flex">
    <div class="step flex-grow-1 @if(Route::is('vendor-branches.branch.detail*')) active @endif"
         data-target="#account-details-modern">
        <a href="{{route('vendor-branches.branch.detail',$branch)}}" type="button" class="step-trigger"
           aria-selected="true">
            <span class="bs-stepper-circle"><i class="ti ti-home-star"></i></span>
            <span class="bs-stepper-label">
              <span class="bs-stepper-title">Branch Details</span>
              <span class="bs-stepper-subtitle">Edit Branch Details</span>
            </span>
        </a>
    </div>
    <div class="step flex-grow-1 @if(Route::is('vendor-branches.registration.detail*')) active @endif"
         data-target="#social-links-modern">
        <a href="{{route('vendor-branches.registration.detail',$branch)}}" type="button" class="step-trigger"
           aria-selected="false">
            <span class="bs-stepper-circle"><i class="ti ti-user-cog"></i></span>
            <span class="bs-stepper-label">
              <span class="bs-stepper-title">Registration Details</span>
              <span class="bs-stepper-subtitle">Add Registration Details</span>
            </span>
        </a>
    </div>
    <div class="step flex-grow-1 @if(Route::is('vendor-branches.bank.detail*')) active @endif"
         data-target="#personal-info-modern">
        <a href="{{route('vendor-branches.bank.detail',$branch)}}" type="button" class="step-trigger"
           aria-selected="false">
            <span class="bs-stepper-circle"><i class="ti ti-building-bank"></i></span>
            <span class="bs-stepper-label">
              <span class="bs-stepper-title">Bank Details</span>
              <span class="bs-stepper-subtitle">Add Bank Details</span>
            </span>
        </a>
    </div>
    <div class="step flex-grow-1 @if(Route::is('vendor-branches.branch.document*')) active @endif"
         data-target="#social-links-modern">
        <a href="{{route('vendor-branches.branch.document',$branch)}}" type="button" class="step-trigger"
           aria-selected="false">
            <span class="bs-stepper-circle"><i class="ti ti-file-barcode"></i></span>
            <span class="bs-stepper-label">
              <span class="bs-stepper-title">Branch Document</span>
              <span class="bs-stepper-subtitle">Upload Branch Document</span>
            </span>
        </a>
    </div>
</div>
