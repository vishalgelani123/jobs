@extends('backend.layouts.app')
@section('title')
    Vendor Inquiry
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
        <div class="col-md-12 text-right">
            <a href="@if(Auth::user()->hasRole('drafter')) {{route('inquiry.detail',$inquiry)}} @else {{route('inquiry-master.detail',$inquiry)}} @endif"
               class="btn btn-danger waves-effect waves-light">Back</a>
        </div>
        <div class="col-md-12 mt-4">
            <div class="card">
                <div id="comparison_product">
                    <div
                        class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="head-label text-left mb-2 mb-md-0">
                            <h5 class="card-title mb-0">{{ $vendor->business_name }}'s Product Details</h5>
                        </div>
                        @php
                            $finalize = \App\Models\FinalizeVersion::where('inquiry_id', $inquiry->id)
                                        ->where('vendor_id', $vendor->user_id)->first();
                        @endphp
                        <div class="dt-action-buttons text-end mt-2 mt-md-0">
                            <div class="dt-buttons d-flex flex-wrap justify-content-end">
                                @if(Auth::user()->hasRole('admin'))
                                    @if($inquiry->status == 'open')
                                        @if($finalize != null)
                                            <div class="head-label text-md-right me-2 mb-2 mb-md-0">
                                                <span class="badge badge-success">Version {{ $finalize->version }} rates are Approved.</span>
                                            </div>
                                        @else
                                            <div class="head-label text-md-right me-2 mb-2 mb-md-0">
                                                <button type="button" class="btn btn-submit"
                                                        onclick="approveProduct('{{ $inquiry->id }}','{{ $vendor->user_id }}')">
                                                    Approve
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="head-label text-md-right">
                                        <a href="{{ route('inquiry-master.download-vendor-product',[$inquiry->id, $vendor->user_id]) }}"
                                           class="btn btn-primary">Download</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="inquiry-vendor-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            @php $inquiryGrandTotal = []; @endphp
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-bordered mb-3"
                                           id="inquiry-vendor-table" aria-describedby="inquiry-vendor-table_info">
                                        <thead>
                                        <tr>
                                            <th class="text-center" rowspan="2"
                                                style="width: 20%;word-break:break-word;">Sr.No
                                            </th>
                                            <th class="text-center" rowspan="2"
                                                style="width: 40%;word-break:break-word;">Item Description<br>(Additional
                                                Info)
                                            </th>
                                            <th class="text-center" rowspan="2"
                                                style="width: 20%;word-break:break-word;">Qty
                                            </th>
                                            <th class="text-center" rowspan="2"
                                                style="width: 20%;word-break:break-word;">Unit
                                            </th>
                                            <th class="text-center" colspan="2"
                                                style="width: 20%;word-break:break-word;">Budget
                                            </th>
                                            @foreach($data as $key => $vr)
                                                <th class="text-center" colspan="4"
                                                    style="width: 20%;word-break:break-word;">V{{$vr->version}}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th class="text-center">Rate</th>
                                            <th class="text-center">Amount</th>
                                            @foreach($data as $key => $vr)
                                                <th class="text-center">Rate</th>
                                                <th class="text-center">Basic Amount</th>
                                                <th class="text-center">GST</th>
                                                <th class="text-center">Amount</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        @php
                                            $versionBasicAmountTotals = []; // Initialize empty array for version totals
                                        $versionGstTotals = [];
                                        $totalGSTAmount = [];
                                        @endphp
                                        <tbody>
                                        @foreach($vendorProductDetail as $key => $details)

                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>
                                                    <b>{!! nl2br(e($details->product->item_description)) !!}</b>
                                                    <hr style="color:#007bff!important;">
                                                    <small>{!! nl2br(e($details->product->additional_info)) !!}</small>
                                                </td>
                                                <td>{{$details->product->qty}} </td>
                                                <td>{{ $details->product->unit }} </td>
                                                <td>{{$details->product->price}}</td>
                                                <td>{{$details->product->qty * $details->product->price}}</td>
                                                @foreach($data as $key => $vr)
                                                    @php
                                                        $basicAmount = $details->{'version_' . ($vr->version) . '_price'} * $details->product->qty;
                                                        $gstRate = $details->{'version_' . ($vr->version) . '_gst_rate'};
                                                        $gstAmount = $details->{'version_' . ($vr->version) . '_gst_amount'};

                                                        // Accumulate basic amount total
                                                        $versionBasicAmountTotals[$vr->version] = ($versionBasicAmountTotals[$vr->version] ?? 0) + $basicAmount;
                                                        $totalGSTAmount[$vr->version] = ($totalGSTAmount[$vr->version] ?? 0) + $details->{'version_' . ($vr->version) . '_total_with_gst'};

                                                        // Accumulate GST totals by rate and version
                                                        $versionGstTotals[$vr->version][$gstRate] = ($versionGstTotals[$vr->version][$gstRate] ?? 0) + $gstAmount;
                                                    @endphp
                                                    <td>
                                                        {{@number_format($details->{'version_' . ($vr->version) . '_price'} ,2)}}
                                                    </td>
                                                    <td>
                                                        {{@number_format(($details->{'version_' . ($vr->version) . '_price'} * $details->product->qty),2)}}
                                                    </td>
                                                    <td>
                                                        {{@number_format($details->{'version_' . ($vr->version) . '_gst_amount'},2)}}
                                                        ({{ @$details->{'version_' . ($vr->version) . '_gst_rate'} }}%)
                                                    </td>
                                                    <td>
                                                        <b>{{@number_format($details->{'version_' . ($vr->version) . '_total_with_gst'},2)}}</b>
                                                        <hr>
                                                        <small>{{ @$details->{'version_' . ($vr->version) . '_remarks'} }}</small>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total Basic Amount:</th>
                                            <th colspan="2"></th>
                                            @foreach($data as $key => $vr)
                                                <th colspan="4" class="text-center">
                                                    {{ number_format($versionBasicAmountTotals[$vr->version] ?? 0, 2) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                        @foreach([5, 12, 18, 28] as $gstRate)
                                            <tr>
                                                <th colspan="6" class="text-center"><b>GST on Material ({{ $gstRate }}%)</b></th>
                                                @foreach($data as $key => $vr)
                                                    <th colspan="4" class="text-center">
                                                        {{ number_format($versionGstTotals[$vr->version][$gstRate] ?? 0, 2) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="6" class="text-end">Total With GST:</th>
                                            @foreach($data as $key => $vr)
                                                <th colspan="4" class="text-center">
                                                    {{ number_format($totalGSTAmount[$vr->version] ?? 0, 2) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                       {{-- <tr>
                                            <th colspan="2" class="text-end">Total:</th>
                                            <th colspan="4"></th>
                                            @foreach($data as $itemKey => $vr)
                                                @php
                                                    $total = $vendorProductDetail->sum(function($details) use ($vr) {
                                                        return @$details->{'version_' . ($vr->version) . '_total_with_gst'};
                                                    });
                                                @endphp
                                                <th colspan="3"><b>{{ number_format($total,2) }}</b></th>
                                                @php $inquiryGrandTotal[$itemKey] = $total; @endphp
                                            @endforeach
                                        </tr>--}}
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-2">
            <div class="card">
                <div id="comparison_product">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">{{$vendor->business_name}}'s General Charges Details</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="inquiry-general-charges-table_wrapper"
                             class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-bordered mb-3"
                                           id="inquiry-vendor-table"
                                           aria-describedby="iinquiry-general-charges-table_info">
                                        <thead>
                                        <tr>
                                            <th class="text-center" rowspan="2"
                                                style="width: 20%;word-break:break-word;">Sr.No
                                            </th>
                                            <th class="text-center" rowspan="2"
                                                style="width: 40%;word-break:break-word;">Name
                                            </th>
                                            @foreach($generalChargesVendorVersion as $key => $generalChargesVendor)
                                                <th class="text-center" colspan="4"
                                                    style="width: 20%;word-break:break-word;">
                                                    V{{$generalChargesVendor->version}}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($generalChargesVendorVersion as $key => $generalChargesVendor)
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Rate</th>
                                                <th class="text-center">GST</th>
                                                <th class="text-center">Amount</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($inquiryGeneralCharges as $key => $inquiryGeneralCharge)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td><b>{{$inquiryGeneralCharge->generalCharge->name}}</b></td>
                                                @foreach($generalChargesVendorVersion as $key => $generalChargesVendor)
                                                    @if($inquiryGeneralCharge->status == "with_price_qty")
                                                        <td>
                                                            {{ @$inquiryGeneralCharge->{'version_' . ($generalChargesVendor->version) . '_quantity'} }}
                                                        </td>
                                                        <td>
                                                            {{ @$inquiryGeneralCharge->{'version_' . ($generalChargesVendor->version) . '_price'} }}
                                                        </td>
                                                        <td>
                                                            {{ @$inquiryGeneralCharge->{'version_' . ($generalChargesVendor->version) . '_gst_amount'} }}
                                                            ({{ @$inquiryGeneralCharge->{'version_' . ($generalChargesVendor->version) . '_gst_rate'} }}
                                                            %)
                                                        </td>
                                                        <td>
                                                            <b>
                                                                {{ @$inquiryGeneralCharge->{'version_' . ($generalChargesVendor->version) . '_total_with_gst'} }}
                                                            </b>
                                                        </td>
                                                    @else
                                                        <td colspan="3"></td>
                                                        <td>
                                                            {{ @$inquiryGeneralCharge->{'version_' . ($generalChargesVendor->version) . '_remark'} ?? '' }}
                                                        </td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-end">Total:</th>
                                            <th colspan="3"></th>
                                            @foreach($generalChargesVendorVersion as $itemKey => $generalChargesVendor)
                                                @php
                                                    $total = $inquiryGeneralCharges->sum(function($inquiryGeneralCharge) use ($generalChargesVendor) {
                                                        return $inquiryGeneralCharge->{'version_' . $generalChargesVendor->version . '_total_with_gst'} ?? 0;
                                                    });
                                                @endphp
                                                <th colspan="4"><b>{{number_format($total,2)}}</b></th>
                                                @if(isset($inquiryGrandTotal[$itemKey]))
                                                    @php $inquiryGrandTotal[$itemKey] = $inquiryGrandTotal[$itemKey] + $total; @endphp
                                                @endif
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-end">Grand Total:</th>
                                            <th colspan="3"></th>
                                            @foreach($inquiryGrandTotal as $grandTotal)
                                                <th colspan="4"><b>{{number_format($grandTotal,2)}}</b></th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-end">Document:</th>
                                            <th colspan="3"></th>
                                            @foreach($data as $vr)
                                                @php
                                                    $documents = \App\Models\TechnicalDocument::where('inquiry_id',$inquiry->id)->where('vendor_id',$vendor->user_id)->where('version',$vr->version)->get();
                                                @endphp
                                                <th colspan="4">
                                                    @foreach($documents as $document)
                                                        <a href="{{asset('images/'.$document->document)}}"
                                                           target="_blank"><i
                                                                class="ti ti-eye"></i></a>{{$document->document}}

                                                        <hr style="color:#007bff!important;">
                                                    @endforeach
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-end">Remarks:</th>
                                            <th colspan="3"></th>
                                            @foreach($data as $vr)
                                                @php
                                                    $remarks = \App\Models\VendorVersionRemark::where('inquiry_id',$inquiry->id)->where('vendor_id',$vendor->user_id)->where('version',$vr->version)->first();
                                                @endphp
                                                <th colspan="4">{{isset($remarks->remarks)}}</th>
                                            @endforeach
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="allocateFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="allocateFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="allocateFormModalTitle">Finalize Version</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="" method="post" enctype="multipart/form-data"
                          action="{{ route('inquiry-master.finalize-quotation')}}">
                        @csrf
                        <input type="hidden" name="inquiry_id" value="{{$inquiry->id}}">
                        <input type="hidden" name="vendor_id" value="{{$vendor->id}}">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Finalize Version<span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2" name="version" id="vendor_id">
                                    <option value="" selected hidden="">Select One</option>
                                    @foreach($data as $key => $vr)
                                        <option value="{{($vr->version)}}">Version{{($vr->version)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                            </button>
                            <button type="submit" class="btn btn-submit">Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="allocateFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="">Image/Doc Preview</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 img-1">
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
    <script>
        function openForm(id) {
            $("#allocateFormModal").modal('show');
            $("#product_id").val(id);
        }
    </script>
    <script>
        function approveProduct(inquiryId, vendorId) {
            Swal.fire({
                //title: "Are you sure?",
                text: "Are you sure Approve it?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('inquiry-master.finalize-quotation') }}',
                        data: {
                            _token: '{{csrf_token()}}',
                            inquiry_id: inquiryId,
                            vendor_id: vendorId,
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
                            location.reload();
                        },
                        error: function (error) {
                        }
                    });
                }
            });

        }

        function compareProduct() {
            $("#compareFormModal").modal('show');
        }

        function submitProduct(inquiry_id, vendor_id) {
            var selectElement = document.getElementById('vendor');

            // Create an array to store the selected values
            var selectedValues = [];

            // Loop through the options in the select element
            for (var i = 0; i < selectElement.options.length; i++) {
                // Check if the option is selected
                if (selectElement.options[i].selected) {
                    // Add the selected value to the array
                    selectedValues.push(selectElement.options[i].value);
                }
            }

            // Now `selectedValues` contains all the selected options' values
            $.ajax({
                type: 'post',
                url: '{{ route('inquiry-master.compare-product') }}',
                data: {
                    _token: '{{csrf_token()}}',
                    inquiry_id: inquiry_id,
                    vendor: selectedValues,
                    vendor_id: vendor_id,
                },
                success: function (response) {
                    $("#comparison_product").html(response)
                    $("#compareFormModal").modal('hide');
                },
                error: function (error) {
                }
            });
        }

        function showImage(image) {
            var extension = image.split('.').pop().toLowerCase();
            var baseUrl = '{{ asset('images/') }}'
            var filePath = baseUrl + '/' + image;
            // Log the extension to the console (for testing)
            $("#imageFormModal").modal('show');
            if (extension == "pdf") {
                $('.img-1').html('<embed src="' + filePath + '" type="application/pdf" width="100%" height="600px"/>')
            } else {
                $('.img-1').html('<img src="' + filePath + '" id="attachmentPreview" alt="Attachment Preview" class="custom-image"/>')
            }
        }
    </script>
@endpush
