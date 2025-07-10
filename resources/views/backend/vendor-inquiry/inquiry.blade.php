@extends('backend.layouts.app')
@section('title')
    Vendor Inquiry
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
                                <label for="name" class="form-label">Price<span
                                        class="text-danger">*</span></label>
                                <input type="hidden" name="ipd_id" id="ipd_id" value="">
                                <input type="hidden" name="inquiry_id" id="inquiry_id" value="">
                                <input type="text" class="form-control" name="price" id="price" placeholder="Enter Price">

                            </div>
                            <div class="col-12 mb-2">
                                <label for="mobile" class="form-label">Remark</label>
                                <textarea id="remark" name="remarks" class="form-control" placeholder="Enter Remark"></textarea>

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
        function showFormModal(id,inquiryId){

            $("#ipd_id").val(id);
            $("#inquiry_id").val(inquiryId);
            $("#userFormModal").modal('show');
            $('#userForm').attr('action', '{{route('vendor-inquiry.store')}}');

        }

        function submitForm(id, inquiryId){
            var vendorPrice = $("#vendor_price_" + id).val();
            var vendorRemark = $("#vendor_description_" + id).val();
            console.log(vendorPrice, vendorRemark);

            $.ajax({
                type: 'post',
                url: '{{ route('vendor-inquiry.store') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'vendor_price': vendorPrice,
                    'vendor_remarks': vendorRemark,
                    'inquiry_id': inquiryId,
                    'product_id' : id,
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
                    $('#inquiry-vendor-table').DataTable().draw();
                    location.reload();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#userForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#userForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }
    </script>
@endpush
