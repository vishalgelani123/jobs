@extends('backend.layouts.app')
@section('title')
    Vendor Inquiry
@endsection
@push('styles')
    <style>
        #field-blank-common-error-message {
            color: #EA5455 !important;
        }

        @media (max-width: 768px) {
            .table-responsive input.form-control,
            .table-responsive textarea.form-control,
            .table-responsive select.form-control {
                width: calc(100% - -101px) !important; /* Full width minus padding */
                font-size: 16px !important; /* Ensure font size is respected */
                padding: 10px !important; /* Ensure padding is respected */
                height: calc(100% - 84px) !important;
            }

            /* Make sure table cells don't shrink */
            .table-responsive td {
                min-width: 100px; /* Set a minimum width for table cells */
                overflow: hidden; /* Hide overflow */
            }
        }

        .version-remark-text {
            overflow: hidden;
            max-height: 100px;
            transition: max-height 1s ease-in-out;
        }

        .expanded-version-remarks {
            max-height: 1000px;
        }
    </style>
@endpush
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
        <div class="col-12 mb-4">
            <div class="card h-100 w-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Inquiry Details</h5>
                        <div class="dt-action-buttons">
                            @if(Auth::user()->hasRole('admin') && $inquiry->status == 'open')
                                <a href="#" onclick="changeAdminStatus()">
                                    {!! $inquiry->inquiry_status_with_bg !!}
                                </a>
                            @else
                                <a>{!! $inquiry->inquiry_status_with_bg !!}</a>
                            @endif
                        </div>
                    </div>
                    <hr>

                    <h6>Subject</h6>
                    <p class="small">{{ $inquiry->remarks }}</p>
                    <hr>

                    <div class="row mb-3 g-3">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-2">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ti ti-user ti-md"></i>
                            </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-truncate">{{ $inquiry->name }}</h6>
                                    <small>Inquiry Name</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-2">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ti ti-calendar ti-md"></i>
                            </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-truncate">{{ $inquiry->inquiry_date .' '.$inquiry->start_time }}</h6>
                                    <small>Inquiry Date</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-2">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ti ti-calendar ti-md"></i>
                            </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-truncate">{{ $inquiry->end_date .' '. $inquiry->end_time }}</h6>
                                    <small>End Date</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-2">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ti ti-file-typography ti-md"></i>
                            </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-truncate">{{ $inquiry->vendorType->name }}</h6>
                                    <small>Vendor Type</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-2">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="ti ti-user ti-md"></i>
                            </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-truncate">{{ @$inquiry->user->name .'('.@$inquiry->user->mobile .')' }}</h6>
                                    <small>Inquiry Contact Details</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($inquiry->admin_status == 'Approved')

            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Vendor Version List</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-3" id="inquiry-vendor-table">
                                <thead>
                                <tr>
                                    <th rowspan="2">Id</th>
                                    <th colspan="2" rowspan="2">Description</th>
                                    <th rowspan="2">Qty</th>
                                    @foreach($vendorVersions as $vendorVersion)
                                        <th colspan="4" class="text-center"
                                            style="color: {{$vrArr[intval($vendorVersion['version'])]}}">
                                            Version {{$vendorVersion['version']}}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach($vendorVersions as $vendorVersion)
                                        <th>Rate</th>
                                        <th>GST</th>
                                        <th>Amount</th>
                                        <th>Remark</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalSums = [];
                                @endphp

                                @foreach($inquiryProductDetails as $key => $details)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td colspan="2"><b>{!! nl2br(e($details->item_description)) !!}</b>
                                            ({!! nl2br(e($details->additional_info)) !!})
                                            )
                                            <br>
                                            <small>{{$details->description}}</small>
                                        </td>
                                        <td>{{$details->qty}} {{ $details->unit }}</td>
                                        @foreach($vendorVersions as $vendorVersion)
                                            @php
                                                $inquiryVersion = \App\Models\VendorVersion::where('version',$vendorVersion['version'])
                                                    ->where('ipd_id',$details->id)
                                                    ->where('user_id',\Illuminate\Support\Facades\Auth::user()->id)
                                                    ->first();

                                                // Add total_with_gst to the totalSums array, indexed by vendor version
                                                if (isset($inquiryVersion)) {
                                                    $totalSums[$vendorVersion['version']] = ($totalSums[$vendorVersion['version']] ?? 0) + $inquiryVersion->total_with_gst;
                                                }

                                                $product = \App\Models\InquiryProductDetail::find($details->id);
                                            @endphp
                                            <td style="color: {{$vrArr[intval($vendorVersion['version'])]}}">@if($product->qty > 0)
                                                    {{@$inquiryVersion->rate / @$product->qty}}
                                                @else
                                                    {{@$inquiryVersion->rate}}
                                                @endif</td>
                                            <td style="color: {{$vrArr[intval($vendorVersion['version'])]}}">{{@$inquiryVersion->gst_amount}}
                                                ({{@$inquiryVersion->gst_rate}}%)
                                            </td>
                                            <td style="color: {{$vrArr[intval($vendorVersion['version'])]}}">{{@$inquiryVersion->total_with_gst}}</td>
                                            <td style="color: {{$vrArr[intval($vendorVersion['version'])]}}">{{@$inquiryVersion->remarks}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="4"><strong>Total :</strong></td>
                                    @foreach($vendorVersions as $vendorVersion)
                                        <td colspan="4" class="text-center"
                                            style="color: {{$vrArr[intval($vendorVersion['version'])]}}">
                                            <strong><i
                                                        class="ti ti-currency-rupee"></i> {{ $totalSums[intval($vendorVersion['version'])] ?? 0 }}
                                            </strong>
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Vendor General Charges Version List</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-3" id="general-charges-table">
                                <thead>
                                <tr>
                                    <th rowspan="2">Id</th>
                                    <th colspan="2" rowspan="2">Name</th>
                                    @foreach($chargesVendorVersions as $version)
                                        <th colspan="5" class="text-center"
                                            style="color : {{$vrArr[intval($vendorVersion['version'])]}}">
                                            Version {{$version['version']}}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach($chargesVendorVersions as $version)
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>GST</th>
                                        <th>Amount</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $generalChargesTotalSums = [];
                                @endphp

                                @foreach($inquiryGeneralCharges as $key => $details)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td colspan="2"><b>{{$details->generalCharge->name}}</b></td>

                                        @foreach($chargesVendorVersions as $vendorVersion)
                                            @php
                                                $inquiryVersion = \App\Models\GeneralChargesVendorVersion::where('version', $vendorVersion['version'])
                                                    ->where('inquiry_general_charges_id', $details->id)
                                                    ->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)
                                                    ->first();

                                                // If an inquiry version exists, add its total_with_gst to the totals array
                                                if ($inquiryVersion) {
                                                    $generalChargesTotalSums[$vendorVersion['version']] =
                                                        ($generalChargesTotalSums[$vendorVersion['version']] ?? 0) + $inquiryVersion->total_with_gst;
                                                }
                                                $charges = "";
                                                if($inquiryVersion){
                                                    $charges = \App\Models\InquiryGeneralCharge::find($inquiryVersion->inquiry_general_charges_id);
                                                }
                                            @endphp
                                            @if(isset($charges->status) && $charges->status == "with_price_qty")
                                                <td style="color : {{$vrArr[$vendorVersion['version']]}}">{{ $inquiryVersion->quantity ?? 'N/A' }}</td>
                                                <td style="color : {{$vrArr[$vendorVersion['version']]}}">{{ $inquiryVersion->rate ?? 'N/A' }}</td>
                                                <td style="color : {{$vrArr[$vendorVersion['version']]}}">{{ $inquiryVersion->gst_amount ?? 'N/A' }}</td>
                                                <td style="color : {{$vrArr[$vendorVersion['version']]}}">{{ $inquiryVersion->total_with_gst ?? 'N/A' }}</td>
                                            @else
                                                <td colspan="3"></td>
                                                <td style="color : {{$vrArr[$vendorVersion['version']]}}">{{ $inquiryVersion->remark ?? 'N/A' }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="3"><strong>Total:</strong></td>
                                    @foreach($chargesVendorVersions as $vendorVersion)
                                        <td colspan="4" class="text-center"
                                            style="color: {{$vrArr[$vendorVersion['version']]}}">
                                            <strong><i
                                                        class="ti ti-currency-rupee"></i> {{ $generalChargesTotalSums[$vendorVersion['version']] ?? 0 }}
                                            </strong>
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Version Wise Remark</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-3" id="version-wise-remark-table">
                                <thead>
                                <tr>
                                    @foreach($chargesVendorVersions as $version)
                                        <th class="text-center"
                                            style="color : {{$vrArr[intval($vendorVersion['version'])]}}">
                                            Version {{$version['version']}}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach($chargesVendorVersions as $key => $vendorVersion)
                                        @php
                                            $inquiryVersion = \App\Models\VendorVersionRemark::where('version', $vendorVersion['version'])
                                                ->where('inquiry_id', $inquiry->id)
                                                ->where('vendor_id', \Illuminate\Support\Facades\Auth::user()->id)
                                                ->first();
                                        @endphp
                                        <td>
                                            @if(isset($inquiryVersion->remarks) && $inquiryVersion->remarks != '')
                                                <div class="version-remark-container">
                                                    <p class="version-remark-text">
                                                        {{ Str::limit($inquiryVersion->remarks, 100) }}
                                                    </p>
                                                    <span
                                                            class="version-full-remark d-none">{{ $inquiryVersion->remarks }}</span>
                                                    @if(strlen($inquiryVersion->remarks) > 100)
                                                        <a href="javascript:void(0);" class="version-view-more"
                                                           data-view="more">View
                                                            More</a>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Product Inquiry List</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="inquiry-vendor-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="table-responsive">
                                <table class="table" id="inquiry-vendor-table"
                                       aria-describedby="inquiry-vendor-table_info">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th colspan="2">Description</th>
                                        <th>Qty</th>
                                        @if(@$vendorData->status!='close' && $inquiry->status!='close')
                                            <th>Price</th>
                                            <th>GST</th>
                                            <th>Total</th>
                                            <th colspan="3">Remark</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($inquiryProductDetails as $key => $details)
                                        <tr>
                                            <td data-id="{{$details->id}}">{{$key+1}}</td>
                                            <td colspan="2" class="w-auto">
                                                <b>{!! nl2br(e($details->item_description)) !!}</b>
                                                ({!! nl2br(e($details->additional_info)) !!})
                                                <br>
                                                <small>&nbsp;{{$details->description}}</small>
                                            </td>
                                            <td class="w-auto">{{$details->qty}} {{ $details->unit }}</td>
                                            @if(@$vendorData->status!='close' && $inquiry->status!='close')
                                                <input type="hidden" id="qty_{{$details->id}}"
                                                       value="{{$details->qty}}">
                                                <input type="hidden" id="original_price_{{$details->id}}">
                                                <td><input type="text" id="vendor_price_{{$details->id}}"
                                                           class="form-control w-auto"
                                                           onchange="setAmount('{{$details->id}}')">
                                                </td>
                                                <td>
                                                    <select class="form-control w-auto" name="gst_rate"
                                                            id="gst_rate_{{$details->id}}"
                                                            onchange="setAmount('{{$details->id}}')">
                                                        <option value="0">0%</option>
                                                        <option value="5">5%</option>
                                                        <option value="6">6%</option>
                                                        <option value="12">12%</option>
                                                        <option value="18">18%</option>
                                                        <option value="28">28%</option>
                                                    </select>
                                                    <br>
                                                    <input type="text" id="gst_amount_{{$details->id}}"
                                                           class="form-control w-auto"
                                                           value="0.00" readonly>
                                                </td>
                                                <td><input type="text" id="total_amount_gst_{{$details->id}}"
                                                           class="form-control w-auto" value="0.00" readonly></td>
                                                <td colspan="3"><textarea class="form-control w-auto"
                                                                          id="vendor_description_{{$details->id}}">{{$details->default_remark}}</textarea>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5"></td>
                                        @if(@$vendorData->status!='close' && $inquiry->status!='close')
                                            <td><p id="total_gst_amount">Total: <b>0</b></p></td>
                                            <td><p id="total_amount">Total: <b>0</b></p></td>
                                        @endif
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Inquiry General Charges List</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="inquiry-general-charges-table_wrapper"
                             class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="table-responsive">
                                <table class="table" id="inquiry-general-charges-table"
                                       aria-describedby="inquiry-general-charges-table_info">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th colspan="2">Name</th>
                                        <th>Qty</th>
                                        @if(@$vendorData->status!='close' && $inquiry->status!='close')
                                            <th>Rate</th>
                                            <th>GST</th>
                                            <th>Total</th>
                                            <th colspan="3">Remark</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($inquiryGeneralCharges as $key => $inquiryGeneralCharge)
                                        <tr>
                                            <td data-status="{{ $inquiryGeneralCharge->status }}"
                                                general-charge-id="{{ $inquiryGeneralCharge->id }}">{{ $key + 1 }}</td>
                                            <td colspan="2" class="w-auto">
                                                <b>{{$inquiryGeneralCharge->generalCharge->name}}</b></td>
                                            @if(@$vendorData->status!='close' && $inquiry->status!='close')
                                                @if(@$inquiryGeneralCharge->status == 'with_price_qty')
                                                    <td>
                                                        <input type="text"
                                                               id="general_charges_quantity_{{$inquiryGeneralCharge->id}}"
                                                               class="form-control w-auto"
                                                               onchange="generalChargesSetAmount('{{$inquiryGeneralCharge->id}}')">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                               id="general_charges_rate_{{$inquiryGeneralCharge->id}}"
                                                               class="form-control w-auto"
                                                               onchange="generalChargesSetAmount('{{$inquiryGeneralCharge->id}}')">
                                                    </td>
                                                    <td>
                                                        <select class="form-control w-auto"
                                                                name="general_charges_gst_rate"
                                                                id="general_charges_gst_rate_{{$inquiryGeneralCharge->id}}"
                                                                onchange="generalChargesSetAmount('{{$inquiryGeneralCharge->id}}')">
                                                            <option value="">Included</option>
                                                            <option value="0">0%</option>
                                                            <option value="5">5%</option>
                                                            <option value="6">6%</option>
                                                            <option value="12">12%</option>
                                                            <option value="18">18%</option>
                                                            <option value="28">28%</option>
                                                        </select>
                                                        <br>
                                                        <input type="text"
                                                               id="general_charges_gst_amount_{{$inquiryGeneralCharge->id}}"
                                                               class="form-control w-auto" value="0.00" readonly>
                                                    </td>
                                                    <td><input type="text"
                                                               id="general_charges_total_amount_gst_{{$inquiryGeneralCharge->id}}"
                                                               class="form-control w-auto" value="0.00" readonly></td>
                                                @else
                                                    <td colspan="4"></td>
                                                @endif
                                                <td colspan="3"><textarea class="form-control w-auto"
                                                                          id="general_charges_remark_{{$inquiryGeneralCharge->id}}"></textarea>
                                                </td>
                                            @else
                                                <td>{{$inquiryGeneralCharge->quantity}} fggf</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="{{$generalChargeVersion != 0 ? 3 : 3}}" class="w-auto"></td>
                                        @if($generalChargeVersion != 0)
                                            <td class="w-auto">Total:
                                                <b>{{$inquiryGeneralCharges->sum('total_with_gst')}}</b></td>
                                        @endif
                                        <td colspan="{{$generalChargeVersion != 0 ? 4 : 2}}" class="w-auto"></td>
                                        @if(@$vendorData->status!='close' && $inquiry->status!='close')
                                            <td class="w-auto"><p id="general_charges_total_gst_amount">Total: <b>0</b>
                                                </p>
                                            </td>
                                            <td class="w-auto"><p id="general_charges_total_amount">Total: <b>0</b></p>
                                            </td>
                                        @endif
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <div id="inquiry-vendor-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <label><b>Remarks:</b></label>
                                    <textarea class="form-control" rows="6" name="remarks" id="remarks"></textarea>
                                </div>
                                <div class="col-md-12" id="remarks-error" style="color: #FF0000;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    @if(isset($inquiry->status) && $inquiry->status != 'close' && isset($vendorData->status) && $vendorData->status != 'close')
        <div class="row mt-4">
            <div class="col-12 text-right d-flex align-items-center justify-content-end">
                <p class="mb-0 mr-2 d-none" id="loader-message">Please wait until your data is submit</p>
                <img src="{{asset('assets/images/loader.gif')}}" class="d-none" id="submit-all-loader"
                     style="width: 25px;" alt="loader">
                <button type="button" class="btn btn-primary ml-3" id="submit-all">Submit</button>
            </div>
            <div class="col-12 text-right">
                <div class="d-none" id="field-blank-common-error-message">Please check above if any
                    field is blank
                </div>
            </div>
        </div>
    @endif

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Technical Document</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class="dt-buttons">
                            <button type="button"
                                    class="dt-button create-new btn btn-success waves-effect waves-light"
                                    onclick="showDocument()">
                                        <span>
                                            <i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add Document</span>
                                        </span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div id="inquiry-product-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-responsive datatables-basic dataTable no-footer dtr-inline"
                                       id="inquiry-product-table" aria-describedby="inquiry-product-table_info"
                                       style="width: 995px;">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Document Name</th>
                                        <th>Version</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($documents as $key => $image)
                                        @php
                                            $extension = pathinfo($image->document, PATHINFO_EXTENSION);
                                        @endphp
                                        <tr>
                                            <td class="sorting_1 dtr-control">{{$key+1}}</td>
                                            <td>{{$image->document_name}}</td>
                                            <td>{{$image->version}}</td>
                                            <td>
                                                <ul class="d-flex pl-0" style="list-style-type: none;">
                                                    <li><a href="javascript:void(0);" class="action-button m-2"
                                                           onclick="showImage('{{$image->document}}')"><i
                                                                    class="ti ti-eye action-icons"></i></a></li>
                                                    <li></li>
                                                    <li><a onclick="deleteAttachments('{{$image->id}}')"
                                                           title="Delete" class="action-button"><i
                                                                    class="ti ti-trash action-icons"></i></a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Terms And Conditions</h5>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($generalTermConditionsCategories as $k => $generalTermConditionsCategory)
                        <div class="col-12">
                            <div class="fw-bold mb-3">{{$k+1}}.&nbsp;{{$generalTermConditionsCategory->name}}</div>
                        </div>
                        @foreach($generalTermConditionsCategory->termConditions as $key => $termConditions)
                            <div class="col-12">
                                <div class="col-12">
                                    {!! $termConditions->description !!}
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Description/Term Conditions</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        {!! $inquiry->description_term_condition !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Supported Documents</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div id="inquiry-product-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table datatables-basic dataTable no-footer dtr-inline"
                                       id="inquiry-product-table" aria-describedby="inquiry-product-table_info"
                                       style="width: 995px;">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Document Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($images as $image)
                                        @php
                                            $extension = pathinfo($image->image, PATHINFO_EXTENSION);
                                        @endphp
                                        <tr>
                                            <td class="sorting_1 dtr-control">{{$image->id}}</td>
                                            <td>{{$image->name}}</td>
                                            <td>
                                                <ul class="d-flex pl-0" style="list-style-type: none;">
                                                    <li><a href="javascript:void(0);" class="action-button m-2"
                                                           onclick="showImage('{{$image->image}}')"><i
                                                                    class="ti ti-eye action-icons"></i></a></li>
                                                    <li>
                                                        <a href="{{ route('vendor.file.download', ['file' => $image->image]) }}"
                                                           class="action-button m-2"><i
                                                                    class="ti ti-download action-icons"></i></a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif

    <div class="col-md-12 mt-4">
        <button id="scrollToTop">
            <i class="ti ti-arrow-up"></i>
        </button>
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
                        <div class="col-12 img-1">
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

    <div class="modal fade" id="technicalDocumentModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="importExcelFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="technicalDocumentForm" enctype="multipart/form-data" action="#">
                    @csrf
                    <input type="hidden" name="inquiry_id" value="{{$inquiry->id}}">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="importExcelFormModalTitle">Add Technical Document</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="ti ti-x close-button-icon"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="type" class="form-label">Document Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="type" class="form-label">Technical Documents<span
                                            class="text-danger">*</span></label>
                                <input type="file" class="filepond" name="images[]" multiple
                                       data-max-files="10" required>
                            </div>
                            <div class="col-12 mb-2 text-danger">Note : Supported documents are
                                .jpg, .jpeg, .png,
                                .pdf,.csv
                            </div>
                            <div class="col-12 mb-2 text-danger" id="image_error_doc"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect waves-light"
                                data-bs-dismiss="modal">Close
                        </button>
                        <button type="button" class="btn btn-submit" onclick="vendorTechnicalDocumentSubmit()">Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>

        function validateInput(selector) {
            let value = $(selector).val();
            if (!value || value.length == 0 || value == undefined) {
                $(selector).after('<span class="text-danger">This field is required</span>');
                showCommonErrorMessage();
                return false;
            }
            return true;
        }

        function clearValidationErrors() {
            $('.text-danger').remove();
        }

        function validateInputLength(selector, maxLength) {
            let value = $(selector).val();
            if (value.length > maxLength) {
                $(selector)
                    .addClass('is-invalid')
                    .after(`<span class="text-danger"> The remarks field must not be greater than 600 characters.</span>`);
                return false;
            }
            return true;
        }

        $('#submit-all').on('click', function () {
            clearValidationErrors();
            let isValid = true;

            let vendorInquiryData = [];
            let vendorProductInquiryData = [];
            let vendorGeneralChargesInquiryData = [];
            let remarks = $("#remarks").val();

            $('#inquiry-vendor-table tbody tr').each(function () {
                let id = $(this).find('td:eq(0)').attr('data-id');
                if (id != undefined && id.length > 0) {
                    isValid &= validateInput("#vendor_price_" + id);
                    isValid &= validateInputLength(`#vendor_description_${id}`, 600);

                    if (isValid) {
                        vendorProductInquiryData.push({
                            product_id: id,
                            inquiry_id: {{$inquiry->id}},
                            vendor_price: $("#original_price_" + id).val(),
                            vendor_description: $("#vendor_description_" + id).val(),
                            gst_rate: $("#gst_rate_" + id).val(),
                            gst_amount: $("#gst_amount_" + id).val(),
                            total_amount_gst: $("#total_amount_gst_" + id).val(),
                        });
                    }
                }
            });

            $('#inquiry-general-charges-table tbody tr').each(function () {
                let generalChargesId = $(this).find('td:eq(0)').attr('general-charge-id');
                let status = $(this).find('td:eq(0)').attr('data-status');

                if (generalChargesId != undefined && generalChargesId.length > 0) {
                    if (status == 'only_remark') {
                        isValid &= validateInput("#general_charges_remark_" + generalChargesId); // Quantity is required
                    } else {
                        isValid &= validateInput("#general_charges_quantity_" + generalChargesId); // Quantity is required
                        isValid &= validateInput("#general_charges_rate_" + generalChargesId); // Rate is required
                        isValid &= validateInputLength(`#general_charges_remark_${generalChargesId}`, 600);
                    }

                    if (isValid) {
                        vendorGeneralChargesInquiryData.push({
                            general_charges_id: generalChargesId,
                            inquiry_id: {{$inquiry->id}},
                            general_charges_quantity: $("#general_charges_quantity_" + generalChargesId).val(),
                            general_charges_rate: $("#general_charges_rate_" + generalChargesId).val(),
                            general_charges_gst_rate: $("#general_charges_gst_rate_" + generalChargesId).val(),
                            general_charges_gst_amount: $("#general_charges_gst_amount_" + generalChargesId).val(),
                            general_charges_total_amount_gst: $("#general_charges_total_amount_gst_" + generalChargesId).val(),
                            general_charges_remark: $("#general_charges_remark_" + generalChargesId).val(),
                        });
                    }
                }
            });

            if (!isValid) {
                showCommonErrorMessage();
                return;
            }

            vendorInquiryData.push({productInquiry: vendorProductInquiryData});
            vendorInquiryData.push({generalChargesInquiry: vendorGeneralChargesInquiryData});

            Swal.fire({
                text: "Do you want to submit data?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, submit it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#submit-all').removeClass('d-block').addClass('d-none');
                    $('#submit-all-loader').addClass('d-block').removeClass('d-none');
                    $('#loader-message').addClass('d-block').removeClass('d-none');

                    $.ajax({
                        type: 'post',
                        url: '{{route('vendor-inquiry.store') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'data': vendorInquiryData,
                            'remarks': remarks,
                        },
                        success: function (response) {
                            if (response.status == true) {
                                Swal.fire({
                                    text: 'Data submit successfully',
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: "warning",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function (xhr) {
                            $('#submit-all').addClass('d-block').removeClass('d-none');
                            $('#submit-all-loader').addClass('d-none').removeClass('d-block');
                            $('#loader-message').addClass('d-none').removeClass('d-block');

                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                if (errors.remarks.length > 0) {
                                    $('#remarks-error').text(errors.remarks[0]);
                                }
                            }
                        }
                    });
                }
            });
        });

        function showCommonErrorMessage() {
            $('#field-blank-common-error-message').removeClass('d-none');
            setTimeout(function () {
                $('#field-blank-common-error-message').addClass('d-none');
            }, 5000);
        }

        function showImage(image) {
            let extension = image.split('.').pop().toLowerCase();
            let baseUrl = '{{ asset('images/') }}'
            let filePath = baseUrl + '/' + image;
            $("#imageFormModal").modal('show');
            if (extension == "pdf" || extension == 'xlsx') {
                $('.img-1').html('<embed src="' + filePath + '" type="application/pdf" width="100%" height="600px"/>')
            } else {
                $('.img-1').html('<img src="' + filePath + '" id="attachmentPreview" alt="Attachment Preview" class="custom-image"/>')
            }
        }

        let gstAmounts = {};
        let totalAmounts = {};

        function setAmount(id) {
            let price = parseFloat($("#vendor_price_" + id).val()) || 0;
            let gst = parseFloat($("#gst_rate_" + id).val()) || 0;
            let qty = parseFloat($("#qty_" + id).val()) || 0;

            if (gst > 0) {
                let totalPrice = price * qty;
                let gstAmount = totalPrice * gst / 100;
                let gstTotalAmount = totalPrice + gstAmount;

                $("#gst_amount_" + id).val(gstAmount.toFixed(2));
                $("#original_price_" + id).val(totalPrice.toFixed(2));
                $("#total_amount_gst_" + id).val(gstTotalAmount.toFixed(2));

                // Store GST amount in the gstAmounts object
                gstAmounts[id] = gstAmount;
                totalAmounts[id] = gstTotalAmount;
            } else {
                $("#gst_amount_" + id).val(0);
                let gstTotalAmount = price * qty;
                $("#total_amount_gst_" + id).val(gstTotalAmount.toFixed(2));
                $("#original_price_" + id).val(gstTotalAmount.toFixed(2));

                // Store 0 in gstAmounts if GST is 0%
                gstAmounts[id] = 0;
                totalAmounts[id] = gstTotalAmount;
            }

            // Calculate total GST and total amount for all products
            calculateTotalGst();
        }

        function calculateTotalGst() {
            let totalGst = 0;
            let totalAmount = 0;

            // Loop through gstAmounts object to sum up all GST amounts
            for (const productId in gstAmounts) {
                totalGst += gstAmounts[productId];
                totalAmount += totalAmounts[productId];
            }

            $("#total_gst_amount").html('Total GST: <b>' + totalGst.toFixed(2) + '</b>'); // Element for total GST
            let onlyGstTotalHtml = totalGst.toFixed(2);
            let totalAmountHtml = (totalAmount.toFixed(2) - totalGst.toFixed(2));
            let grandTotalAmountHtml = totalAmount.toFixed(2);
            $("#total_amount").html('GST Amount: <b>' + onlyGstTotalHtml + '</b><br>' + 'Total: <b>' + totalAmountHtml + '</b><br> Grand Total: <b>' + grandTotalAmountHtml + '</b>'); // Element for total amount
        }

        let generalChargesGstAmounts = {};
        let generalChargesTotalAmounts = {};

        function generalChargesSetAmount(id) {
            let generalChargesQuantity = parseFloat($("#general_charges_quantity_" + id).val()) || 0;
            let generalChargesRate = parseFloat($("#general_charges_rate_" + id).val()) || 0;
            let generalChargesGstRate = parseFloat($("#general_charges_gst_rate_" + id).val()) || 0;

            let generalChargesTotalPrice = generalChargesRate * generalChargesQuantity;

            if (generalChargesGstRate > 0) {
                let generalChargesGstAmount = generalChargesTotalPrice * generalChargesGstRate / 100;
                let totalAmountWithGst = generalChargesTotalPrice + generalChargesGstAmount;

                $("#general_charges_gst_amount_" + id).val(generalChargesGstAmount.toFixed(2));
                $("#general_charges_total_amount_gst_" + id).val(totalAmountWithGst.toFixed(2));

                generalChargesGstAmounts[id] = generalChargesGstAmount;
                generalChargesTotalAmounts[id] = totalAmountWithGst;
            } else {
                $("#general_charges_gst_amount_" + id).val("0.00");
                $("#general_charges_total_amount_gst_" + id).val(generalChargesTotalPrice.toFixed(2));

                generalChargesGstAmounts[id] = 0;
                generalChargesTotalAmounts[id] = generalChargesTotalPrice;
            }

            generalChargesCalculateTotalGst();
        }

        function generalChargesSetAmount(id) {
            let generalChargesQuantity = parseFloat($("#general_charges_quantity_" + id).val()) || 0;
            let generalChargesRate = parseFloat($("#general_charges_rate_" + id).val()) || 0;
            let generalChargesGstRate = parseFloat($("#general_charges_gst_rate_" + id).val()) || 0;

            let generalChargesTotalPrice = generalChargesRate * generalChargesQuantity;

            if (generalChargesGstRate > 0) {
                let generalChargesGstAmount = generalChargesTotalPrice * generalChargesGstRate / 100;
                let totalAmountWithGst = generalChargesTotalPrice + generalChargesGstAmount;

                $("#general_charges_gst_amount_" + id).val(generalChargesGstAmount.toFixed(2));
                $("#general_charges_total_amount_gst_" + id).val(totalAmountWithGst.toFixed(2));

                generalChargesGstAmounts[id] = generalChargesGstAmount;
                generalChargesTotalAmounts[id] = totalAmountWithGst;
            } else {
                $("#general_charges_gst_amount_" + id).val("0.00");
                $("#general_charges_total_amount_gst_" + id).val(generalChargesTotalPrice.toFixed(2));

                generalChargesGstAmounts[id] = 0;
                generalChargesTotalAmounts[id] = generalChargesTotalPrice;
            }

            generalChargesCalculateTotalGst();
        }

        function generalChargesCalculateTotalGst() {
            let generalChargesTotalGst = 0;
            let generalChargesTotalAmount = 0;

            for (const generalChargesId in generalChargesGstAmounts) {
                generalChargesTotalGst += generalChargesGstAmounts[generalChargesId];
                generalChargesTotalAmount += generalChargesTotalAmounts[generalChargesId];
            }

            $("#general_charges_total_gst_amount").html('Total GST: <b>' + generalChargesTotalGst.toFixed(2) + '</b>');
            let onlyGstTotalHtml = generalChargesTotalGst.toFixed(2);
            let totalAmountHtml = (generalChargesTotalAmount.toFixed(2) - generalChargesTotalGst.toFixed(2));
            let grandTotalAmountHtml = generalChargesTotalAmount.toFixed(2);
            $("#general_charges_total_amount").html('GST Amount: <b>' + onlyGstTotalHtml + '</b><br>' + 'Total: <b>' + totalAmountHtml + '</b><br> Grand Total: <b>' + grandTotalAmountHtml + '</b>'); // Element for total amount
        }

        function setAmount(id) {
            let price = parseFloat($("#vendor_price_" + id).val()) || 0;
            let gst = parseFloat($("#gst_rate_" + id).val()) || 0;
            let qty = parseFloat($("#qty_" + id).val()) || 0;

            if (gst > 0) {
                let totalPrice = price * qty;
                let gstAmount = totalPrice * gst / 100;
                let gstTotalAmount = totalPrice + gstAmount;

                $("#gst_amount_" + id).val(gstAmount.toFixed(2));
                $("#original_price_" + id).val(totalPrice.toFixed(2));
                $("#total_amount_gst_" + id).val(gstTotalAmount.toFixed(2));

                // Store GST amount in the gstAmounts object
                gstAmounts[id] = gstAmount;
                totalAmounts[id] = gstTotalAmount;
            } else {
                $("#gst_amount_" + id).val(0);
                let gstTotalAmount = price * qty;
                $("#total_amount_gst_" + id).val(gstTotalAmount.toFixed(2));
                $("#original_price_" + id).val(gstTotalAmount.toFixed(2));

                // Store 0 in gstAmounts if GST is 0%
                gstAmounts[id] = 0;
                totalAmounts[id] = gstTotalAmount;
            }

            // Calculate total GST and total amount for all products
            calculateTotalGst();
        }

        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize
        );

        const inputElement = document.querySelector('input[name="images[]"].filepond');
        const imagePond = FilePond.create(inputElement);

        imagePond.setOptions({
            acceptedFileTypes: [
                'image/jpeg', 'image/png', 'image/jpg', 'application/pdf', 'text/csv'
                /*'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip', 'application/x-zip-compressed',
                'application/acad', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image/vnd.dwg'*/
            ],
            maxFiles: 1,  // Allow only one file
            maxFileSize: '20MB', // Optional: Uncomment if needed
            server: {
                process: {
                    url: '/upload',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                revert: {
                    url: '/revert',
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            }
        });

        function showDocument() {
            $('#technicalDocumentModal').modal('show');
        }

        function deleteAttachments(id) {
            let inquiry_id = '{{$inquiry->id}}';
            let route = '{{ route('vendor-inquiry.delete-product-data') }}';
            Swal.fire({
                //title: "Are you sure?",
                text: "Are you sure want to delete?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: route,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'document_id': id,
                            'inquiry_id': inquiry_id
                        },
                        success: function (response) {
                            if (response.status == true) {
                                Swal.fire({
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: 'warning',
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
            });
        }


        document.getElementById("remarks").addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.stopPropagation();
            }
        });

        function vendorTechnicalDocumentSubmit() {
            let url = "{{route('vendor-inquiry.store-document')}}";
            let formData = new FormData($('#technicalDocumentForm')[0]);
            if (imagePond.getFiles().length > 0) {
                formData.append('images[]', imagePond.getFile(0).file);
            }
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#technicalDocumentModal').modal('hide');
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
                    $('#technicalDocumentForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField;
                        if (field.includes('images')) {
                            inputField = $('#technicalDocumentForm').find('[name="images[]"]');
                        } else {
                            inputField = $('#technicalDocumentForm').find('[name="' + field + '"]');
                        }
                        inputField.addClass('is-invalid');
                        if (inputField.attr('type') === 'file') {
                            $('#image_error_doc').html(messages.join('<br>'));
                        } else {
                            inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                            $('#image_error_doc').html(messages.join('<br>'));
                        }
                    });
                }
            });
        }

        $('.version-view-more').click(function () {
            let container = $(this).closest('.version-remark-container');
            let remarkText = container.find('.version-remark-text');
            let fullRemark = container.find('.version-full-remark').text();

            if ($(this).attr('data-view') === "more") {
                remarkText.html(fullRemark).addClass('expanded-version-remarks');
                $(this).text("Show Less").attr('data-view', 'less');
            } else {
                remarkText.html(fullRemark.substring(0, 100) + '...').removeClass('expanded-version-remarks');
                $(this).text("View More").attr('data-view', 'more');
            }
        });
    </script>
@endpush
