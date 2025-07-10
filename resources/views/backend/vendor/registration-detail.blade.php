@extends('backend.layouts.app')
@section('title')
    Registration Details
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
            @endif
        </div>
        <div class="col-12 mb-4">
            <div class="bs-stepper wizard-numbered mt-2">
                @include('backend.vendor.partial.header')
                <div class="bs-stepper-content">
                    <div class="row text-nowrap">
                        <div class="col-12 mb-4">
                            <div class="info-container">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title mb-0"></h5>
                                    @if($vendor->status != 'active')
                                        <a href="javascript:;" class="me-3 waves-effect waves-light" onclick="showRegistrationFormModal()">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">PAN Account No:</span>
                                                <span>{{$vendor->pan_account_no}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">GST No:</span>
                                                <span>{{$vendor->gst_no}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Attachment:</span>
                                                <a href="{{$vendor->image_path}}" download>{{$vendor->gst_attachment}}</a>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">PF No:</span>
                                                <span>{{$vendor->pf_no}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">ESIC No:</span>
                                                <span>{{$vendor->esic_no}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Digital Signature:</span>
                                                <span>{{$vendor->digital_signature}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">MSME Registered:</span>
                                                <span>{{$vendor->msme_registered}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">MSME No:</span>
                                                <span>{{$vendor->msme_no}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Form Of MSME:</span>
                                                <span>{{$vendor->form_of_msme}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between">
                            <a href="{{route('vendor.vendor.detail')}}" class="btn btn-submit btn-prev waves-effect">
                                <i class="ti ti-arrow-left me-sm-1 me-0"></i>
                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                            </a>
                            <a href="{{route('vendor.bank.detail')}}" class="btn btn-submit btn-next waves-effect waves-light">
                                <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

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
                    <form action="{{route('vendor.registration.detail.store',$vendor)}}" method="post"
                          id="registrationDetailForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="vendor_type" value="{{strtolower($vendor->vendorType->name)}}">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label for="pan_account_no" class="form-label">PAN Account No<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pan_account_no" id="pan_account_no"
                                       value="{{$vendor->pan_account_no}}" placeholder="Enter PAN Account No">
                            </div>
                            <div class="col-6 mb-2">
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
                            <div class="col-6 mb-2 gst-fields d-none">
                                <label for="gst_no" class="form-label">GST No<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="gst_no" id="gst_no"
                                       value="{{$vendor->gst_no}}" placeholder="Enter GST No">
                            </div>
                            <div class="col-6 mb-2 attachment-fields d-none">
                                <label for="attachment" class="form-label">Attachment<span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="attachment" id="attachment">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="pf_no" class="form-label">PF No</label>
                                <input type="text" class="form-control" value="{{$vendor->pf_no}}" name="pf_no"
                                       id="pf_no"
                                       placeholder="Enter PF No">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="esic_no" class="form-label">ESIC No</label>
                                <input type="text" class="form-control" name="esic_no" id="esic_no"
                                       value="{{$vendor->esic_no}}" placeholder="Enter ESIC No">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="digital_signature" class="form-label">Digital Signature<span
                                        class="text-danger">*</span></label>
                                <select id="digital_signature" name="digital_signature"
                                        class="form-control select2">
                                    <option selected disabled value="">Select Digital Signature</option>
                                    <option @if($vendor->digital_signature == 'yes') selected @endif value="yes">Yes
                                    </option>
                                    <option @if($vendor->digital_signature == 'no') selected @endif value="no">No
                                    </option>
                                </select>
                            </div>
                            <div class="col-6 mb-2">
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
                            <div class="col-6 mb-2 msme-fields d-none">
                                <label for="msme_no" class="form-label">MSME No</label>
                                <input type="text" class="form-control" name="msme_no"
                                       id="msme_no" placeholder="Enter MSME No"
                                       value="{{$vendor->msme_no}}">
                            </div>
                            <div class="col-6 mb-2 msme-fields d-none">
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

@endsection
@push('scripts')
    <script>
        function showRegistrationFormModal() {
            $('#registrationDetailFormModal').modal('show');

            let inputInvalid = $('#registrationDetailForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#registrationDetailFormModalTitle').text('Edit Registration Detail');
        }

        let MSMeRegistered = "{{$vendor->msme_registered}}";
        showHideMsmeFields(MSMeRegistered);

        $('#MSME_registered').on('change', function () {
            showHideMsmeFields($(this).val());
        });

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
    </script>
@endpush
