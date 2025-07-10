@extends('backend.layouts.app')
@section('title')
    Vendor Details
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
        <div class="row text-nowrap">
            <div class="col-4">
                @include('backend.admin-vendor.partial.sidebar')
            </div>

            <div class="col-8">
                @include('backend.admin-vendor.partial.header')
                <div class="row text-nowrap">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="info-container">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-0">Vendor Details</h5>
                                        <a href="javascript:;" class="me-3 waves-effect waves-light"
                                           onclick="showVendorFormModal()"><i class="ti ti-edit"></i></a>
                                    </div>
                                    <hr>
                                    <ul class="list-unstyled">
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
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Business Name:</span>
                                            <span>{{$vendor->business_name}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Address:</span>
                                            <span>{{$vendor->address}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">State:</span>
                                            <span>{{isset($vendor->state->name) ? $vendor->state->name : ''}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">City:</span>
                                            <span>{{isset($vendor->city->name) ? $vendor->city->name : ''}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Pin Code:</span>
                                            <span>{{$vendor->pin_code}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Phone Number 1:</span>
                                            <span>{{$vendor->phone_number_1}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Phone Number 2:</span>
                                            <span>{{$vendor->phone_number_2}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Fax No:</span>
                                            <span>{{$vendor->fax_no}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Email:</span>
                                            <span>{{$vendor->email}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Name Of Contact Person:</span>
                                            <span>{{$vendor->name_of_contact_person}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Contact Person Mobile Number:</span>
                                            <span>{{$vendor->contact_person_mobile_number}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Contact Person Email:</span>
                                            <span>{{$vendor->contact_person_email}}</span>
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
                                            <span>{{$vendor->type_of_account}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Bank Account No:</span>
                                            <span>{{$vendor->bank_account_no}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Bank Name:</span>
                                            <span>{{$vendor->bank_name}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Payment In Favour:</span>
                                            <span>{{$vendor->payment_in_favour}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Bank Branch Name And Address:</span>
                                            <span>{{$vendor->bank_branch_name_and_address}}</span>
                                        </li>

                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Bank Branch Code:</span>
                                            <span>{{$vendor->bank_branch_code}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-medium me-2">Bank IFSC Code:</span>
                                            <span>{{$vendor->bank_ifsc_code}}</span>
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
        <div class="modal fade" id="vendorDetailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="vendorDetailFormModalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="vendorDetailFormModalTitle"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('vendors.vendor.detail.store',$vendor)}}" method="post"
                              id="vendorDetailForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-2">
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
                                <div class="col-12 mb-2">
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
                                <div class="col-12 mb-2">
                                    <label for="business_name" class="form-label">Business Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="business_name"
                                           id="business_name" placeholder="Enter Business Name"
                                           value="{{$vendor->business_name}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea id="address" name="address" class="form-control"
                                              placeholder="Enter Address">{{$vendor->address}}</textarea>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="state" class="form-label">State<span
                                            class="text-danger">*</span></label>
                                    <select id="state" name="state" class="form-control select2">
                                        <option selected disabled value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}"
                                                    @if($vendor->state_id == $state->id) selected @endif>{{$state->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="city" class="form-label">City<span
                                            class="text-danger">*</span></label>
                                    <select id="city" name="city" class="form-control select2">
                                        <option selected disabled value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="pin_code" class="form-label">Pin Code<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pin_code"
                                           id="pin_code" placeholder="Enter Pin Code"
                                           value="{{$vendor->pin_code}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="phone_number_1" class="form-label">Phone Number 1<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="phone_number_1"
                                           id="phone_number_1" placeholder="Enter Phone Number 1"
                                           value="{{$vendor->phone_number_1}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="phone_number_2" class="form-label">Phone Number 2</label>
                                    <input type="number" class="form-control" name="phone_number_2"
                                           id="phone_number_2" placeholder="Enter Phone Number 2"
                                           value="{{$vendor->phone_number_2}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="fax_no" class="form-label">Fax No</label>
                                    <input type="text" class="form-control" name="fax_no"
                                           id="fax_no" placeholder="Enter Fax No"
                                           value="{{$vendor->fax_no}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="email" class="form-label">Email<span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email"
                                           id="email" placeholder="Enter Email"
                                           value="{{$vendor->email}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="name_of_contact_person" class="form-label">Name Of Contact
                                        Person<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name_of_contact_person"
                                           id="name_of_contact_person"
                                           placeholder="Enter Name Of Contact Person"
                                           value="{{$vendor->name_of_contact_person}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="contact_person_mobile_number" class="form-label">Contact Person
                                        Mobile Number<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="contact_person_mobile_number"
                                           id="contact_person_mobile_number"
                                           placeholder="Enter Contact Person Mobile Number"
                                           value="{{$vendor->contact_person_mobile_number}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="contact_person_email" class="form-label">Contact Person Email<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="contact_person_email"
                                           id="contact_person_email"
                                           placeholder="Enter Contact Person Email"
                                           value="{{$vendor->contact_person_email}}">
                                    @error('contact_person_email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
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

        {{-----------Bank Detail Modal-----------}}
        <div class="modal fade" id="bankDetailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
             tabindex="-1"
             aria-labelledby="bankDetailFormModalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="bankDetailFormModalTitle"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('vendors.bank.detail.store',$vendor)}}" method="post"
                              id="bankDetailForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label for="type_of_account" class="form-label">Type Of Account<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="type_of_account"
                                           id="type_of_account" placeholder="Enter Type Of Account"
                                           value="{{$vendor->type_of_account}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="bank_account_no" class="form-label">Account No<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="bank_account_no"
                                           id="bank_account_no" placeholder="Enter Account No"
                                           value="{{$vendor->bank_account_no}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="bank_name" class="form-label">Bank Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="bank_name"
                                           id="bank_name" placeholder="Enter Bank Name"
                                           value="{{$vendor->bank_name}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="payment_in_favour" class="form-label">Payment In Favour</label>
                                    <input type="text" class="form-control" name="payment_in_favour"
                                           id="payment_in_favour" placeholder="Enter payment In Favor"
                                           value="{{$vendor->payment_in_favour}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="bank_branch_name_and_address" class="form-label">Branch
                                        Name And Address<span
                                            class="text-danger">*</span></label>
                                    <textarea id="bank_branch_name_and_address"
                                              name="bank_branch_name_and_address" class="form-control"
                                              placeholder="Enter Branch Name And Address">{{$vendor->bank_branch_name_and_address}}</textarea>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="bank_branch_code" class="form-label">Branch Code<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="bank_branch_code"
                                           id="bank_branch_code" placeholder="Enter Branch Code"
                                           value="{{$vendor->bank_branch_code}}">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="bank_ifsc_code" class="form-label">IFSC Code<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="bank_ifsc_code"
                                           id="bank_ifsc_code" placeholder="Enter IFSC Code"
                                           value="{{$vendor->bank_ifsc_code}}">
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
    </div>

@endsection
@push('scripts')
    <script>
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
                    $('#pre-vendor-categories-table').DataTable().draw();
                    location.reload();
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
                        let selectedCity = "{{$vendor->city_id}}";
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
