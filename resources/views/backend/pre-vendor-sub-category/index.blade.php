@extends('backend.layouts.app')
@section('title')
    Pre Vendor Sub Categories
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
                        <h5 class="card-title mb-0">Pre Vendor Sub Categories</h5>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons d-flex justify-content-center">
                            <button type="button" class="dt-button create-new btn btn-custom-primary me-2"
                                    data-bs-toggle="modal" data-bs-target="#importExcelFormModal">
                                <span>
                                   <i class="ti ti-file-import me-sm-1"></i>
                                   <span class="d-none d-sm-inline-block">Import</span>
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
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="preVendorSubCategoryFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="preVendorSubCategoryFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="preVendorSubCategoryFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="preVendorSubCategoryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="pre_vendor_category" class="form-label">Pre Vendor Category<span
                                        class="text-danger">*</span></label>
                                <select id="pre_vendor_category" name="pre_vendor_category" class="form-control">
                                    <option selected disabled value="">Select Pre Vendor Category</option>
                                    @foreach($preVendorCategories as $preVendorCategory)
                                        <option value="{{$preVendorCategory->id}}">{{$preVendorCategory->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
                            </div>
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

    <div class="modal fade" id="importExcelFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="importExcelFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="importExcelFormModalTitle">Import Sub Categories</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <a href="{{ asset('assets/sample_files/Pre-Vendor-Sub-Category-Sample-File.xlsx') }}" class="btn btn-custom-primary mr-3">Sample File</a>
                </div>
                <div class="modal-body">
                    <form method="post" id="importExcelForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="pre_vendor_categories" class="form-label">Pre Vendor Category<span
                                        class="text-danger">*</span></label>
                                <select id="pre_vendor_categories" name="pre_vendor_category"
                                        class="form-control">
                                    <option selected disabled value="">Select Pre Vendor Category</option>
                                    @foreach($preVendorCategories as $preVendorCategory)
                                        <option
                                            value="{{$preVendorCategory->id}}">{{$preVendorCategory->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="file" class="form-label">Excel File<span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="file" id="file" accept=".xlsx, .xls">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit text-capitalize"
                            onclick="submitPreVendorSubCategoryImport()">Import
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        function showFormModal(id = '') {
            $('#preVendorSubCategoryFormModal').modal('show');

            let inputInvalid = $('#preVendorSubCategoryForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#preVendorSubCategoryFormModalTitle').text('Add New');
            $('#preVendorSubCategoryForm').attr('action', '{{route('pre-vendor-sub-categories.store')}}');

            if (id != '') {
                $('#preVendorSubCategoryFormModalTitle').text('Update');

                $.ajax({
                    type: 'post',
                    url: '{{route('pre-vendor-sub-categories.edit')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#preVendorSubCategoryForm').attr('action', '{{route('pre-vendor-sub-categories.update','uuid')}}'.replace('uuid', response.data.uuid));
                            $('input[name="name"]').val(response.data.name);
                            $('select[name="pre_vendor_category"]').val(response.data.pre_vendor_category_id);
                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function submitForm() {
            let url = $('#preVendorSubCategoryForm').attr('action');
            let formData = new FormData($('#preVendorSubCategoryForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#preVendorSubCategoryFormModal').modal('hide');
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
                    $('#pre-vendor-sub-categories-table').DataTable().draw();
                    location.reload();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#preVendorSubCategoryForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#preVendorSubCategoryForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        function submitPreVendorSubCategoryImport() {
            let formData = new FormData($('#importExcelForm')[0]);
            $.ajax({
                type: 'post',
                url: '{{route('pre-vendor-sub-categories.import')}}',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#importExcelFormModal').modal('hide');
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
                    $('#term-conditions-categories-table').DataTable().draw();
                    location.reload();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#importExcelForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#importExcelForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }
    </script>
@endpush
