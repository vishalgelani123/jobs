@extends('backend.layouts.app')
@section('title')
    General Term Condition Categories
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

        <div class="col-md-12">
            <div class="card">
                <div
                    class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-2 mb-md-0">General T&C Categories</h5>
                    </div>
                    <div class="dt-action-buttons ms-auto text-end">
                        <div class="dt-buttons">
                            <button type="button" class="dt-button create-new btn btn-custom-primary"
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

    <div class="modal fade" id="categoryFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="categoryFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="categoryFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-2">
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

    <!------- Import excel modal ------>
    <div class="modal fade" id="importExcelFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="importExcelFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="importExcelFormModalTitle">Import</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                    <a href="{{ asset('assets/sample_files/Terms-Sample-File.csv') }}"
                       class="btn btn-custom-primary mr-3">Sample File</a>
                </div>
                <div class="modal-body">
                    <form method="post" id="importExcelForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="term_condition_category" class="form-label">Term Condition Category<span
                                        class="text-danger">*</span></label>
                                <select id="term_condition_category" name="term_condition_category"
                                        class="form-control">
                                    <option selected disabled value="">Select Term Condition Category</option>
                                    @foreach($termConditionCategories as $termConditionCategory)
                                        <option
                                            value="{{$termConditionCategory->id}}">{{$termConditionCategory->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="file" class="form-label">Excel File<span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="file" id="file" accept=".csv">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit text-capitalize"
                            onclick="submitTermsImport()">Import
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
            $('#categoryFormModal').modal('show');

            let inputInvalid = $('#categoryForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#categoryFormModalTitle').text('Add New');
            $('#categoryForm').attr('action', '{{route('general-term-condition-categories.store')}}');

            if (id != '') {
                $('#categoryFormModalTitle').text('Update');

                $.ajax({
                    type: 'post',
                    url: '{{route('general-term-condition-categories.edit')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#categoryForm').attr('action', '{{route('general-term-condition-categories.update','uuid')}}'.replace('uuid', response.data.uuid));
                            $('input[name="name"]').val(response.data.name);
                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function submitForm() {
            let url = $('#categoryForm').attr('action');
            let formData = new FormData($('#categoryForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#categoryFormModal').modal('hide');
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
                    $('#categoryForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#categoryForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        function submitTermsImport() {
            let formData = new FormData($('#importExcelForm')[0]);
            $.ajax({
                type: 'post',
                url: '{{route('general-term-condition-categories.import')}}',
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
