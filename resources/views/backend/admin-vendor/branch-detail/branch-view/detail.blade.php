@extends('backend.layouts.app')
@section('title')
    Branch Details
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
        <div class="row text-nowrap">
            <div class="col-md-4">
                @include('backend.admin-vendor.branch-detail.branch-view.partial.sidebar')
            </div>

            <div class="col-md-8">
                @include('backend.admin-vendor.branch-detail.branch-view.partial.header')
                <div class="row text-nowrap">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="info-container">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-0">Branch Details</h5>
                                        <a href="javascript:;" class="me-3 waves-effect waves-light"
                                           onclick="showBranchFormModal()"><i class="ti ti-edit"></i></a>
                                    </div>
                                    <hr>
                                    <ul class="list-unstyled">
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">State:</span>
                                            <span>{{isset($branch->state->name) ? $branch->state->name : ''}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">City:</span>
                                            <span>{{isset($branch->city->name) ? $branch->city->name : ''}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Address:</span>
                                            <span>{{$branch->address}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Pin Code:</span>
                                            <span>{{$branch->pin_code}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Mobile No</span>
                                            <span>{{$branch->phone_number_1}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Phone Number 2:</span>
                                            <span>{{$branch->phone_number_2}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Fax No:</span>
                                            <span>{{$branch->fax_no}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Email:</span>
                                            <span>{{$branch->email}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Name Of Contact Person:</span>
                                            <span>{{$branch->name_of_contact_person}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Contact Person Mobile Number:</span>
                                            <span>{{$branch->contact_person_mobile_number}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Contact Person Email:</span>
                                            <span>{{$branch->contact_person_email}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row text-nowrap">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="info-container">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-0">Bank Details</h5>
                                        <a href="javascript:;" class="me-3 waves-effect waves-light"
                                           onclick="showBankFormModal()"><i class="ti ti-edit"></i></a>
                                    </div>
                                    <hr>
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
                </div>
            </div>
        </div>

        {{-----------Vendor Detail Modal---------}}
        <div class="modal fade" id="branchDetailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="branchDetailFormModalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="branchDetailFormModalTitle"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="ti ti-x close-button-icon"></i></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('branches.branch.detail.store',[$vendor,$branch])}}" method="post"
                              id="branchDetailForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="state" class="form-label">State<span
                                            class="text-danger">*</span></label>
                                    <select id="state" name="state" class="form-control select2">
                                        <option selected disabled value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}"
                                                    @if($branch->state_id == $state->id) selected @endif>{{$state->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="city" class="form-label">City<span
                                            class="text-danger">*</span></label>
                                    <select id="city" name="city" class="form-control select2">
                                        <option selected disabled value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea id="address" name="address" class="form-control"
                                              placeholder="Enter Address">{{$branch->address}}</textarea>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="pin_code" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" name="pin_code"
                                           id="pin_code" placeholder="Enter Pin Code"
                                           value="{{$branch->pin_code}}">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="mobile_no" class="form-label">Mobile No<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="mobile_no"
                                           id="mobile_no" placeholder="Enter Mobile No"
                                           value="{{$branch->phone_number_1}}">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="phone_number_2" class="form-label">Phone Number 2</label>
                                    <input type="number" class="form-control" name="phone_number_2"
                                           id="phone_number_2" placeholder="Enter Phone Number 2"
                                           value="{{$branch->phone_number_2}}">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="fax_no" class="form-label">Fax No</label>
                                    <input type="text" class="form-control" name="fax_no"
                                           id="fax_no" placeholder="Enter Fax No"
                                           value="{{$branch->fax_no}}">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="email" class="form-label">Email<span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email"
                                           id="email" placeholder="Enter Email"
                                           value="{{$branch->email}}">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="name_of_contact_person" class="form-label">Name Of Contact
                                        Person<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name_of_contact_person"
                                           id="name_of_contact_person"
                                           placeholder="Enter Name Of Contact Person"
                                           value="{{$branch->name_of_contact_person}}">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="contact_person_mobile_number" class="form-label">Contact Person
                                        Mobile Number<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="contact_person_mobile_number"
                                           id="contact_person_mobile_number"
                                           placeholder="Enter Contact Person Mobile Number"
                                           value="{{$branch->contact_person_mobile_number}}">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="contact_person_email" class="form-label">Contact Person Email<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="contact_person_email"
                                           id="contact_person_email"
                                           placeholder="Enter Contact Person Email"
                                           value="{{$branch->contact_person_email}}">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                        </button>
                        <button type="button" class="btn btn-submit" onclick="branchSubmitForm()">Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-----------Bank Detail Modal-----------}}
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
                        <form action="{{route('branches.bank.detail.store',[$vendor,$branch])}}" method="post"
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
                function showBranchFormModal(id = '') {
                    $('#branchDetailFormModal').modal('show');

                    let inputInvalid = $('#branchDetailForm').find('.is-invalid');
                    inputInvalid.removeClass('is-invalid');
                    $('.error-message').remove();

                    $('#branchDetailFormModalTitle').text('Edit Branch Detail');
                }

                function branchSubmitForm() {
                    let url = $('#branchDetailForm').attr('action');
                    let formData = new FormData($('#branchDetailForm')[0]);
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#branchDetailFormModal').modal('hide');
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
                            $('#pre-branch-categories-table').DataTable().draw();
                            location.reload();
                        },
                        error: function (error) {
                            let errors = error.responseJSON.errors;
                            $('#branchDetailForm .form-control').removeClass('is-invalid');
                            $('.error-message').remove();
                            $.each(errors, function (field, messages) {
                                let inputField = $('#branchDetailForm').find('[name="' + field + '"]');
                                inputField.addClass('is-invalid');
                                inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                            });
                        }
                    });
                }


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
                            $('#pre-branch-categories-table').DataTable().draw();
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

                setTimeout(function () {
                    let stateId = $("#state").val();
                    if (stateId) {
                        $.ajax({
                            type: 'post',
                            url: "{{route('cities')}}",
                            data: {
                                state_id: stateId,
                                _token: "{{csrf_token()}}",
                            },
                            success: function (response) {
                                let options = '<option value="" disabled>Select City</option>';
                                let selectedCity = "{{$branch->city_id}}";
                                $.each(response.data, function (key, city) {
                                    if (selectedCity == city.id) {
                                        options += '<option selected="selected" value="' + city.id + '">' + city.name + '</option>';
                                    } else {
                                        options += '<option value="' + city.id + '">' + city.name + '</option>';
                                    }
                                });
                                $('#city').html(options);
                            }
                        });
                    } else {
                        $('#city').html('<option value="" disabled>Select City</option>');
                    }
                }, 1000);

                $('#state').change(function () {
                    let stateId = $(this).val();
                    if (stateId) {
                        $.ajax({
                            type: 'post',
                            url: "{{route('cities')}}",
                            data: {
                                state_id: stateId,
                                _token: "{{csrf_token()}}",
                            },
                            success: function (response) {
                                let options = '<option value="" disabled>Select City</option>';
                                $.each(response.data, function (key, city) {
                                    options += '<option value="' + city.id + '">' + city.name + '</option>';
                                });
                                $('#city').html(options);
                            }
                        });
                    } else {
                        $('#city').html('<option value="" disabled>Select City</option>');
                    }
                });
            </script>
    @endpush
