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

        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Branch Details</h5>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons">
                            <a href="{{route('vendor-branches.create')}}" class="dt-button create-new btn btn-success">
                               <span>
                                  <i class="ti ti-plus me-sm-1"></i>
                                  <span class="d-none d-sm-inline-block">Add New</span>
                               </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="statusFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="statusFormModalTitle">Status Update</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('vendors.status.update')}}" method="post"
                          id="statusForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="vendor_id" name="vendor_id">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio1"
                                           value="partially_active">
                                    <label class="form-check-label" for="inlineRadio1">Partially Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio2"
                                           value="fully_active">
                                    <label class="form-check-label" for="inlineRadio2">Fully Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio3"
                                           value="active">
                                    <label class="form-check-label" for="inlineRadio3">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio4"
                                           value="blocked">
                                    <label class="form-check-label" for="inlineRadio4">Blocked</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick="submitStatusForm()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        function showStatusFormModal(id) {
            $('#statusFormModal').modal('show');
            $('#vendor_id').val(id);

            $.ajax({
                type: 'post',
                url: '{{route('vendors.status.edit')}}',
                data: {
                    id: id,
                    _token: '{{csrf_token()}}',
                },
                success: function (response) {
                    if (response.status == true) {
                        let statusValue = response.data.status;
                        $('input[name="status"][value="' + statusValue + '"]').prop('checked', true);
                    }
                }
            });
        }


        function submitStatusForm() {
            let url = $('#statusForm').attr('action');
            let formData = new FormData($('#statusForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
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
                    $('#vendors-table').DataTable().draw();
                },
            });
        }
    </script>
@endpush
