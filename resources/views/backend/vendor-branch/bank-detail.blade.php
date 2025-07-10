@extends('backend.layouts.app')
@section('title')
    Bank Details
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">{{Session::get('error')}}</div>
            @endif
        </div>
        <div class="col-md-12 mb-4">
            <div class="bs-stepper wizard-numbered mt-2">
                @include('backend.vendor-branch.partial.header')
                <div class="bs-stepper-content">
                    <div class="row text-nowrap">
                        <div class="col-md-12 mb-4">
                            <div class="info-container">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title mb-0"></h5>
                                    @if($branch->status != 'active')
                                        <a href="javascript:;" class="me-3 waves-effect waves-light" onclick="showBankFormModal()">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="row">
                                    <!-- Left column -->
                                    <div class="col-md-12 col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Type Of Account:</span>
                                                <span>{{$branch->type_of_account}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Account No:</span>
                                                <span>{{$branch->bank_account_no}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Bank Name:</span>
                                                <span>{{$branch->bank_name}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Payment In Favour:</span>
                                                <span>{{$branch->payment_in_favour}}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Right column -->
                                    <div class="col-md-12 col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Branch Name And Address:</span>
                                                <span>{{$branch->bank_branch_name_and_address}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Branch Code:</span>
                                                <span>{{$branch->bank_branch_code}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">IFSC Code:</span>
                                                <span>{{$branch->bank_ifsc_code}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation buttons -->
                        <div class="col-md-12 d-flex justify-content-between">
                            <a href="{{route('vendor-branches.registration.detail',$branch)}}" class="btn btn-submit btn-prev waves-effect">
                                <i class="ti ti-arrow-left me-sm-1 me-0"></i>
                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                            </a>
                            <a href="{{route('vendor-branches.branch.document',$branch)}}" class="btn btn-submit btn-next waves-effect waves-light">
                                <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="bankDetailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="bankDetailFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bankDetailFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('vendor-branches.bank.detail.store',$branch)}}" method="post"
                          id="bankDetailForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="type_of_account" class="form-label">Type Of Account<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="type_of_account"
                                       id="type_of_account" placeholder="Enter Type Of Account"
                                       value="{{$branch->type_of_account}}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="bank_account_no" class="form-label">Account No<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_account_no"
                                       id="bank_account_no" placeholder="Enter Account No"
                                       value="{{$branch->bank_account_no}}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="bank_name" class="form-label">Bank Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_name"
                                       id="bank_name" placeholder="Enter Bank Name"
                                       value="{{$branch->bank_name}}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="payment_in_favour" class="form-label">Payment In Favour</label>
                                <input type="text" class="form-control" name="payment_in_favour"
                                       id="payment_in_favour" placeholder="Enter payment In Favor"
                                       value="{{$branch->payment_in_favour}}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="bank_branch_name_and_address" class="form-label">Branch
                                    Name And Address<span
                                        class="text-danger">*</span></label>
                                <textarea id="bank_branch_name_and_address"
                                          name="bank_branch_name_and_address" class="form-control"
                                          placeholder="Enter Branch Name And Address">{{$branch->bank_branch_name_and_address}}</textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="bank_branch_code" class="form-label">Branch Code<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_branch_code"
                                       id="bank_branch_code" placeholder="Enter Branch Code"
                                       value="{{$branch->bank_branch_code}}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="bank_ifsc_code" class="form-label">IFSC Code<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_ifsc_code"
                                       id="bank_ifsc_code" placeholder="Enter IFSC Code"
                                       value="{{$branch->bank_ifsc_code}}">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick="bankSubmitForm()">Update
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showBankFormModal(id = '') {
            $('#bankDetailFormModal').modal('show');

            let inputInvalid = $('#bankDetailForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#bankDetailFormModalTitle').text('Edit Bank Detail');
        }

        function bankSubmitForm() {
            let url = $('#bankDetailForm').attr('action');
            let formData = new FormData($('#bankDetailForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#bankDetailFormModal').modal('hide');
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
                    $('#bankDetailForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#bankDetailForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }
    </script>
@endpush

