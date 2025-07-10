@extends('backend.layouts.app')
@section('title')
    User Management
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

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">User Management</h5>
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

    <div class="modal fade" id="userFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="userFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="userFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="userForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="branch_name" class="form-label">Branch Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="branch_name" id="branch_name"
                                       placeholder="Enter Branch Name">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="designation" class="form-label">Designation<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="designation" id="designation"
                                       placeholder="Enter Designation">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="email" class="form-label">Email<span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email"
                                       placeholder="Enter Email">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="mobile" class="form-label">Mobile<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="mobile" id="mobile"
                                       placeholder="Enter Mobile Number">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="password" class="form-label">Password<span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" id="password"
                                       placeholder="Enter Password">
                                <div class="text-danger d-none" id="password_blank_message">Leave blank if you don't
                                    want to change
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="type" class="form-label">Type<span
                                        class="text-danger">*</span></label>
                                <select id="type" name="type" class="form-control">
                                    <option selected disabled value="">Select Type</option>
                                    <option value="admin">Admin</option>
                                    <option value="drafter">Drafter</option>
                                    <option value="approver">Approver</option>
                                    <option value="approver_with_admin">Approver With Admin</option>
                                </select>
                            </div>
                            <div class="col-12 mb-2 d-none" id="admin-user">
                                <label for="admin" class="form-label">Admin<span
                                        class="text-danger">*</span></label>
                                <select id="admin" name="admin" class="form-control select2">
                                    <option selected disabled value="">select Admin</option>
                                    @foreach($admins as $admin)
                                        <option value="{{$admin->id}}">{{$admin->name}}</option>
                                    @endforeach
                                </select>
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

        $('#type').on('change', function () {
            let selectedType = $(this).val();
            $('#admin-user').removeClass('d-block').addClass('d-none')
            if (selectedType == 'drafter') {
                $('#admin-user').removeClass('d-none').addClass('d-block')
            }
        });

        function showFormModal(id = '') {
            $('#userFormModal').modal('show');

            let inputInvalid = $('#userForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#userFormModalTitle').text('Add New');
            $('#password_blank_message').addClass('d-none');
            $('#userForm').attr('action', '{{route('users.store')}}');

            if (id != '') {
                $('#userFormModalTitle').text('Update');
                $('#password_blank_message').removeClass('d-none');

                $.ajax({
                    type: 'post',
                    url: '{{route('users.edit')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#userForm').attr('action', '{{route('users.update','uuid')}}'.replace('uuid', response.data.uuid));

                            $('input[name="name"]').val(response.data.name);
                            $('input[name="branch_name"]').val(response.data.branch_name);
                            $('input[name="designation"]').val(response.data.designation);
                            $('input[name="email"]').val(response.data.email);
                            $('input[name="mobile"]').val(response.data.mobile);
                            $('select[name="type"]').val(response.data.role);
                            $('#admin-user').removeClass('d-block').addClass('d-none')
                            if (response.data.role == 'drafter') {
                                $('#admin-user').removeClass('d-none').addClass('d-block')
                            }
                            $('select[name="admin"]').val(response.data.user_id);
                            $("#admin").trigger('change');
                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function submitForm() {
            let url = $('#userForm').attr('action');
            let formData = new FormData($('#userForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#userFormModal').modal('hide');
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
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#userForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#userForm').find('[name="' + field + '"]');
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
