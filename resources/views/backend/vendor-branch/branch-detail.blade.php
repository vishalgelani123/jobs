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
        <div class="col-md-12 mb-4">
            <div class="bs-stepper wizard-numbered mt-2">
                @include('backend.vendor-branch.partial.header')
                <div class="bs-stepper-content">
                    <div class="row text-nowrap">
                        <div class="col-md-12 mb-4">
                            <div class="info-container">
                                <div class="d-flex justify-content-between">
                                    <h6 class="card-title mb-0 fw-bold">Vendor Details</h6>
                                    @if($branch->status != 'active')
                                        <a href="javascript:;" class="me-3 waves-effect waves-light"
                                           onclick="showBranchFormModal()"><i class="ti ti-edit"></i></a>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-12 mt-4">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Vendor Type :</span>
                                                <span>{{isset($vendor->vendorType->name) ? $vendor->vendorType->name : ''}}</span>
                                            </li>
                                            @php
                                                $categories = [];
                                                foreach ($vendorItems as $vendorItem){
                                                    if(isset($vendorItem->preVendorCategory->name)){
                                                    $categories[$vendorItem->preVendorCategory->name] =  $vendorItem->preVendorCategory->name;
                                                    }
                                                }
                                            @endphp
                                            <li class="mb-3 d-flex">
                                                <span class="fw-medium me-2">Categories :</span>
                                                <span>
                                    <ul>
                                        @foreach($categories as $category)
                                            <li>{{$category}}</li>
                                        @endforeach
                                    </ul>
                                </span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-lg-6 col-md-12 mt-4">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Business Name :</span>
                                                <span>{{$vendor->business_name}}</span>
                                            </li>
                                            <li class="mb-3 d-flex">
                                                <span class="fw-medium me-2">Sub Categories :</span>
                                                <span>
                                    <ul>
                                        @foreach($vendorItems as $vendorItem)
                                            @if(isset($vendorItem->preVendorSubCategory->name))
                                                <li>{{$vendorItem->preVendorSubCategory->name}}</li>
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
                                    <div class="col-lg-6 col-md-12 mt-2">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Status :</span>
                                                <span>{!! $branch->status_with_bg !!}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">State :</span>
                                                <span>{{isset($branch->state->name) ? $branch->state->name : ''}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Mobile No :</span>
                                                <span>{{$branch->phone_number_1}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Address :</span>
                                                <span>{{$branch->address}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Pin Code :</span>
                                                <span>{{$branch->pin_code}}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-lg-6 col-md-12 mt-4">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Email :</span>
                                                <span>{{$branch->email}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">City :</span>
                                                <span>{{isset($branch->city->name) ? $branch->city->name : ''}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Phone Number 2 :</span>
                                                <span>{{$branch->phone_number_2}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Fax No :</span>
                                                <span>{{$branch->fax_no}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <h6 class="fw-bold mt-2">Contact Person Details</h6>
                                <hr>

                                <div class="row">
                                    <div class="col-lg-6 col-md-12 mt-2">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Name :</span>
                                                <span>{{$branch->name_of_contact_person}}</span>
                                            </li>
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Mobile Number :</span>
                                                <span>{{$branch->contact_person_mobile_number}}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-lg-6 col-md-12 mt-4">
                                        <ul class="list-unstyled">
                                            <li class="mb-3">
                                                <span class="fw-medium me-2">Email :</span>
                                                <span>{{$branch->contact_person_email}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <a href="{{route('vendor-branches.registration.detail',$branch)}}"
                               class="btn btn-submit btn-next text-right waves-effect waves-light float-end">
                                <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                                <i class="ti ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


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
                    <form action="{{route('vendor-branches.branch.detail.store',$branch)}}" method="post"
                          id="branchDetailForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="vendor_id" value="{{$vendor->id}}">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="email" class="form-label">Email<span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email"
                                       id="email" placeholder="Enter Email"
                                       value="{{$branch->email}}" disabled>
                            </div>
                            <div class="col-md-6 mb-2">
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
                            <div class="col-md-6 mb-2">
                                <label for="city" class="form-label">City<span
                                        class="text-danger">*</span></label>
                                <select id="city" name="city" class="form-control select2">
                                    <option selected disabled value="">Select City</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="pin_code" class="form-label">Pin Code</label>
                                <input type="text" class="form-control" name="pin_code"
                                       id="pin_code" placeholder="Enter Pin Code"
                                       value="{{$branch->pin_code}}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="mobile_number" class="form-label">Mobile Number<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="mobile_number"
                                       id="mobile_number" placeholder="Enter Mobile Number"
                                       value="{{$branch->phone_number_1}}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="phone_number_2" class="form-label">Phone Number 2</label>
                                <input type="number" class="form-control" name="phone_number_2"
                                       id="phone_number_2" placeholder="Enter Phone Number 2"
                                       value="{{$branch->phone_number_2}}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="fax_no" class="form-label">Fax No</label>
                                <input type="text" class="form-control" name="fax_no"
                                       id="fax_no" placeholder="Enter Fax No"
                                       value="{{$branch->fax_no}}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="address" class="form-label">Address</label>
                                <textarea id="address" name="address" class="form-control"
                                          placeholder="Enter Address">{{$branch->address}}</textarea>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="name_of_contact_person" class="form-label">Name Of Contact
                                    Person<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name_of_contact_person"
                                       id="name_of_contact_person"
                                       placeholder="Enter Name Of Contact Person"
                                       value="{{$branch->name_of_contact_person}}">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="contact_person_mobile_number" class="form-label">Contact Person
                                    Mobile Number<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="contact_person_mobile_number"
                                       id="contact_person_mobile_number"
                                       placeholder="Enter Contact Person Mobile Number"
                                       value="{{$branch->contact_person_mobile_number}}">
                            </div>
                            <div class="col-md-6 mb-2">
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

@endsection
@push('scripts')
    <script>
        function showBranchFormModal() {
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
