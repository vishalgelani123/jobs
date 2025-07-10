@extends('backend.layouts.app')
@section('title')
    Vendor Required Docs
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 mb-2">
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
                    <div class="head-label text-left text-capitalize">
                        <h5 class="card-title mb-0">Vendor Required Docs</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3"><b>Vendor Type :</b> {{$vendorType->name}}</div>
                    </div>
                    <form method="POST"
                          action="{{ route('vendor-types.vendor.doc.store',$vendorType) }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead class="text-center">
                                            <tr>
                                                <th>#</th>
                                                <th>Doc Type</th>
                                                <th>Add/Edit</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($vendorDocTypes as $key => $vendorDocType)
                                                <tr>
                                                    <td class="text-center">{{ $key+1 }}</td>
                                                    <td class="text-center">{{$vendorDocType->name}}</td>
                                                    <td class="text-center">
                                                        <label for="vendor_doc_type_{{$vendorDocType->id}}"></label>
                                                        <input type="checkbox" class="form-check-input"
                                                               id="vendor_doc_type_{{$vendorDocType->id}}"
                                                               name="vendor_doc_type[]" value="{{$vendorDocType->id }}"
                                                            {{in_array($vendorDocType->id , $preSelectedVendorDocTypes) ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 text-right mt-4">
                                        <a href="{{route('vendor-types.index')}}" class="btn btn-danger">Cancel</a>
                                        <button class="btn btn-submit" type="submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

