@extends('backend.layouts.app')
@section('title')
    Vendor Details
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
        </div>
        <div class="col-12 mb-4">
            <div class="bs-stepper wizard-numbered mt-2">
                @include('backend.vendor.partial.header')
                <div class="bs-stepper-content">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="info-container">
                                <div class="d-flex justify-content-between">
                                    <h6 class="card-title mb-0 fw-bold">Vendor Details</h6>
                                    @if($vendor->status != 'active')
                                        <a href="javascript:;" class="me-3 waves-effect waves-light" onclick="showVendorFormModal()">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mt-4">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Vendor Type:</span>
                                                <span>{{ isset($vendor->vendorType->name) ? $vendor->vendorType->name : '' }}</span>
                                            </li>
                                            @php
                                                $categories = [];
                                                foreach ($vendorItems as $vendorItem){
                                                    if(isset($vendorItem->preVendorCategory->name)){
                                                    $categories[$vendorItem->preVendorCategory->name] =  $vendorItem->preVendorCategory->name;
                                                    }
                                                }
                                            @endphp
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Categories:</span>
                                                <span>
                                                <ul>
                                                    @foreach($categories as $category)
                                                        <li>{{ $category }}</li>
                                                    @endforeach
                                                </ul>
                                            </span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 mt-4">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Business Name:</span>
                                                <span>{{ $vendor->business_name }}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Sub Categories:</span>
                                                <span>
                                                <ul>
                                                    @foreach($vendorItems as $vendorItem)
                                                        @if(isset($vendorItem->preVendorSubCategory->name))
                                                            <li>{{ $vendorItem->preVendorSubCategory->name }}</li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <h6 class="fw-bold mt-2">Basic Details</h6>
                                <hr>

                                <div class="row">
                                    <div class="col-lg-6 mt-2">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Status:</span>
                                                <span>{!! $vendor->status_with_bg !!}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">State:</span>
                                                <span>{{ isset($vendor->state->name) ? $vendor->state->name : '' }}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Phone Number 1:</span>
                                                <span>{{ $vendor->phone_number_1 }}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Address:</span>
                                                <span>{{ $vendor->address }}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Pin Code:</span>
                                                <span>{{ $vendor->pin_code }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Email:</span>
                                                <span>{{ $vendor->email }}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">City:</span>
                                                <span>{{ isset($vendor->city->name) ? $vendor->city->name : '' }}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Phone Number 2:</span>
                                                <span>{{ $vendor->phone_number_2 }}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Fax No:</span>
                                                <span>{{ $vendor->fax_no }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <h6 class="fw-bold mt-2">Contact Person Details</h6>
                                <hr>

                                <div class="row">
                                    <div class="col-lg-6 mt-2">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Name:</span>
                                                <span>{{ $vendor->name_of_contact_person }}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Mobile Number:</span>
                                                <span>{{ $vendor->contact_person_mobile_number }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Email:</span>
                                                <span>{{ $vendor->contact_person_email }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <a href="{{ route('vendor.registration.detail') }}" class="btn btn-submit btn-next text-right waves-effect waves-light float-end">
                                <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>
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
                    <form action="{{route('vendor.vendor.detail.store',$vendor)}}" method="post"
                          id="vendorDetailForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label for="business_name" class="form-label">Business Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="business_name"
                                       id="business_name" placeholder="Enter Business Name"
                                       value="{{$vendor->business_name}}">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="email" class="form-label">Email<span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email"
                                       id="email" placeholder="Enter Email"
                                       value="{{$vendor->email}}">
                            </div>

                            <div class="col-6 mb-2">
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
                            <div class="col-6 mb-2">
                                <label for="city" class="form-label">City<span
                                        class="text-danger">*</span></label>
                                <select id="city" name="city" class="form-control select2">
                                    <option selected disabled value="">Select City</option>
                                </select>
                            </div>

                            <div class="col-6 mb-2">
                                <label for="pin_code" class="form-label">Pin Code<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pin_code"
                                       id="pin_code" placeholder="Enter Pin Code"
                                       value="{{$vendor->pin_code}}">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="phone_number_1" class="form-label">Phone Number 1<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="phone_number_1"
                                       id="phone_number_1" placeholder="Enter Phone Number 1"
                                       value="{{$vendor->phone_number_1}}">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="phone_number_2" class="form-label">Phone Number 2</label>
                                <input type="number" class="form-control" name="phone_number_2"
                                       id="phone_number_2" placeholder="Enter Phone Number 2"
                                       value="{{$vendor->phone_number_2}}">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="fax_no" class="form-label">Fax No</label>
                                <input type="text" class="form-control" name="fax_no"
                                       id="fax_no" placeholder="Enter Fax No"
                                       value="{{$vendor->fax_no}}">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="address" class="form-label">Address</label>
                                <textarea id="address" name="address" class="form-control"
                                          placeholder="Enter Address">{{$vendor->address}}</textarea>
                            </div>

                            <div class="col-6 mb-2">
                                <label for="name_of_contact_person" class="form-label">Name Of Contact
                                    Person<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name_of_contact_person"
                                       id="name_of_contact_person"
                                       placeholder="Enter Name Of Contact Person"
                                       value="{{$vendor->name_of_contact_person}}">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="contact_person_mobile_number" class="form-label">Contact Person
                                    Mobile Number<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="contact_person_mobile_number"
                                       id="contact_person_mobile_number"
                                       placeholder="Enter Contact Person Mobile Number"
                                       value="{{$vendor->contact_person_mobile_number}}">
                            </div>
                            <div class="col-6 mb-2">
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

@endsection
@push('scripts')
    <script>
        function showVendorFormModal() {
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
