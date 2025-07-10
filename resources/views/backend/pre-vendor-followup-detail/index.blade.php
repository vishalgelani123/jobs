@extends('backend.layouts.app')
@section('title')
    Pre Vendor Followup Details
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
                        <h5 class="card-title mb-0">Pre Vendor Followup Details</h5>
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
                    <div class="row mb-4">
                        <div class="col-md-3">Category
                            : {{isset($preVendorDetail->preVendorCategory->name) ? $preVendorDetail->preVendorCategory->name :''}}</div>
                        @php
                            $preVendorSubCategory = array();
                                foreach($preVendorDetail->preVendorDetailItems as $subCategory){
                                    $preVendorSubCategory[] = $subCategory->preVendorSubCategory->name;
                                }
                        @endphp
                        <div class="col-md-3">Sub Category
                            : {{implode(",",$preVendorSubCategory)}}</div>
                        <div class="col-md-6"></div>
                        <div class="col-md-3 mt-2">Name : {{$preVendorDetail->name}}</div>
                        <div class="col-md-3 mt-2">Email : {{$preVendorDetail->email}}</div>
                        <div class="col-md-3 mt-2">Mobile : {{$preVendorDetail->mobile}}</div>
                    </div>
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="preVendorFollowupDetailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="preVendorFollowupDetailFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="preVendorFollowupDetailFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="preVendorFollowupDetailForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="type" class="form-label">Type<span
                                        class="text-danger">*</span></label>
                                <select id="type" name="type"
                                        class="form-control">
                                    <option selected disabled value="">Select Type</option>
                                    <option value="email_sent">Email Sent</option>
                                    <option value="whatsapp_sent">WhatsApp Sent</option>
                                    <option value="call">Call</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="date" class="form-label">Date<span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date" id="date">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="remark" class="form-label">Remark</label>
                                <textarea id="remark" name="remark" class="form-control"
                                          placeholder="Enter Remark"></textarea>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="next_followup_date" class="form-label">Next Followup Date<span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="next_followup_date"
                                       id="next_followup_date">
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
            $('#pre_vendor_category').val('');
            $('#pre_vendor_sub_category').val('');
            $('#name').val('');
            $('#mobile').val('');
            $('#email').val('');
            $('#preVendorFollowupDetailFormModal').modal('show');
            let inputInvalid = $('#preVendorFollowupDetailForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#preVendorFollowupDetailFormModalTitle').text('Add New');
            $('#preVendorFollowupDetailForm').attr('action', '{{route('pre-vendor-followup-details.store',$preVendorDetail)}}');

            if (id != '') {
                $('#preVendorFollowupDetailFormModalTitle').text('Update');

                $.ajax({
                    type: 'post',
                    url: '{{route('pre-vendor-followup-details.edit','pre_vendor_detail')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#preVendorFollowupDetailForm').attr('action', '{{route('pre-vendor-followup-details.update',[$preVendorDetail,'uuid'])}}'.replace('uuid', response.data.uuid));
                            $('select[name="type"]').val(response.data.type);
                            $('input[name="date"]').val(response.data.date);
                            $('textarea[name="remark"]').val(response.data.remarks);
                            $('input[name="next_followup_date"]').val(response.data.next_followup_date);
                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function submitForm() {
            let url = $('#preVendorFollowupDetailForm').attr('action');
            let formData = new FormData($('#preVendorFollowupDetailForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#preVendorFollowupDetailFormModal').modal('hide');
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
                    $('#pre-vendor-followup-details-table').DataTable().draw();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#preVendorFollowupDetailForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#preVendorFollowupDetailForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

    </script>
@endpush
