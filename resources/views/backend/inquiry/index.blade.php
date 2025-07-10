@extends('backend.layouts.app')
@section('title')
    Inquiry
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
                        <h5 class="card-title mb-0">Inquiry</h5>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons">
                            <a href="{{route('inquiry-master.export')}}?type=pdf&{{http_build_query(request()->query())}}"
                               class="dt-button create-new btn btn-submit text-white">
                                        <span><i class="ti ti-pdf me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">PDF</span>
                                        </span>
                            </a>
                            <a href="{{route('inquiry-master.export')}}?type=excel&{{http_build_query(request()->query())}}"
                               class="dt-button create-new btn btn-submit text-white">
                                        <span><i class="ti ti-file-spreadsheet me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Excel</span>
                                        </span>
                            </a>
                            <button type="button" class="dt-button create-new btn btn-success"
                                    onclick="showFormModal()">
                                        <span><i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(Auth::user()->hasRole('admin'))
                        <form method="get" action="{{route('inquiry-master.index')}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="inquiry_date_filter">Date</label>
                                            <input type="text" class="form-control" name="inquiry_date_filter"
                                                   id="inquiry_date_filter" placeholder="Select Date Range"
                                                   value="{{ request()->inquiry_date_filter ?? '' }}">
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <label for="project_name_filter" class="form-label">Project Name</label>
                                            <select id="project_name_filter" name="project_name_filter"
                                                    class="form-control select2">
                                                <option selected disabled value="">Select Project Name</option>
                                                @foreach($projectNames as $projectName)
                                                    <option value="{{$projectName->id}}"
                                                            @if(request()->project_name_filter == $projectName->id) selected @endif>{{ucwords($projectName->name)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <label for="vendor_type_filter" class="form-label">Vendor Type</label>
                                            <select id="vendor_type_filter" name="vendor_type_filter"
                                                    class="form-control select2">
                                                <option selected disabled value="">Select Vendor Type</option>
                                                @foreach($vendorType as $type)
                                                    <option value="{{$type->id}}"
                                                            @if(request()->vendor_type_filter == $type->id) selected @endif>{{ucwords($type->name)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <label for="status_filter" class="form-label">Status</label>
                                            <select id="status_filter" name="status_filter"
                                                    class="form-control select2">
                                                <option selected disabled value="">Select Status</option>
                                                <option value="open"
                                                        @if(request()->status_filter == 'open') selected @endif>Open
                                                </option>
                                                <option value="close"
                                                        @if(request()->status_filter == 'close') selected @endif>Close
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <label for="admin_status_filter" class="form-label">Admin Status</label>
                                            <select id="admin_status_filter" name="admin_status_filter"
                                                    class="form-control select2">
                                                <option selected disabled value="">Select Admin Status</option>
                                                <option value="Approved"
                                                        @if(request()->admin_status_filter == 'Approved') selected @endif>Approved
                                                </option>
                                                <option value="Pending"
                                                        @if(request()->admin_status_filter == 'Pending') selected @endif>Pending
                                                </option>
                                                <option value="Rejected"
                                                        @if(request()->admin_status_filter == 'Rejected') selected @endif>Rejected
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-2">
                                            <label for="approver_status_filter" class="form-label">Approver Status</label>
                                            <select id="approver_status_filter" name="approver_status_filter"
                                                    class="form-control select2">
                                                <option selected disabled value="">Select Approver Status</option>
                                                <option value="approved"
                                                        @if(request()->approver_status_filter == 'approved') selected @endif>Approved
                                                </option>
                                                <option value="pending"
                                                        @if(request()->approver_status_filter == 'pending') selected @endif>Pending
                                                </option>
                                                <option value="rejected"
                                                        @if(request()->approver_status_filter == 'rejected') selected @endif>Rejected
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mt-3 text-end">
                                            <button type="submit" class="btn btn-submit"><i
                                                    class="ti ti-filter"></i>&nbsp;Apply
                                            </button>
                                            <a href="{{route('inquiry-master.index')}}"
                                               class="btn btn-danger"><i
                                                    class="ti ti-reload"></i>&nbsp;Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>

    <!------ Inquiry Form Modal ------>
    <div class="modal fade" id="inquiryFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="inquiryFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="inquiryFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="inquiryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Inquiry Date<span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="inquiry_date" id="inquiry_date"
                                       placeholder="Enter Inquiry Date">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">End Date<span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" id="end_date"
                                       placeholder="Enter End Date">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Start Time<span
                                        class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="start_time" id="start_time"
                                       placeholder="Enter Start Time">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">End Time<span
                                        class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="end_time" id="end_time"
                                       placeholder="Enter End Time">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Project Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name"
                                       placeholder="Enter Project Name">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="mobile" class="form-label">Subject</label>
                                <textarea id="remark" name="remarks" class="form-control"></textarea>

                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="type" class="form-label">Vendor Type<span
                                        class="text-danger">*</span></label>
                                <select id="vendor_type" name="vendor_type" class="form-control select2">
                                    <option selected disabled value="">Select Type</option>
                                    @foreach($vendorType as $type)
                                        <option
                                            value="{{$type->id}}">{{ucwords(str_replace("_"," ",$type->name))}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if(Auth::user()->hasRole('drafter'))
                                <div class="col-md-12 mb-2">
                                    <label for="type" class="form-label">Admin<span
                                            class="text-danger">*</span></label>
                                    <select id="admin_id" name="admin_id[]" class="form-control select2" multiple>
                                        <option value="all">All</option>
                                        @foreach($users as $user)
                                            <option
                                                value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-12 mb-2">
                                <label for="type" class="form-label">General Terms and Condition Category<span
                                        class="text-danger">*</span></label>
                                <select id="general_term_condition_categories"
                                        name="general_term_condition_categories[]" class="form-control select2"
                                        multiple>
                                    @foreach($generalTermConditions as $generalTermCondition)
                                        <option
                                            value="{{$generalTermCondition->id}}">{{$generalTermCondition->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="type" class="form-label">Category Wise T&C</label>
                                <select id="term_condition_categories"
                                        name="term_condition_categories[]" class="form-control select2" multiple>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="type" class="form-label">Terms and Condition Document</label>
                                <select id="term_condition_documents"
                                        name="term_condition_documents[]" class="form-control select2" multiple>
                                    @foreach($documents as $document)
                                        <option value="{{$document->id}}">{{$document->name}}</option>
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

    <div class="modal fade" id="fullDescriptionFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="fullDescriptionFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Subject</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-2" id="full-description-modal-body">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        function showFullDescription(description) {
            $('#fullDescriptionFormModal').modal('show');
            $('#full-description-modal-body').text(description);
        }
    </script>
    <script>
        // Register the FilePond plugins
        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize
        );

        // Manually initialize a FilePond instance
        const inputElement = document.querySelector('input[type="file"].filepond');
        const pond = FilePond.create(inputElement);

        // Configure FilePond options here if needed
        pond.setOptions({
            server: {
                process: {
                    url: '/upload',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Include CSRF token for security
                    }
                },
                revert: {
                    url: '/revert',
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Include CSRF token for security
                    }
                }
            }
        });

        $(function () {
            $('input[name="inquiry_date_filter"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY'
                }
            });

            $('input[name="inquiry_date_filter"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $('input[name="inquiry_date_filter"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });

        function changeStatus(inquiry_id, status) {
            Swal.fire({
                //title: "Are you sure?",
                text: "Do you want to change the status?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, change it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: '{{route('inquiry-master.change-inquiry-status')}}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'status': status,
                            'inquiry_id': inquiry_id,
                        },
                        success: function (response) {
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
                            }, 2000);
                        },
                        error: function (error) {
                            let errors = error.responseJSON.errors;
                            $('#inquiryForm .form-control').removeClass('is-invalid');
                            $('.error-message').remove();
                            $.each(errors, function (field, messages) {
                                let inputField = $('#inquiryForm').find('[name="' + field + '"]');
                                inputField.addClass('is-invalid');
                                inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                            });
                        }
                    });
                }
            });
        }

        function showFormModal(id = '') {
            $('#inquiryFormModal').modal('show');

            let inputInvalid = $('#inquiryForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#inquiryFormModalTitle').text('Add New');
            @if(Auth::user()->hasRole('admin'))
            $('#inquiryForm').attr('action', '{{route('inquiry-master.store')}}');
            @else
            $('#inquiryForm').attr('action', '{{route('inquiry.store')}}');
            @endif

            if (id != '') {
                $('#inquiryFormModalTitle').text('Update');
                $('#password_blank_message').removeClass('d-none');

                let route = '{{route('inquiry.edit')}}';
                @if(Auth::user()->hasRole('admin'))
                    route = '{{route('inquiry-master.edit')}}';
                @endif

                $.ajax({
                    type: 'post',
                    url: route,
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            @if(Auth::user()->hasRole('admin'))
                            $('#inquiryForm').attr('action', '{{route('inquiry-master.update','uuid')}}'.replace('uuid', response.data.uuid));
                            @else
                            $('#inquiryForm').attr('action', '{{route('inquiry.update','uuid')}}'.replace('uuid', response.data.uuid));
                            @endif

                            $('input[name="inquiry_date"]').val(response.data.inquiry_date);
                            $('input[name="end_date"]').val(response.data.end_date);
                            $('input[name="start_time"]').val(response.data.start_time);
                            $('input[name="end_time"]').val(response.data.end_time);
                            $('input[name="name"]').val(response.data.name);
                            $('#vendor_type').val(response.data.vendor_type).trigger('change');
                            $('#admin_id').val(response.admin).trigger('change');
                            $('#remark').text(response.data.remarks);

                            let categories = JSON.parse(response.data.general_term_condition_categories);
                            $('#general_term_condition_categories').val(categories).trigger('change');

                            let termConditionDocuments = JSON.parse(response.data.term_condition_documents_id);
                            $('#term_condition_documents').val(termConditionDocuments).trigger('change');

                            setTimeout(function () {
                                let termConditionCategories = JSON.parse(response.data.term_condition_categories_id || '[]');
                                $.each(termConditionCategories, function (key, termConditionCategoryId) {
                                    if ($('#term_condition_categories option[value="' + termConditionCategoryId + '"]').length > 0) {
                                        $('#term_condition_categories option[value="' + termConditionCategoryId + '"]').prop('selected', true);
                                        $('#term_condition_categories option[value=""]').prop('selected', false);
                                    }
                                });
                                $("#term_condition_categories").trigger('change');
                            }, 1000);
                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function submitForm() {
            let url = $('#inquiryForm').attr('action');
            let formData = new FormData($('#inquiryForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#inquiryFormModal').modal('hide');
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
                    }, 2000);
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#inquiryForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#inquiryForm').find('[name="' + field + '"]');
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

        $(".select2").select2();

        $("#vendor_type").change(function () {
            let selectedVendorTypeId = $(this).val();
            if (selectedVendorTypeId != "") {
                $.ajax({
                    url: "{{route('inquiry-master.fetch.term.condition.category')}}",
                    method: 'POST',
                    data: {
                        id: selectedVendorTypeId,
                        _token: "{{csrf_token()}}",
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#term_condition_categories').empty();
                            $.each(response.data, function (index, category) {
                                $('#term_condition_categories').append($('<option>', {
                                    value: category.id,
                                    text: category.name
                                }));
                            });
                        }
                    },
                });
            }
        });

    </script>
@endpush
