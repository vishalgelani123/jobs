@extends('backend.layouts.app')
@section('title')
    Branch Document
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
            @error('document')
            <div class="alert alert-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="col-12 mb-4">
            <div class="bs-stepper wizard-numbered mt-2">
                @include('backend.vendor-branch.partial.header')
                <div class="bs-stepper-content">
                    <div class="info-container">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title mb-0"></h5>
                        </div>
                        <form method="POST" action="{{ route('vendor-branches.branch.document.store',$branch) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <table class="table table-bordered table-responsive">
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
                                                @foreach($vendorDocDetails as $key => $vendorDocDetail)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td class="text-center">{{ isset($vendorDocDetail->vendorDocType->name) ? $vendorDocDetail->vendorDocType->name : '' }}</td>
                                                        <input type="hidden" name="vendor_doc_type[]"
                                                               value="{{ $vendorDocDetail->vendor_doc_type_id }}">
                                                        <td>
                                                            @if(!array_key_exists($vendorDocDetail->vendor_doc_type_id, $uploadedVendorDocsStatusArr) ||
                                                                (array_key_exists($vendorDocDetail->vendor_doc_type_id, $uploadedVendorDocsStatusArr) &&
                                                                $uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] != "approve"))
                                                                <input type="file" class="form-control" accept=".pdf"
                                                                       id="document" name="document[{{ $key }}]">
                                                                @error("document.{$key}")
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if(array_key_exists($vendorDocDetail->vendor_doc_type_id, $uploadedVendorDocs))
                                                                <a target="_blank"
                                                                   href="{{ asset('branch_documents/' . $uploadedVendorDocs[$vendorDocDetail->vendor_doc_type_id]) }}"
                                                                   class="badge badge-primary">View</a>
                                                            @else
                                                                <i class="ti ti-x text-danger"></i>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if(array_key_exists($vendorDocDetail->vendor_doc_type_id, $uploadedVendorDocsStatusArr))
                                                                @if($uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "pending")
                                                                    <span class="badge badge-primary">Pending</span>
                                                                @elseif($uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "rejected")
                                                                    <span class="badge badge-danger">Rejected</span>
                                                                @elseif($uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "approve")
                                                                    <span class="badge badge-success">Approve</span>
                                                                @endif
                                                            @else
                                                                <button class="badge badge-primary">Pending</button>
                                                            @endif
                                                        </td>
                                                        @if(Auth::user()->hasRole('admin'))
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                        onclick="changeDocumentStatus('{{ $vendorDocDetail->id }}','pending')"
                                                                        name="default-radio-{{ $vendorDocDetail->id }}"
                                                                        class="form-check-input" type="radio" value=""
                                                                        id="pending-{{ $vendorDocDetail->id }}"
                                                                        @if(!array_key_exists($vendorDocDetail->vendor_doc_type_id, $uploadedVendorDocsStatusArr) || (array_key_exists($vendorDocDetail->vendor_doc_type_id, $uploadedVendorDocsStatusArr) && $uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "pending")) checked @endif>
                                                                    <label class="form-check-label"
                                                                           for="pending-{{ $vendorDocDetail->id }}">Pending</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input
                                                                        onclick="changeDocumentStatus('{{ $vendorDocDetail->id }}','rejected')"
                                                                        name="default-radio-{{ $vendorDocDetail->id }}"
                                                                        class="form-check-input" type="radio" value=""
                                                                        id="rejected-{{ $vendorDocDetail->id }}"
                                                                        @if(array_key_exists($vendorDocDetail->vendor_doc_type_id, $uploadedVendorDocsStatusArr) && $uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "rejected") checked @endif>
                                                                    <label class="form-check-label"
                                                                           for="rejected-{{ $vendorDocDetail->id }}">Rejected</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input
                                                                        onclick="changeDocumentStatus('{{ $vendorDocDetail->id }}','approve')"
                                                                        name="default-radio-{{ $vendorDocDetail->id }}"
                                                                        class="form-check-input" type="radio" value=""
                                                                        id="approve-{{ $vendorDocDetail->id }}"
                                                                        @if(array_key_exists($vendorDocDetail->vendor_doc_type_id, $uploadedVendorDocsStatusArr) && $uploadedVendorDocsStatusArr[$vendorDocDetail->vendor_doc_type_id] == "approve") checked @endif>
                                                                    <label class="form-check-label"
                                                                           for="approve-{{ $vendorDocDetail->id }}">Approve</label>
                                                                </div>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-4">
                                    <a href="{{ route('vendor-branches.registration.detail', $branch) }}"
                                       class="btn btn-submit btn-prev waves-effect">
                                        <i class="ti ti-arrow-left me-sm-1 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </a>
                                    <button class="btn btn-submit waves-effect waves-light" type="submit">Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
