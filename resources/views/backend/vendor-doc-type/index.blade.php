@extends('backend.layouts.app')
@section('title')
    Documents
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
                        <h5 class="card-title mb-0">Documents</h5>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons">
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

    <div class="modal fade" id="vendorDocTypeFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="vendorDocTypeFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="vendorDocTypeFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="vendorDocTypeForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="doc_type" class="form-label">Doc Type</label>
                                <div class="row">
                                    @foreach($vendorDocTypeExtensions as $vendorDocTypeExtension)
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="doc_type[]"
                                                       id="doc_type_{{$vendorDocTypeExtension->id}}"
                                                       value="{{$vendorDocTypeExtension->id}}" checked>
                                                <label class="form-check-label"
                                                       for="doc_type_{{$vendorDocTypeExtension->id}}">
                                                    {{$vendorDocTypeExtension->name}}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-12 mb-2 text-danger" id="type_method_error"></div>
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
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        function showFormModal(id = '') {
            $('#vendorDocTypeFormModal').modal('show');

            let inputInvalid = $('#vendorDocTypeForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#vendorDocTypeFormModalTitle').text('Add New');
            $('#vendorDocTypeForm').attr('action', '{{route('vendor-doc-types.store')}}');

            if (id != '') {
                $('#vendorDocTypeFormModalTitle').text('Update');

                $.ajax({
                    type: 'post',
                    url: '{{route('vendor-doc-types.edit')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#vendorDocTypeForm').attr('action', '{{route('vendor-doc-types.update','uuid')}}'.replace('uuid', response.data.uuid));
                            $('input[name="name"]').val(response.data.name);
                            $('input[type="checkbox"][name="doc_type"]').prop('checked', false);

                            response.data.vendor_doc_sub_types.forEach(function (subType) {
                                let checkboxValue = subType.doc_type_extension_id;
                                let checkbox = $('input[type="checkbox"][name="doc_type[]"][value="' + checkboxValue + '"]');
                                checkbox.prop('checked', true);
                            });
                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function submitForm() {
            let url = $('#vendorDocTypeForm').attr('action');
            let formData = new FormData($('#vendorDocTypeForm')[0]);

            let typeFieldLength = $('input[type="checkbox"][name="doc_type[]"]:checked').length;

            $('#type_method_error').text('');
            if (typeFieldLength <= 0) {
                $('#type_method_error').text('Please select doc type');
                return;
            }

            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#vendorDocTypeFormModal').modal('hide');
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
                    $('#vendor-doc-types-table').DataTable().draw();
                    location.reload();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#vendorDocTypeForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#vendorDocTypeForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }
    </script>
@endpush
