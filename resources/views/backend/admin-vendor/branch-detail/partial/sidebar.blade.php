<div class="card mb-4">
    <div class="card-body">
        <div class="customer-avatar-section">
            <div class="d-flex align-items-center flex-column">
                <img class="img-fluid my-3 avatar-initial rounded-circle bg-label-warning"
                     src="https://images.placeholders.dev/?width=110&height=110&text={{$vendor->initials_name}}"
                     height="110" width="110" alt="User avatar">
                <div class="customer-info text-center">
                    <h4 class="mb-1">{{$vendor->business_name}}</h4>
                </div>
            </div>
            <hr>
        </div>
        <div class="info-container">
            <ul class="list-unstyled">
                <li class="mb-3 mt-3">
                    <span class="fw-medium me-2">Business Name:</span>
                    <span>{{$vendor->business_name}}</span>
                </li>
                <li class="mb-3">
                    <span class="fw-medium me-2">Vendor Type:</span>
                    <span>{{isset($vendor->vendorType->name) ? $vendor->vendorType->name : ''}}</span>
                </li>
                @php
                    $subCategory = [];
                    foreach ($vendorItems as $vendorItem){
                        $subCategory[] =  $vendorItem->preVendorSubCategory->name. ' ('. $vendorItem->preVendorCategory->name.')';
                    }
                @endphp
                <li class="mb-3">
                    <span class="fw-medium me-2">Pre Vendor Sub Category:</span>
                    <span
                        style="white-space: pre-wrap; !important;">{{ implode(', ', array_map(function($item) { return str_replace("_", " ", $item); }, $subCategory)) }}</span>
                </li>
            </ul>
        </div>
    </div>
</div>

