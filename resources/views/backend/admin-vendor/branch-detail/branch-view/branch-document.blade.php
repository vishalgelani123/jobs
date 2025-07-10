@extends('backend.layouts.app')
@section('title')
    Branch Document
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
            @error('document')
            <div class="alert alert-danger">{{$message}}</div>
            @enderror
        </div>
        <div class="row text-nowrap">
            <div class="col-md-4">
                @include('backend.admin-vendor.branch-detail.branch-view.partial.sidebar')
            </div>

            <div class="col-md-8">
                @include('backend.admin-vendor.branch-detail.branch-view.partial.header')
                <div class="row text-nowrap">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="info-container">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-0">Branch Documents</h5>
                                    </div>
                                    <p class="text-danger d-block">
                                        Note : Submit button only press when you upload document.<br>
                                        For status change you just need to click on status button in action column.</p>
                                    <form method="POST"
                                          action="{{ route('branches.branch.document.store',[$vendor,$branch]) }}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row mt-4">
                                                    <div class="col-md-12 table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="text-center">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Vendor Doc</th>
                                                                <th>Add/Edit</th>
                                                                <th>Uploaded</th>
                                                                <th>Status</th>
                                                                @if(Auth::user()->hasRole('admin'))
                                                                    <th>Action</th>
                                                                @endif
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @php $approveStatusCounter = 0; @endphp
                                                            @foreach($vendorDocDetails as $key => $vendorDocDetail)
                                                                <tr>
                                                                    <td class="text-center">{{ $key+1 }}</td>
                                                                    <td class="text-center">{{isset($vendorDocDetail->vendorDocType->name) ? $vendorDocDetail->vendorDocType->name : ''}}</td>
                                                                    <input type="hidden" name="vendor_doc_type[]"
                                                                           value="{{$vendorDocDetail->vendor_doc_type_id}}">
                                                                    <td>
                                                                        @if(array_key_exists($vendorDocDetail->vendor_doc_type_id,$uploadedVendorDocsStatusArr) == false || (array_key_exists($vendorDocDetail->vendor_doc_type_id,$uploadedVendorDocsStatusArr) && $uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] != "approve"))
                                                                            <input type="file" class="form-control"
                                                                                   accept=".pdf"
                                                                                   id="document"
                                                                                   name="document[{{$key}}]">
                                                                            @error("document.{$key}")
                                                                            <span
                                                                                class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">@if(array_key_exists($vendorDocDetail->vendor_doc_type_id,$uploadedVendorDocs))
                                                                            {{--<i class="ti ti-check text-success"></i><br>--}}
                                                                            <a target="_blank"
                                                                               href="{{asset('branch_documents/'.$uploadedVendorDocs[$vendorDocDetail->vendor_doc_type_id])}}"
                                                                               class="badge badge-primary">View</a>
                                                                        @else
                                                                            <i class="ti ti-x text-danger"></i>
                                                                        @endif</td>
                                                                    <td class="text-center">
                                                                        @if(array_key_exists($vendorDocDetail->vendor_doc_type_id,$uploadedVendorDocsStatusArr))
                                                                            @if($uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "pending")
                                                                                <button class="badge badge-primary">
                                                                                    Pending
                                                                                </button>
                                                                            @elseif($uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "rejected")
                                                                                <button class="badge badge-danger">
                                                                                    Rejected
                                                                                </button>
                                                                            @elseif($uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "approve")
                                                                                @php $approveStatusCounter++; @endphp
                                                                                <button class="badge badge-success">
                                                                                    Approve
                                                                                </button>
                                                                            @endif
                                                                        @else
                                                                            <button class="badge badge-primary">
                                                                                Pending
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                    @if(Auth::user()->hasRole('admin'))
                                                                        <td>
                                                                            <div class="form-check">
                                                                                <input
                                                                                    onclick="changeDocumentStatus('{{$vendorDocDetail->vendor_doc_type_id}}','pending')"
                                                                                    name="default-radio-{{$vendorDocDetail->id}}"
                                                                                    class="form-check-input"
                                                                                    type="radio" value=""
                                                                                    id="pending-{{$vendorDocDetail->id}}"
                                                                                    @if(array_key_exists($vendorDocDetail->vendor_doc_type_id,$uploadedVendorDocsStatusArr) == false || (array_key_exists($vendorDocDetail->vendor_doc_type_id,$uploadedVendorDocsStatusArr) && $uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "pending")) checked @endif>
                                                                                <label class="form-check-label"
                                                                                       for="pending-{{$vendorDocDetail->id}}">
                                                                                    Pending
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input
                                                                                    onclick="changeDocumentStatus('{{$vendorDocDetail->vendor_doc_type_id}}','rejected')"
                                                                                    name="default-radio-{{$vendorDocDetail->id}}"
                                                                                    class="form-check-input"
                                                                                    type="radio" value=""
                                                                                    id="rejected-{{$vendorDocDetail->id}}"
                                                                                    @if(array_key_exists($vendorDocDetail->vendor_doc_type_id,$uploadedVendorDocsStatusArr) && $uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "rejected") checked @endif>
                                                                                <label class="form-check-label"
                                                                                       for="rejected-{{$vendorDocDetail->id}}">
                                                                                    Rejected
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input
                                                                                    onclick="changeDocumentStatus('{{$vendorDocDetail->vendor_doc_type_id}}','approve')"
                                                                                    name="default-radio-{{$vendorDocDetail->id}}"
                                                                                    class="form-check-input"
                                                                                    type="radio" value=""
                                                                                    id="approve-{{$vendorDocDetail->id}}"
                                                                                    @if(array_key_exists($vendorDocDetail->vendor_doc_type_id,$uploadedVendorDocsStatusArr) && $uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "approve") checked @endif>
                                                                                <label class="form-check-label"
                                                                                       for="approve-{{$vendorDocDetail->id}}">
                                                                                    Approve
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                @if($approveStatusCounter != count($vendorDocDetails))
                                                    <div class="row mt-4">
                                                        <div class="col-md-12 text-right mt-4">
                                                            <button class="btn btn-submit" type="submit">Submit</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function changeDocumentStatus(vendorDocId, status) {
            $.ajax({
                type: 'post',
                url: '{{ route('branches.status.change',[$vendor,$branch]) }}',
                data: {
                    _token: '{{csrf_token()}}',
                    vendor_doc_id: vendorDocId,
                    status: status,
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
                }
            });
        }
    </script>
@endpush
