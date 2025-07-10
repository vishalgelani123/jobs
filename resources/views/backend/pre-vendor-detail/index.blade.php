@extends('backend.layouts.app')
@section('title')
    Invite Vendors
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

        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Invite Vendors</h5>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons">
                            <button type="button" class="dt-button create-new btn btn-custom-primary"
                                    onclick="showBulkSendFormModal()">
                                        <span>
                                            <i class="ti ti-send me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Bulk Send</span>
                                        </span>
                            </button>
                            <button type="button" class="dt-button create-new btn btn-success"
                                    onclick="showFormModal()">
                                        <span>
                                            <i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="get" action="{{route('pre-vendor-details.index')}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="category">Category</label>
                                        <select id="category" name="category"
                                                class="form-control select2">
                                            <option value="">All</option>
                                            @foreach($preVendorCategories as $preVendorCategory)
                                                <option value="{{$preVendorCategory->id}}"
                                                        @if(request()->category == $preVendorCategory->id) selected @endif >{{$preVendorCategory->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="sub_category">Sub Category</label>
                                        <select id="sub_category" name="sub_category"
                                                class="form-control select2">
                                            <option value="">All</option>
                                            @foreach($preVendorSubCategories as $preVendorSubCategory)
                                                <option value="{{$preVendorSubCategory->id}}"
                                                        @if(request()->sub_category == $preVendorSubCategory->id) selected @endif >{{$preVendorSubCategory->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status">Status</label>
                                        <select id="status" name="status"
                                                class="form-control select2">
                                            <option value="">All</option>
                                            <option value="open" @if(request()->status == 'open') selected @endif>Open
                                            </option>
                                            <option value="close" @if(request()->status == 'close') selected @endif>
                                                Close
                                            </option>
                                            <option value="lost" @if(request()->status == 'lost') selected @endif>
                                                Lost
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <button type="submit" class="btn btn-submit"><i
                                                class="ti ti-filter"></i>&nbsp;Apply
                                        </button>
                                        <a href="{{route('pre-vendor-details.index')}}"
                                           class="btn btn-danger"><i
                                                class="ti ti-reload"></i>&nbsp;Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="preVendorDetailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="preVendorDetailFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="preVendorDetailFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="preVendorDetailForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Business Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                       placeholder="Enter Business Name">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="email" class="form-label">Email<span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email"
                                       placeholder="Enter Email">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="mobile" class="form-label">Mobile<span
                                        class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="mobile" id="mobile"
                                       placeholder="Enter Mobile Number">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="pre_vendor_sub_category" class="form-label">Pre Vendor Sub Category<span
                                        class="text-danger">*</span></label>
                                <select id="pre_vendor_sub_category" name="pre_vendor_sub_category[]"
                                        class="form-control select2" multiple>
                                    @foreach($preVendorSubCategories as $preVendorSubCategory)
                                        <option value="{{$preVendorSubCategory->id}}">{{$preVendorSubCategory->name}}
                                            ({{$preVendorSubCategory->preVendorCategory->name}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="state" class="form-label">State<span
                                        class="text-danger">*</span></label>
                                <select id="state" name="state" class="form-control select2">
                                    <option selected disabled value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{$state->id}}">{{$state->name}}</option>
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
                                          placeholder="Enter Address">{{old('address')}}</textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="vendor_type" class="form-label">Vendor Type<span
                                        class="text-danger">*</span></label>
                                <select id="vendor_type" name="vendor_type" class="form-control select2">
                                    <option selected disabled value="">Select State</option>
                                    @foreach($vendorTypes as $vendorType)
                                        <option value="{{$vendorType->id}}">{{$vendorType->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if($smtpSetting != "" || $whatsAppSetting != "")
                                <div class="col-md-12 mb-2 mt-2 hide-mail">
                                    <label for="send" class="form-label">Send</label>
                                    <div class="row">
                                        @if($smtpSetting != "")
                                            <div class="col-md-2 mb-2">
                                                <div class="form-check form-check-inline">
                                                    <input name="send[]" class="form-check-input" type="checkbox"
                                                           id="mail" value="mail" checked>
                                                    <label class="form-check-label" for="mail">Email</label>
                                                </div>
                                            </div>
                                        @endif
                                        @if($whatsAppSetting != "")
                                            <div class="col-md-2 mb-2">
                                                <div class="form-check form-check-inline">
                                                    <input name="send[]" class="form-check-input" type="checkbox"
                                                           id="whatsapp" value="whatsapp" checked>
                                                    <label class="form-check-label" for="whatsapp">Whatsapp</label>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick="submitForm()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendHistoryModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-labelledby="sendHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-md">
            <div class="modal-content">
                <div class="modal-header text-capitalize">
                    <h1 class="modal-title fs-5" id="statusLogFormModalLabel">Send History</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body" id="send-history-modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-capitalize" data-bs-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-------  Send -------->
    <div class="modal fade" id="sendFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="sendFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="sendFormModalTitle">Send</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="sendForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="pre_vendor_id" name="pre_vendor">
                        <div class="row">
                            @if($smtpSetting != "")
                                <div class="col-4 mb-2">
                                    <div class="form-check form-check-inline">
                                        <input name="send[]" class="form-check-input" type="checkbox" id="mail"
                                               value="mail" checked>
                                        <label class="form-check-label" for="mail">Email</label>
                                    </div>
                                </div>
                            @endif
                            @if($whatsAppSetting != "")
                                <div class="col-4 mb-2">
                                    <div class="form-check form-check-inline">
                                        <input name="send[]" class="form-check-input" type="checkbox" id="whatsapp"
                                               value="whatsapp" checked>
                                        <label class="form-check-label" for="whatsapp">Whatsapp</label>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12 mb-2 text-danger" id="send_method_error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit text-capitalize"
                            onclick="submitSend()">Send
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-------  Bulk Send -------->
    <div class="modal fade" id="bulkSendFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-labelledby="bulkSendFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bulkSendFormModalTitle">Bulk Send</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="bulkSendForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            @if($smtpSetting != '')
                                <div class="col-4 mb-2">
                                    <div class="form-check form-check-inline">
                                        <input name="bulk_send[]" class="form-check-input" type="checkbox" id="mail"
                                               value="mail" checked>
                                        <label class="form-check-label" for="mail">Email</label>
                                    </div>
                                </div>
                            @endif
                            @if($whatsAppSetting != "")
                                <div class="col-4 mb-2">
                                    <div class="form-check form-check-inline">
                                        <input name="bulk_send[]" class="form-check-input" type="checkbox" id="whatsapp"
                                               value="whatsapp" checked>
                                        <label class="form-check-label" for="whatsapp">Whatsapp</label>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12 mb-2 text-danger" id="bulk_send_method_error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-submit text-capitalize" onclick="submitBulkSend()">Send
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-------  Status -------->
    <div class="modal fade" id="statusFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="statusFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="statusFormModalTitle">Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="statusForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="status_id" id="status_id">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio1"
                                           value="open">
                                    <label class="form-check-label" for="inlineRadio1">Open</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio2"
                                           value="close">
                                    <label class="form-check-label" for="inlineRadio2">Close</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio3"
                                           value="lost">
                                    <label class="form-check-label" for="inlineRadio3">Lost</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit text-capitalize"
                            onclick="submitStatus()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>

        let preVendorSubCategory = '';

        function showFormModal(id = '') {
            $('#pre_vendor_category').val('');
            $('#pre_vendor_sub_category').val('');
            $('#name').val('');
            $('#mobile').val('');
            $('#email').val('');
            $('#preVendorDetailFormModal').modal('show');
            let inputInvalid = $('#preVendorDetailForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#preVendorDetailFormModalTitle').text('Add New');
            $('#preVendorDetailForm').attr('action', '{{route('pre-vendor-details.store')}}');

            $('.hide-mail').addClass('d-block').removeClass('d-none');

            if (id != '') {
                $('#preVendorDetailFormModalTitle').text('Update');
                $('.hide-mail').addClass('d-none').removeClass('d-block');

                $.ajax({
                    type: 'post',
                    url: '{{route('pre-vendor-details.edit')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            preVendorSubCategory = response.data.pre_vendor_sub_category_id;
                            $('#preVendorDetailForm').attr('action', '{{route('pre-vendor-details.update','uuid')}}'.replace('uuid', response.data.uuid));
                            $('input[name="name"]').val(response.data.name);
                            $('input[name="mobile"]').val(response.data.mobile);
                            $('input[name="email"]').val(response.data.email);
                            $('select[name="pre_vendor_sub_category"]').val(response.data.pre_vendor_sub_category_id);
                            $('textarea[name="address"]').val(response.data.address);
                            $('select[name="state"]').val(response.data.state_id);
                            $("#state").trigger('change');
                            $('select[name="city"]').val(response.data.city_id);
                            $("#city").trigger('change');
                            $('select[name="vendor_type"]').val(response.data.vendor_type_id);
                            $("#vendor_type").trigger('change');
                            $("#pre_vendor_category").trigger('change');

                            setTimeout(function () {
                                if (response.data.pre_vendor_detail_items != undefined) {
                                    $.each(response.data.pre_vendor_detail_items, function (key, subCategory) {
                                        let subCategoryId = subCategory.pre_vendor_sub_category_id;
                                        if ($('#pre_vendor_sub_category option[value="' + subCategoryId + '"]').length > 0) {
                                            $('#pre_vendor_sub_category option[value="' + subCategoryId + '"]').prop('selected', true);
                                            $('#pre_vendor_sub_category option[value=""]').prop('selected', false);
                                        }
                                    });
                                }
                                $("#pre_vendor_sub_category").trigger('change');

                                let selectedCity = response.data.city.id;
                                if ($('#city option[value="' + selectedCity + '"]').length > 0) {
                                    $('#city option[value="' + selectedCity + '"]').prop('selected', true);
                                }
                                $("#city").trigger('change');
                            }, 500);

                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function submitForm() {
            let url = $('#preVendorDetailForm').attr('action');
            let formData = new FormData($('#preVendorDetailForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#preVendorDetailFormModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#preVendorDetailForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#preVendorDetailForm').find('[name="' + field + '"]');
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

        function showHistory(id) {
            $('#send-history-modal-body').empty();
            $.ajax({
                type: 'get',
                url: '{{route('pre-vendor-details.send.history')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id,
                },
                success: function (response) {
                    $('#send-history-modal-body').html(response.data);
                    $('#sendHistoryModal').modal('show');
                },
                error: function (error) {
                }
            });
        }

        function showSendFormModal(id) {
            $('#sendFormModal').modal('show');
            $('#pre_vendor_id').val(id);
        }

        function submitSend() {
            let formData = new FormData($('#sendForm')[0]);
            let sendFieldLength = $('input[type="checkbox"][name="send[]"]:checked').length;

            $('#send_method_error').text('')
            if (sendFieldLength <= 0) {
                $('#send_method_error').text('Please select send method');
                return;
            }

            $('#sendFormModal').modal('hide');

            $.ajax({
                type: 'post',
                url: '{{route('pre-vendor-details.send')}}',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#bulkSendFormModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    $('#pre-vendor-details-table').DataTable().draw();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#bulkSendForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#bulkSendForm').find('[name="' + field + '[]"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        function showBulkSendFormModal() {
            let selectedCheckboxes = [];
            $('.row-checkbox:checked').each(function () {
                selectedCheckboxes.push($(this).val());
            });

            if (selectedCheckboxes.length <= 0) {
                Swal.fire({
                    text: "Please select row",
                    icon: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }
            $('#bulkSendFormModal').modal('show');
        }

        function submitBulkSend() {
            let formData = new FormData($('#bulkSendForm')[0]);
            let selectedCheckboxes = [];
            $('.row-checkbox:checked').each(function () {
                selectedCheckboxes.push($(this).val());
            });
            formData.append('selected_rows', selectedCheckboxes);

            let bulkSendFieldLength = $('input[type="checkbox"][name="bulk_send[]"]:checked').length;

            $('#bulk_send_method_error').text('')
            if (bulkSendFieldLength <= 0) {
                $('#bulk_send_method_error').text('Please select send method');
                return;
            }

            $('#bulkSendFormModal').modal('hide');

            $.ajax({
                type: 'post',
                url: '{{route('pre-vendor-details.bulk.send')}}',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#bulkSendFormModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    $('#pre-vendor-details-table').DataTable().draw();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#bulkSendForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#bulkSendForm').find('[name="' + field + '[]"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        $('#selectAllCheckbox').on('change', function () {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
        });

        $(document).on('change', '.row-checkbox', function () {
            let allChecked = $('.row-checkbox:checked').length === $('.row-checkbox').length;
            $('#selectAllCheckbox').prop('checked', allChecked);
        });

        function statusFormModal(id, status) {
            $('#status_id').val(id)
            $(`[name="status"][value="${status}"]`).attr('checked', true)
            $('#statusFormModal').modal('show');
        }

        function submitStatus() {
            let formData = new FormData($('#statusForm')[0]);
            $.ajax({
                type: 'post',
                url: '{{route('pre-vendor-details.status')}}',
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
                    $('#pre-vendor-details-table').DataTable().draw();
                    location.reload();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#statusForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#statusForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        $('#state').change(function () {
            let stateId = $(this).val();
            fetchCities(stateId);
        });

        let selectedState = '{{old('state')}}';
        if (selectedState != '') {
            fetchCities(selectedState);
        }

        function fetchCities(stateId) {
            let selectedCity = '{{old('city')}}';
            $('#city').empty().html('<option value="" disabled>Select City</option>');
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
                        options += '<option ' + (selectedCity == city.id ? 'selected' : '') + ' value="' + city.id + '">' + city.name + '</option>';
                    });
                    $('#city').html(options);
                }
            });
        }

    </script>
@endpush
