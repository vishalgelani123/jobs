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
                        {{Carbon\Carbon::parse($vendor->updated_at)->format('d-m-Y, h:i A')}}
                    </h6>
                </div>
            </div>
            <hr>
        </div>
        <div class="info-container">
            <ul class="list-unstyled">
                <a href="javascript:;" class="me-3 waves-effect waves-light float-end"
                   onclick="showVendorFormModal()"><i class="ti ti-edit"></i></a>
                <li class="mb-3">
                    <span class="fw-medium me-2">Vendor Type:</span>
                    <span>{{isset($vendor->vendorType->name) ? $vendor->vendorType->name : ''}}</span>
                </li>
                <li class="mb-3 mt-3">
                    <span class="fw-medium me-2">Business Name:</span>
                    <span>{{$vendor->business_name}}</span>
                </li>
                <li class="mb-3">
                    <span class="fw-medium me-2">Email:</span>
                    <span>{{$vendor->email}}</span>
                </li>
                @php
                    $user = \App\Models\User::find($vendor->user_id);
                @endphp
                <li class="mb-3">
                    <span class="fw-medium me-2">Mobile Number:</span>
                    <span>{{$vendor->phone_number_1}} (OTP : {{isset($user->otp)?$user->otp:''}})</span>
                </li>
                <li class="mb-3">
                    <span class="fw-medium me-2">Password:</span>
                    <a href="javascript:;" class="badge badge-primary"
                       onclick="updatePassword()">Update Password</a>
                </li>
                <li class="mb-3">
                    <span class="fw-medium me-2">Status:</span>
                    <span>
                        @if(Auth::user()->hasRole('admin'))
                            <a href="javascript:;"
                               onclick="showStatusFormModal('{{$vendor->id}}')">{!! $vendor->status_with_bg !!}</a>
                        @else
                            {!! $vendor->status_with_bg !!}
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
                <form action="{{route('vendors.registration.detail.store',$vendor)}}" method="post"
                      id="registrationDetailForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="vendor_type" value="{{strtolower($vendor->vendorType->name)}}">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="pan_account_no" class="form-label">PAN Account No<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pan_account_no" id="pan_account_no"
                                   value="{{$vendor->pan_account_no}}" placeholder="Enter PAN Account No">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="gst_status" class="form-label">GST Status<span
                                    class="text-danger">*</span></label>
                            <select id="gst_status" name="gst_status"
                                    class="form-control select2">
                                <option selected disabled value="">Select Status</option>
                                <option @if($vendor->gst_status == 'yes') selected
                                        @endif value="yes">Yes
                                </option>
                                <option @if($vendor->gst_status == 'no') selected
                                        @endif value="no">No
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2 gst-fields d-none">
                            <label for="gst_no" class="form-label">GST No<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gst_no" id="gst_no"
                                   value="{{$vendor->gst_no}}" placeholder="Enter GST No">
                        </div>
                        <div class="col-md-12 mb-2 attachment-fields d-none">
                            <label for="attachment" class="form-label">Attachment<span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="attachment" id="attachment">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="pf_no" class="form-label">PF No</label>
                            <input type="text" class="form-control" value="{{$vendor->pf_no}}" name="pf_no" id="pf_no"
                                   placeholder="Enter PF No">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="esic_no" class="form-label">ESIC No</label>
                            <input type="text" class="form-control" name="esic_no" id="esic_no"
                                   value="{{$vendor->esic_no}}" placeholder="Enter ESIC No">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="digital_signature" class="form-label">Digital Signature<span
                                    class="text-danger">*</span></label>
                            <select id="digital_signature" name="digital_signature"
                                    class="form-control select2">
                                <option selected disabled value="">Select Digital Signature</option>
                                <option @if($vendor->digital_signature == 'yes') selected @endif value="yes">Yes
                                </option>
                                <option @if($vendor->digital_signature == 'no') selected @endif value="no">No</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="MSME_registered" class="form-label">MSME Registered?</label>
                            <select id="MSME_registered" name="MSME_registered"
                                    class="form-control select2">
                                <option selected disabled value="">Select MSME Registered</option>
                                <option @if($vendor->msme_registered == 'yes') selected
                                        @endif value="yes">Yes
                                </option>
                                <option @if($vendor->msme_registered == 'no') selected
                                        @endif value="no">No
                                </option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2 msme-fields d-none">
                            <label for="msme_no" class="form-label">MSME No</label>
                            <input type="text" class="form-control" name="msme_no"
                                   id="msme_no" placeholder="Enter MSME No"
                                   value="{{$vendor->msme_no}}">
                        </div>
                        <div class="col-md-12 mb-2 msme-fields d-none">
                            <label for="form_of_msme" class="form-label">Form Of MSME</label>
                            <input type="text" class="form-control" name="form_of_msme"
                                   id="form_of_msme" placeholder="Enter Form Of MSME"
                                   value="{{$vendor->form_of_msme}}">
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
                <form action="{{route('vendors.status.update')}}" method="post"
                      id="statusForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="vendor_id" name="vendor_id">
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
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio5"
                                       value="pending">
                                <label class="form-check-label" for="inlineRadio5">Pending</label>
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

<div class="modal fade" id="vendorDetailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1"
     aria-labelledby="vendorDetailFormModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="vendorDetailFormModalTitle"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="ti ti-x close-button-icon"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{route('vendors.vendor.detail.store',$vendor)}}" method="post"
                      id="vendorDetailForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="vendor_type" class="form-label">Vendor Type<span
                                    class="text-danger">*</span></label>
                            <select id="vendor_type" name="vendor_type" class="form-control select2">
                                <option selected disabled value="">Select Vendor Type</option>
                                @foreach($vendorTypes as $vendorType)
                                    <option value="{{$vendorType->id}}"
                                            @if($vendor->vendor_type_id == $vendorType->id) selected @endif>{{$vendorType->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="pre_vendor_sub_category" class="form-label">Pre Vendor Sub Category<span
                                    class="text-danger">*</span></label>
                            <select id="pre_vendor_sub_category" name="pre_vendor_sub_category[]"
                                    class="form-control select2" multiple>
                                <option disabled value="">Select Pre Vendor Sub Category</option>
                                @foreach($preVendorSubCategories as $preVendorSubCategory)
                                    <option value="{{ $preVendorSubCategory->id }}"
                                            @if(in_array($preVendorSubCategory->id, $vendorItemArray)) selected @endif>
                                        {{ $preVendorSubCategory->name }}
                                        ({{ $preVendorSubCategory->preVendorCategory->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="business_name" class="form-label">Business Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="business_name"
                                   id="business_name" placeholder="Enter Business Name"
                                   value="{{$vendor->business_name}}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="email" class="form-label">Email<span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email"
                                   id="email" placeholder="Enter Email"
                                   value="{{$vendor->email}}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="mobile_number" class="form-label">Mobile Number<span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="mobile_number"
                                   id="mobile_number" placeholder="Enter Mobile Number"
                                   value="{{$vendor->phone_number_1}}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-submit" onclick="vendorSubmitForm()">Update
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updatePasswordFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1"
     aria-labelledby="updatePasswordFormModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updatePasswordFormModalTitle"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="ti ti-x close-button-icon"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{route('vendors.update.password',$vendor)}}" method="post"
                      id="updatePasswordForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-password-toggle">
                                <label for="password" class="form-label">Password<span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           aria-describedby="password"/>
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-password-toggle">
                                <label for="confirm_password" class="form-label">Confirm Password<span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="confirm_password"
                                           class="form-control @error('confirm_password') is-invalid @enderror"
                                           name="confirm_password"
                                           aria-describedby="password"/>
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2 mt-3">
                            <div class="form-check">
                                <input checked class="form-check-input" type="checkbox" id="password_mail_send"
                                       name="password_mail_send">
                                <label class="form-check-label" for="password_mail_send">
                                    Send Mail Of Updated Password
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <img src="{{asset('assets/images/loader.gif')}}" class="d-none" id="password-update-loader"
                     style="width: 25px;" alt="loader">
                <button id="password-update-btn" type="button" class="btn btn-submit"
                        onclick="updatePasswordSubmitForm()">Update
                </button>
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

            $('#registrationDetailFormModalTitle').text('Registration Detail');

            let MSMeRegistered = "{{$vendor->msme_registered}}";
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

        showHideGstFields("{{$vendor->gst_status}}");

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
            $('#vendor_id').val(id);

            $.ajax({
                type: 'post',
                url: '{{route('vendors.status.edit')}}',
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

        function showVendorFormModal(id = '') {
            $('#vendorDetailFormModal').modal('show');

            let inputInvalid = $('#vendorDetailForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#vendorDetailFormModalTitle').text('Edit Vendor Detail');
        }

        function vendorSubmitForm() {
            let url = $('#vendorDetailForm').attr('action');
            let formData = new FormData($('#vendorDetailForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#vendorDetailFormModal').modal('hide');
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
                    })
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#vendorDetailForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#vendorDetailForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        function updatePassword() {
            $('#updatePasswordFormModal').modal('show');

            let inputInvalid = $('#updatePasswordForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#updatePasswordFormModalTitle').text('Update Password');
        }

        function updatePasswordSubmitForm() {
            $('#password-update-loader').addClass('d-block').removeClass('d-none');
            $('#password-update-btn').addClass('d-none').removeClass('d-block');

            let url = $('#updatePasswordForm').attr('action');
            let formData = new FormData($('#updatePasswordForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#updatePasswordFormModal').modal('hide');
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
                error: function (error) {
                    $('#password-update-loader').addClass('d-none').removeClass('d-block');
                    $('#password-update-btn').addClass('d-block').removeClass('d-none');

                    let errors = error.responseJSON.errors;
                    $('#updatePasswordForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();

                    $.each(errors, function (field, messages) {
                        let inputField = $('#updatePasswordForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');

                        let parentCol = inputField.closest('.col-md-12');
                        let errorDiv = $('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');

                        parentCol.after(errorDiv);
                    });
                }
            });
        }
    </script>
@endpush
