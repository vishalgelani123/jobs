@extends('backend.layouts.app')
@section('title')
    Jobs
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
                        <h5 class="card-title mb-0">Jobs</h5>
                    </div>
                    @if(\Illuminate\Support\Facades\Auth::user()->role!="candidate")
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
                        @endif
                </div>
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="applyFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="applyFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="applyFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="applyForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="job_id" id="apply_job_id">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="cover_letter" class="form-label">Cover Letter<span
                                            class="text-danger">*</span></label>
                                <textarea class="form-control" name="cover_letter" id="cover_letter"
                                          placeholder="Enter Cover Letter"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick="submitApplyForm()">Save
                    </button>
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
                                <label for="name" class="form-label">Title<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Enter Title">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="branch_name" class="form-label">Description<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" id="description"
                                          placeholder="Enter Description"></textarea>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="designation" class="form-label">Company Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="company" id="company"
                                       placeholder="Enter Company Name">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="email" class="form-label">Location<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="location" id="location"
                                       placeholder="Enter Location">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="mobile" class="form-label">Salary<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="salary" id="salary"
                                       placeholder="Enter Salary">
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

        function showApplyFormModal(id) {
            $('#applyFormModal').modal('show');
            $("#apply_job_id").val(id)
            let inputInvalid = $('#userForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#applyFormModalTitle').text('Add New');
            // $('#cover_letter').addClass('d-none');
            $('#applyForm').attr('action', '{{route('jobs.apply')}}');
        }
        function submitApplyForm() {
            let url = $('#applyForm').attr('action');
            let formData = new FormData($('#applyForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#applyFormModal').modal('hide');
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
                    $('#users-table').DataTable().ajax.reload(null, false);
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#applyForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#applyForm').find('[name="' + field + '"]');
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

        function showFormModal(id = '') {
            $('#userFormModal').modal('show');


            let inputInvalid = $('#userForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#userFormModalTitle').text('Add New');
            $('#password_blank_message').addClass('d-none');
            $('#userForm').attr('action', '{{route('jobs.store')}}');

            if (id != '') {
                $('#userFormModalTitle').text('Update');
                $('#password_blank_message').removeClass('d-none');

                $.ajax({
                    type: 'post',
                    url: '{{route('jobs.edit')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#userForm').attr('action', '{{route('jobs.update','id')}}'.replace('id', response.data.id));

                            $('input[name="title"]').val(response.data.title);
                            $('#description').val(response.data.description);
                            $('input[name="company"]').val(response.data.company);
                            $('input[name="location"]').val(response.data.location);
                            $('input[name="salary"]').val(response.data.salary);
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
                    $('#users-table').DataTable().ajax.reload(null, false);
                    /*setTimeout(function () {
                        location.reload();
                    }, 1000);*/
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
