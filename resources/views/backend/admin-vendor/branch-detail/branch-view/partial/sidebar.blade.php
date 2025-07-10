<div class="card mb-4">
    <div class="card-body">
        <div class="customer-avatar-section">
            <div class="d-flex align-items-center flex-column">
                <img class="img-fluid my-3 avatar-initial rounded-circle bg-label-warning"
                     src="https://images.placeholders.dev/?width=110&height=110&text={{$vendor->initials_name}}"
                     height="110" width="110" alt="User avatar">
                <div class="customer-info text-center">
                    <h4 class="mb-1">{{$vendor->business_name}}</h4>
                    <h6 class="mb-1">Last Updated :
                        {{Carbon\Carbon::parse($branch->updated_at)->format('d-m-Y, h:i A')}}
                    </h6>
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

                <li class="mb-3">
                    <span class="fw-medium me-2">Status:</span>
                    <span>
                        @if(Auth::user()->hasRole('admin'))
                            <a href="javascript:;"
                               onclick="showStatusFormModal('{{$branch->id}}')">{!! $branch->status_with_bg !!}</a>
                        @else
                            {!! $branch->status_with_bg !!}
                        @endif
                    </span>
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

<div class="card mb-4">
    <div class="card-body">
        <div class="info-container">
            <div class="d-flex justify-content-between">
                <h5 class="card-title mb-0">Registration Detail</h5>
                <a href="javascript:;" class="me-3 waves-effect waves-light"
                   onclick="showRegistrationFormModal()"><i class="ti ti-edit"></i></a>
            </div>
            <hr>
            <ul class="list-unstyled">
                <li class="mb-3 mt-3">
                    <span class="fw-medium me-2">PAN No:</span>
                    <span>{{$branch->pan_account_no}}</span>
                    @if($branch->pan_no_verify == '1')
                        <button type="button"
                                class="btn btn-sm rounded-pill btn-icon me-2 btn-outline-success waves-effect text-success"
                                data-bs-toggle="tooltip" title="Verified">
                            <span class="ti ti-check" style="font-size: 1rem;"></span>
                        </button>
                    @else
                        <button type="button"
                                class="btn btn-sm rounded-pill btn-icon me-2 btn-outline-danger waves-effect text-danger"
                                data-bs-toggle="tooltip" title="UnVerified">
                            <span class="ti ti-x" style="font-size: 1rem;"></span>
                        </button>
                    @endif
                    @if($branch->pan_account_no != '' && $branch->pan_no_verify != '1')
                        <a type="button" class="btn btn-sm btn-primary waves-effect waves-light float-right"
                           id="pan-verify"
                           onclick="panCardVerify()">Verify</a>
                    @endif
                    @if($branch->pan_no_verify == '1')
                        <button type="button" class="btn btn-sm btn-primary waves-effect waves-light float-right"
                                data-bs-toggle="modal" data-bs-target="#panDetailModal">View
                        </button>
                    @endif
                </li>
                <li>
                    <span class="fw-medium me-2">GST No:</span>
                    <span>{{$branch->gst_no}}</span>
                    @if($branch->gst_no_verify == '1')
                        <button type="button" class="btn rounded-pill btn-icon btn-sm me-2 btn-outline-success waves-effect text-success" data-bs-toggle="tooltip" title="Verified">
                            <span class="ti ti-check" style="font-size: 1rem;"></span>
                        </button>
                    @else
                        <button type="button" class="btn rounded-pill btn-icon btn-sm me-2 btn-outline-danger waves-effect text-danger" data-bs-toggle="tooltip" title="UnVerified">
                            <span class="ti ti-x" style="font-size: 1rem;"></span>
                        </button>
                    @endif

                    @if($branch->gst_no != '' && $branch->gst_no_verify != '1')
                        <a type="button" class="btn btn-sm btn-primary waves-effect waves-light float-right mt-2 mt-md-0" onclick="gstNoVerify()">Verify</a>
                    @endif

                    @if($branch->gst_no_verify == '1')
                        <button type="button" class="btn btn-sm btn-primary waves-effect waves-light float-right mt-2 mt-md-0" onclick="gstDetail()">View</button>
                    @endif
                </li>
                @if($branch->gst_no_verify == '1')
                    <li class="mt-1">
                        <div class="d-flex flex-column flex-md-row">
                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light mb-2 mb-md-0 me-md-2" onclick="gstFillingDetail('current_year')">Current Year</button>
                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" onclick="gstFillingDetail('previous_year')">Previous Year</button>
                        </div>
                    </li>
                @endif
                <li class="mt-3 mb-3">
                    <span class="fw-medium me-2">Attachment:</span>
                    <a href="{{$branch->image_path}}" download>{{$branch->gst_attachment}}</a>
                </li>
                <li class="mb-3">
                    <span class="fw-medium me-2">PF No:</span>
                    <span>{{$branch->pf_no}}</span>
                </li>
                <li class="mb-3">
                    <span class="fw-medium me-2">ESIC No:</span>
                    <span>{{$branch->esic_no}}</span>
                </li>

                <li class="mb-3">
                    <span class="fw-medium me-2">Digital Signature:</span>
                    <span>{{$branch->digital_signature}}</span>
                </li>
                <li class="mb-3">
                    <span class="fw-medium me-2">MSME Registered:</span>
                    <span>{{$branch->msme_registered}}</span>
                </li>
                <li class="mb-3">
                    <span class="fw-medium me-2">MSME No:</span>
                    <span>{{$branch->msme_no}}</span>
                </li>
                <li class="mb-3">
                    <span class="fw-medium me-2">Form Of MSME:</span>
                    <span>{{$branch->form_of_msme}}</span>
                </li>
            </ul>
        </div>
    </div>
</div>


<div class="modal fade" id="registrationDetailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1"
     aria-labelledby="registrationDetailFormModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="registrationDetailFormModalTitle"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="ti ti-x close-button-icon"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{route('branches.registration.detail.store',[$vendor,$branch])}}" method="post"
                      id="registrationDetailForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="vendor_type" value="{{strtolower($vendor->vendorType->name)}}">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="pan_account_no" class="form-label">PAN Account No<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pan_account_no" id="pan_account_no"
                                   value="{{$branch->pan_account_no}}" placeholder="Enter PAN Account No">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="gst_status" class="form-label">GST Status<span
                                    class="text-danger">*</span></label>
                            <select id="gst_status" name="gst_status"
                                    class="form-control select2">
                                <option selected disabled value="">Select Status</option>
                                <option @if($branch->gst_status == 'yes') selected
                                        @endif value="yes">Yes
                                </option>
                                <option @if($branch->gst_status == 'no') selected
                                        @endif value="no">No
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2 gst-fields d-none">
                            <label for="gst_no" class="form-label">GST No<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gst_no" id="gst_no"
                                   value="{{$branch->gst_no}}" placeholder="Enter GST No">
                        </div>
                        <div class="col-md-12 mb-2 attachment-fields d-none">
                            <label for="attachment" class="form-label">Attachment<span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="attachment" id="attachment">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="pf_no" class="form-label">PF No</label>
                            <input type="text" class="form-control" value="{{$branch->pf_no}}" name="pf_no" id="pf_no"
                                   placeholder="Enter PF No">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="esic_no" class="form-label">ESIC No</label>
                            <input type="text" class="form-control" name="esic_no" id="esic_no"
                                   value="{{$branch->esic_no}}" placeholder="Enter ESIC No">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="digital_signature" class="form-label">Digital Signature</label>
                            <select id="digital_signature" name="digital_signature"
                                    class="form-control select2">
                                <option selected disabled value="">Select Digital Signature</option>
                                <option @if($branch->digital_signature == 'yes') selected @endif value="yes">Yes
                                </option>
                                <option @if($branch->digital_signature == 'no') selected @endif value="no">No</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="MSME_registered" class="form-label">MSME Registered?</label>
                            <select id="MSME_registered" name="MSME_registered"
                                    class="form-control select2">
                                <option selected disabled value="">Select MSME Registered</option>
                                <option @if($branch->msme_registered == 'yes') selected
                                        @endif value="yes">Yes
                                </option>
                                <option @if($branch->msme_registered == 'no') selected
                                        @endif value="no">No
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2 msme-fields d-none">
                            <label for="msme_no" class="form-label">MSME No</label>
                            <input type="text" class="form-control" name="msme_no"
                                   id="msme_no" placeholder="Enter MSME No"
                                   value="{{$branch->msme_no}}">
                        </div>
                        <div class="col-md-12 mb-2 msme-fields d-none">
                            <label for="form_of_msme" class="form-label">Form Of MSME</label>
                            <input type="text" class="form-control" name="form_of_msme"
                                   id="form_of_msme" placeholder="Enter Form Of MSME"
                                   value="{{$branch->form_of_msme}}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-submit" onclick="submitRegistrationForm()">Save
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="statusFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1"
     aria-labelledby="statusFormModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="statusFormModalTitle">Status Update</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="ti ti-x close-button-icon"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{route('branches.status.update',$vendor)}}" method="post"
                      id="statusForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="branch_id" name="branch_id">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio1"
                                       value="partially_active">
                                <label class="form-check-label" for="inlineRadio1">Partially Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio2"
                                       value="active">
                                <label class="form-check-label" for="inlineRadio2">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio3"
                                       value="inactive">
                                <label class="form-check-label" for="inlineRadio3">Inactive</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio4"
                                       value="block">
                                <label class="form-check-label" for="inlineRadio4">Block</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-submit" onclick="submitStatusForm()">Save
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="panVerifyFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1"
     aria-labelledby="panVerifyFormModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="panVerifyFormModalTitle">Pan Verify</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="ti ti-x close-button-icon"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{route('branches.pan.verify.store',[$vendor,$branch])}}" method="post"
                      id="panVerifyForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="pan_no" class="form-label">PAN No</label>
                            <input type="text" class="form-control" name="pan_no" id="pan_no"
                                   value="{{$branch->pan_account_no}}" readonly>
                        </div>
                        <div class="col-md-12 mb-2">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td><b>Category</b></td>
                                    <td id="pan_category"></td>
                                </tr>
                                <tr>
                                    <td><b>Name</b></td>
                                    <td id="pan_name"></td>
                                </tr>
                                <tr>
                                    <td><b>Aadhaar Linked</b></td>
                                    <td id="pan_aadhaar_linked"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                        {{--data-bs-dismiss="modal"--}} onclick="location.reload()">Close
                </button>
                <button type="button" class="btn btn-submit" onclick="submitPanVerifyForm()">Correct
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="panDetailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="panVerifyFormModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="panVerifyFormModalTitle">Pan Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="ti ti-x close-button-icon"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="pan_no" class="form-label">PAN No</label>
                        <input type="text" class="form-control" name="pan_no" id="pan_no"
                               value="{{$branch->pan_account_no}}" readonly>
                    </div>
                    <div class="col-md-12 mb-2">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td><b>Category</b></td>
                                <td>{{$branch->pan_category}}</td>
                            </tr>
                            <tr>
                                <td><b>Name</b></td>
                                <td>{{$branch->pan_name}}</td>
                            </tr>
                            <tr>
                                <td><b>Aadhaar Linked</b></td>
                                <td>{{$branch->pan_aadhaar_linked}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="panCardVerify()">Re-Verify</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="gstVerifyFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1"
     aria-labelledby="gstVerifyFormModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="gstVerifyFormModalTitle">GST Verify</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="ti ti-x close-button-icon"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{route('branches.gst.verify.store',[$vendor,$branch])}}" method="post"
                      id="gstVerifyForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="gst_no_api" class="form-label">GST No</label>
                            <input type="text" class="form-control" name="gst_no_api" id="gst_no_api"
                                   value="{{$branch->gst_no}}" readonly>
                        </div>
                        <div class="col-md-12 mb-2">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td><b>Legal Name</b></td>
                                    <td id="legal_name"></td>
                                    <td><b>Trade Name</b></td>
                                    <td id="trade_name"></td>
                                </tr>
                                <tr>
                                    <td><b>Pan</b></td>
                                    <td id="gst_pan"></td>
                                    <td><b>Constitution</b></td>
                                    <td id="constitution"></td>
                                </tr>
                                <tr>
                                    <td><b>Nature</b></td>
                                    <td id="nature"></td>
                                    <td><b>Type</b></td>
                                    <td id="gst_type"></td>
                                </tr>
                                <tr>
                                    <td><b>Registered</b></td>
                                    <td id="gst_registered"></td>
                                    <td><b>Updated</b></td>
                                    <td id="gst_updated"></td>
                                </tr>
                                <tr>
                                    <td><b>Expiry</b></td>
                                    <td id="gst_expiry"></td>
                                    <td><b>State</b></td>
                                    <td id="gst_state"></td>
                                </tr>
                                <tr>
                                    <td><b>State Code</b></td>
                                    <td id="gst_state_code"></td>
                                    <td><b>Center</b></td>
                                    <td id="gst_center"></td>
                                </tr>
                                <tr>
                                    <td><b>Center Code</b></td>
                                    <td id="gst_center_code"></td>
                                    <td><b>E Invoice Enabled</b></td>
                                    <td id="gst_einvoice_enabled"></td>
                                </tr>
                                <tr>
                                    <td><b>Active</b></td>
                                    <td id="gst_active"></td>
                                </tr>
                                <tr>
                                    <td><b>Address</b></td>
                                    <td colspan="3" id="gst_address"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-submit" onclick="submitGSTVerifyForm()">Correct
                </button>
            </div>
        </div>
    </div>
</div>
<!------------------ GST Details ------------------>
<div class="modal fade" id="gstDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1"
     aria-labelledby="gstDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="gstDetailModalTitle">GST Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="ti ti-x close-button-icon"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="gst_no_api" class="form-label"><b>GST No</b></label>
                        <input type="text" class="form-control" name="gst_no_api" id="gst_no_api"
                               value="{{$branch->gst_no}}" readonly>
                    </div>
                    <div class="col-md-12 mb-2 table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td><b>Legal Name</b></td>
                                <td id="gst_detail_legal_name"></td>
                                <td><b>Trade Name</b></td>
                                <td id="gst_detail_trade_name"></td>
                            </tr>
                            <tr>
                                <td><b>Pan</b></td>
                                <td id="gst_detail_pan"></td>
                                <td><b>Constitution</b></td>
                                <td id="gst_detail_constitution"></td>
                            </tr>
                            <tr>
                                <td><b>Nature</b></td>
                                <td id="gst_detail_nature"></td>
                                <td><b>Type</b></td>
                                <td id="gst_detail_type"></td>
                            </tr>
                            <tr>
                                <td><b>Registered</b></td>
                                <td id="gst_detail_registered"></td>
                                <td><b>Updated</b></td>
                                <td id="gst_detail_updated"></td>
                            </tr>
                            <tr>
                                <td><b>Expiry</b></td>
                                <td id="gst_detail_expiry"></td>
                                <td><b>State</b></td>
                                <td id="gst_detail_state"></td>
                            </tr>
                            <tr>
                                <td><b>State Code</b></td>
                                <td id="gst_detail_state_code"></td>
                                <td><b>Center</b></td>
                                <td id="gst_detail_center"></td>
                            </tr>
                            <tr>
                                <td><b>Center Code</b></td>
                                <td id="gst_detail_center_code"></td>
                                <td><b>E Invoice Enabled</b></td>
                                <td id="gst_detail_einvoice_enabled"></td>
                            </tr>
                            <tr>
                                <td><b>Active</b></td>
                                <td id="gst_detail_active"></td>
                            </tr>
                            <tr>
                                <td><b>Address</b></td>
                                <td colspan="3" id="gst_detail_address"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="gstNoVerify()">Re-Verify</button>
            </div>
        </div>
    </div>
</div>

<!------------------ GST Filling Details ------------------>
<div class="modal fade" id="gstFillingDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1"
     aria-labelledby="gstFillingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="gstFillingDetailModalTitle">GST Filings Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="ti ti-x close-button-icon"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="gst_no_api" class="form-label"><b>GST No</b></label>
                        <span id="gst_financial_year" class="fw-bold"></span>
                        <input type="text" class="form-control" name="gst_no_api" id="gst_no_api"
                               value="{{$branch->gst_no}}" readonly>
                    </div>
                    <div class="col-md-12 mb-2"><b>Filings</b></div>
                    <div class="col-md-12 mb-2 table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td style="padding: 0;">
                                    <table class="table table-bordered">
                                        <tbody id="gst_filings_detail"></tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        function showRegistrationFormModal(id = '') {
            $('#registrationDetailFormModal').modal('show');

            let inputInvalid = $('#registrationDetailForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#registrationDetailFormModalTitle').text('Edit Registration Detail');

            let MSMeRegistered = "{{$branch->msme_registered}}";
            showHideMsmeFields(MSMeRegistered);

            $('#MSME_registered').on('change', function () {
                showHideMsmeFields($(this).val());
            });
        }

        function showHideMsmeFields(value) {
            $('.msme-fields').addClass('d-none').removeClass('d-block');
            if (value == 'yes') {
                $('.msme-fields').addClass('d-block').removeClass('d-none');
            }
        }

        $('#gst_status').on('change', function () {
            showHideGstFields($(this).val());
        });

        showHideGstFields("{{$branch->gst_status}}");

        function showHideGstFields(value) {
            if (value == 'yes') {
                $('.gst-fields').addClass('d-block').removeClass('d-none');
                $('.attachment-fields').addClass('d-none').removeClass('d-block');
            }
            if (value == 'no') {
                $('.attachment-fields').addClass('d-block').removeClass('d-none');
                $('.gst-fields').addClass('d-none').removeClass('d-block');
            }
        }

        function submitRegistrationForm() {
            let url = $('#registrationDetailForm').attr('action');
            let formData = new FormData($('#registrationDetailForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#registrationDetailFormModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    $('#pre-vendor-categories-table').DataTable().draw();
                    location.reload();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#registrationDetailForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#registrationDetailForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        if (inputField.is('select')) {
                            inputField.next('span').after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                        } else {
                            inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                        }
                    });
                }
            });
        }

        function showStatusFormModal(id) {
            $('#statusFormModal').modal('show');
            $('#branch_id').val(id);

            $.ajax({
                type: 'post',
                url: '{{route('branches.status.edit',$vendor)}}',
                data: {
                    id: id,
                    _token: '{{csrf_token()}}',
                },
                success: function (response) {
                    if (response.status == true) {
                        let statusValue = response.data.status;
                        $('input[name="status"][value="' + statusValue + '"]').prop('checked', true);
                    }
                }
            });
        }

        function submitStatusForm() {
            let url = $('#statusForm').attr('action');
            let formData = new FormData($('#statusForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#statusFormModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    location.reload();
                },
            });
        }

        function panCardVerify() {
            $('.modal').modal('hide');
            $.ajax({
                type: 'post',
                url: '{{route('branches.pan.verify',[$vendor,$branch])}}',
                data: {
                    _token: '{{csrf_token()}}',
                },
                success: function (response) {
                    if (response.status == true) {
                        $('#panVerifyFormModal').modal('show');
                        $('#pan_category').html(response.data.pan_category);
                        $('#pan_name').html(response.data.pan_name);
                        $('#pan_aadhaar_linked').html(response.data.pan_aadhaarLinked);
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                },
            });
        }

        function submitPanVerifyForm() {
            let url = $('#panVerifyForm').attr('action');
            let formData = new FormData($('#panVerifyForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#panVerifyFormModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                },
            });
        }

        function gstNoVerify() {
            $('.modal').modal('hide');
            $.ajax({
                type: 'post',
                url: '{{route('branches.gst.no.verify',[$vendor,$branch])}}',
                data: {
                    _token: '{{csrf_token()}}',
                },
                success: function (response) {
                    if (response.status == true) {
                        let addressArr = JSON.parse(response.data.gst_addresses);

                        $('#gstVerifyFormModal').modal('show');
                        $('#legal_name').html(response.data.gst_legal_name);
                        $('#trade_name').html(response.data.gst_trade_name);
                        $('#gst_pan').html(response.data.gst_pan);
                        $('#constitution').html(response.data.gst_constitution);
                        $('#nature').html(response.data.gst_nature);
                        $('#gst_type').html(response.data.gst_type);
                        $('#gst_registered').html(response.data.gst_registered);
                        $('#gst_updated').html(response.data.gst_updated);
                        $('#gst_expiry').html(response.data.gst_expiry);
                        $('#gst_state').html(response.data.gst_state);
                        $('#gst_state_code').html(response.data.gst_state_code);
                        $('#gst_center').html(response.data.gst_center);
                        $('#gst_center_code').html(response.data.gst_center_code);
                        $('#gst_einvoice_enabled').html(response.data.gst_einvoice_enabled);
                        $('#gst_active').html(response.data.gst_active);
                        let addressHtml = '';
                        if (addressArr.length > 0) {
                            $.each(addressArr, function (index, address) {
                                addressHtml += `
                                            <div><b>Type:</b> ${address.type ?? ""}</div>
                                            <div><b>Building:</b> ${address.building ?? ""}</div>
                                            <div><b>Building Name:</b> ${address.buildingName ?? ""}</div>
                                            <div><b>Floor:</b> ${address.floor ?? ""}</div>
                                            <div><b>Street:</b> ${address.street ?? ""}</div>
                                            <div><b>Locality:</b> ${address.locality ?? ""}</div>
                                            <div><b>District:</b> ${address.district ?? ""}</div>
                                            <div><b>State:</b> ${address.state ?? ""}</div>
                                            <div><b>ZIP:</b> ${address.zip ?? ""}</div>
                                            <div><b>Nature:</b> ${address.nature ?? ""}</div>
                                            <br>
                                        `;
                            });
                            $('#gst_address').html(addressHtml);
                        }
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                },
            });
        }

        function submitGSTVerifyForm() {
            let url = $('#gstVerifyForm').attr('action');
            let formData = new FormData($('#gstVerifyForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#gstVerifyFormModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                },
            });
        }

        function gstDetail() {
            $.ajax({
                type: 'post',
                url: '{{route('branches.gst.detail',[$vendor,$branch])}}',
                data: {
                    _token: '{{csrf_token()}}',
                },
                success: function (response) {
                    if (response.status == true) {
                        let addressArr = JSON.parse(response.data.gst_addresses);

                        $('#gstDetailModal').modal('show');
                        $('#gst_detail_legal_name').text(response.data.gst_legal_name);
                        $('#gst_detail_trade_name').text(response.data.gst_trade_name);
                        $('#gst_detail_pan').text(response.data.gst_pan);
                        $('#gst_detail_constitution').text(response.data.gst_constitution);
                        $('#gst_detail_nature').text(response.data.gst_nature);
                        $('#gst_detail_type').text(response.data.gst_type);
                        $('#gst_detail_registered').text(response.data.gst_registered);
                        $('#gst_detail_updated').text(response.data.gst_updated);
                        $('#gst_detail_expiry').text(response.data.gst_expiry);
                        $('#gst_detail_state').text(response.data.gst_state);
                        $('#gst_detail_state_code').text(response.data.gst_state_code);
                        $('#gst_detail_center').text(response.data.gst_center);
                        $('#gst_detail_center_code').text(response.data.gst_center_code);
                        $('#gst_detail_einvoice_enabled').text(response.data.gst_einvoice_enabled);
                        $('#gst_detail_active').text(response.data.gst_active);

                        let addressHtml = '';
                        if (addressArr.length > 0) {
                            $.each(addressArr, function (index, address) {
                                addressHtml += `
                                            <div><b>Type:</b> ${address.type ?? ""}</div>
                                            <div><b>Building:</b> ${address.building ?? ""}</div>
                                            <div><b>Building Name:</b> ${address.buildingName ?? ""}</div>
                                            <div><b>Floor:</b> ${address.floor ?? ""}</div>
                                            <div><b>Street:</b> ${address.street ?? ""}</div>
                                            <div><b>Locality:</b> ${address.locality ?? ""}</div>
                                            <div><b>District:</b> ${address.district ?? ""}</div>
                                            <div><b>State:</b> ${address.state ?? ""}</div>
                                            <div><b>ZIP:</b> ${address.zip ?? ""}</div>
                                            <div><b>Nature:</b> ${address.nature ?? ""}</div>
                                            <br>
                                        `;
                            });
                            $('#gst_detail_address').html(addressHtml);
                        }
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                }
                ,
            });
        }

        function gstFillingDetail(yearType) {
            $.ajax({
                type: 'post',
                url: '{{route('branches.gst.detail',[$vendor,$branch])}}',
                data: {
                    _token: '{{csrf_token()}}',
                },
                success: function (response) {
                    if (response.status == true) {
                        let gstFilingsYear = response.data.gst_current_financial_year;
                        let filingsArr = JSON.parse(response.data.gst_current_financial_year_filings) ?? [];

                        if (yearType == 'previous_year') {
                            gstFilingsYear = response.data.gst_previous_financial_year;
                            filingsArr = JSON.parse(response.data.gst_previous_financial_year_filings) ?? [];
                        }

                        $('#gstFillingDetailModal').modal('show');
                        $('#gst_financial_year').text('(' + gstFilingsYear + ')');

                        let filingsHtml = '<table class="table"><tbody>';
                        if (filingsArr.length > 0) {
                            $.each(filingsArr, function (index, filings) {
                                if (index % 2 === 0) {
                                    filingsHtml += '<tr>';
                                }

                                filingsHtml += `<td>
                                                    <div><b>Mode:</b> ${filings.mode ?? ""}</div>
                                                    <div><b>Filed:</b> ${filings.filed ?? ""}</div>
                                                    <div><b>Type:</b> ${filings.type ?? ""}</div>
                                                    <div><b>Period:</b> ${filings.period ?? ""}</div>
                                                    <div><b>Ack:</b> ${filings.ack ?? ""}</div>
                                                    <div><b>Status:</b> ${filings.status ?? ""}</div>
                                                </td>`;

                                if (index % 2 === 1 || index === filingsArr.length - 1) {
                                    filingsHtml += '</tr>';
                                }
                            });
                            filingsHtml += '</tbody></table>';
                            $('#gst_filings_detail').html(filingsHtml);
                        }
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                }
                ,
            });
        }
    </script>
@endpush
