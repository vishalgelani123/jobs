@extends('backend.layouts.app')
@section('title')
    Inquiry Details
@endsection
@push('styles')
    <style>
        textarea {
            overflow: auto;
            resize: vertical;
        }
    </style>
@endpush

@section('content')
    <div class="col-md-12">
        @if(Session::has('success'))
            <div class="alert alert-success">{{Session::get('success')}}</div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">{{Session::get('error')}}</div>
        @endif
    </div>

    @php
        $route = route('inquiry-master.index');

        if (Auth::user()->hasRole('drafter')) {
            $route = route('inquiry.index');
        } elseif (Auth::user()->hasRole('approver') && Auth::user()->hasRole('admin')) {
            $route = route('approver-inquiry.approver.inquiry');
        }
    @endphp

    <div class="col-md-12 mb-3 text-right">
        <a href="{{ $route }}" class="btn btn-danger waves-effect waves-light">Back</a>
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
                <p class="small">{{$inquiry->remarks}}</p>
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
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ti ti-clock-2 ti-md"></i>
                            </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-truncate">{{ isset($inquiry->admin_status_updated_at) ? \Carbon\Carbon::parse($inquiry->admin_status_updated_at)->format('d-m-Y H:i A') : '' }}</h6>
                                <small>Last Updated Status</small>
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
                    @if($inquiry->admin_status=="Approved")
                        @if(@$inquiry->approval->name!=null && @$inquiry->approval->mobile!=null)
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar flex-shrink-0 me-2">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="ti ti-user-exclamation ti-md"></i>
                            </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-truncate">{{ @$inquiry->approval->name .'('.@$inquiry->approval->mobile .')' }}</h6>
                                        <small>Inquiry Contact Details</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div id="comparison_product"></div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Approver Details</h5>
                        </div>
                        <div class="dt-action-buttons text-end">
                            <div class="dt-buttons">
                                @if($showApprovalStatusButton == "1")
                                    <button type="button" class="btn btn-submit"
                                            data-bs-toggle="modal" data-bs-target="#approvalStatusModal">
                                        <span class="d-none d-sm-inline-block">Status Update</span>
                                    </button>
                                @endif
                                @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('drafter'))
                                    @if($inquiry->status == 'close')
                                        <img src="{{asset('assets/images/loader.gif')}}" class="d-none mr-5"
                                             id="send-for-approval-loader"
                                             style="width: 20px;" alt="loader">
                                        <button type="button" class="btn btn-submit send-for-approval"
                                                onclick="approverSendMail()">
                                                <span><i class="ti ti-send me-sm-1"></i>
                                                    <span class="d-none d-sm-inline-block">Send For Approval</span>
                                                </span>
                                        </button>
                                    @endif
                                    <button type="button"
                                            class="dt-button create-new btn btn-success waves-effect waves-light"
                                            onclick="showApprovers('{{ route('inquiry-master.approver.details', $inquiry) }}', '{{ route('inquiry-master.approver.submit', $inquiry) }}')">
                                            <span><i class="ti ti-plus me-sm-1"></i>
                                                    <span class="d-none d-sm-inline-block"> Add Approver</span>
                                            </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4"><b>Remark :</b> {!! nl2br(e($inquiry->approval_remark)) !!}</div>
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Approver Name</th>
                                    <th>Status</th>
                                    <th>Date & Time</th>
                                    <th>Remark</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                @if(count($inquiryApprovals)>0)
                                    @foreach($inquiryApprovals as $key => $inquiryApproval)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$inquiryApproval->approvalUser->name}}</td>
                                            <td>{!! $inquiryApproval->status_with_bg !!}</td>
                                            <td>{{ isset($inquiryApproval->status_update_date_time) ? Carbon\Carbon::parse($inquiryApproval->status_update_date_time)->format('d-m-Y h:i A') : '' }}</td>
                                            <td>{!! nl2br(e($inquiryApproval->remark)) !!}</td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2">No Data Available</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div id="comparison_product"></div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Inquiry Allocations</h5>
                        </div>
                        <div class="dt-action-buttons text-end">
                            <div class="dt-buttons d-flex justify-content-end">
                                @if(count($vendorDetails) > 0)
                                    <button type="button" class="btn btn-submit mb-2 me-2"
                                            onclick="compareProduct()">Comparison
                                    </button>
                                @endif
                                @if($inquiry->status == 'open' && $inquiry->admin_status == 'Pending')
                                    @if(Auth::user()->hasRole('admin'))
                                        <button type="button"
                                                class="dt-button create-new btn btn-success waves-effect waves-light mb-2"
                                                onclick="showAllocateModal()">
                                                <span>
                                                    <i class="ti ti-plus me-sm-1"></i>
                                                    <span class="d-none d-sm-inline-block">Allocate</span>
                                                </span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>Version</th>
                                    <th>Grand Total</th>
                                    <th>Quote Bank</th>
                                    <th>Status</th>
                                    @if (Auth::user()->hasRole('admin') && $inquiry->approval_status == 'approved')
                                        <th>Allocation</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                @php $quoteBank = array(); @endphp
                                @if(count($vendorDetails) > 0 )
                                    @foreach($vendorDetails as $detail)
                                        @php
                                            if(isset($detail->vendor->user_id)) {
                                            $vendorVersion = \App\Models\InquiryVendorRateDetail::where('inquiry_id',$inquiry->id)->where('vendor_id',$detail->vendor->user_id)->first();
                                                if(isset($detail->vendor->user_id) && $vendorVersion) {
                                                $productTotal = \App\Models\InquiryVendorRateDetail::where('inquiry_id', $inquiry->id)
                                                ->where('vendor_id', $detail->vendor->user_id)
                                                ->sum('total_with_gst');
                                                $generalChargeTotal = \App\Models\InquiryGeneralCharge::where('inquiry_id', $inquiry->id)
                                                ->where('vendor_id', $detail->vendor->user_id)
                                                ->sum('total_with_gst');

                                                $quoteBank[$detail->vendor->user_id] = ($productTotal+$generalChargeTotal);
                                                }
                                                }
                                        @endphp
                                    @endforeach

                                    @foreach($vendorDetails as $detail)
                                        @if(isset($detail->vendor->id) && $detail->vendor->id != '')
                                            @php
                                                $vendorDetail = \App\Models\Vendor::where('id',$detail->vendor->id)->first();
                                                $version = \App\Models\InquiryVendorRateDetail::where('inquiry_id',$inquiry->id)->where('vendor_id',$detail->vendor->user_id)->first();
                                                $status = \App\Models\InquiryVendorDetail::where('inquiry_id',$inquiry->id)->where('vendor_id',$detail->vendor->user_id)->first();
                                            @endphp
                                        @endif
                                        <tr class="@if(@$inquiryAward->vendor_id == @$detail->vendor->user_id) fw-bold @endif">
                                            <td>{{@$detail->vendor->business_name ? $detail->vendor->business_name : ""}}
                                                ({{ @$detail->city->name ?$detail->city->name : "" }})
                                            </td>
                                            <td>{{@$version->version ? $version->version : ""}}</td>
                                            @php
                                                $grandTotal = 0;
                                                $gstTotalAmount = 0;
                                                $totalAmount = 0;
                                                if(isset($detail->vendor->user_id)){
                                                    $productTotal = \App\Models\InquiryVendorRateDetail::where('inquiry_id', $inquiry->id)
                                                        ->where('vendor_id', $detail->vendor->user_id)
                                                           ->sum('total_with_gst');
                                                    $generalChargeTotal = \App\Models\InquiryGeneralCharge::where('inquiry_id', $inquiry->id)
                                                        ->where('vendor_id', $detail->vendor->user_id)
                                                           ->sum('total_with_gst');

                                                    $productGstAmountTotal = \App\Models\InquiryVendorRateDetail::where('inquiry_id', $inquiry->id)
                                                        ->where('vendor_id', $detail->vendor->user_id)
                                                           ->sum('gst_amount');
                                                    $generalChargeGstTotal = \App\Models\InquiryGeneralCharge::where('inquiry_id', $inquiry->id)
                                                        ->where('vendor_id', $detail->vendor->user_id)
                                                           ->sum('gst_amount');

                                                   $gstTotalAmount = $productGstAmountTotal + $generalChargeGstTotal;
                                                   $grandTotal = $productTotal + $generalChargeTotal;
                                                   $totalAmount = $grandTotal - $gstTotalAmount;
                                                 }
                                            @endphp
                                            <td>
                                                @if(!empty($version) && $version != '')
                                                    &#8377; {{number_format($grandTotal,2)}}
                                                @endif
                                            </td>
                                            {{--@php
                                                asort($quoteBank);

                                                $label_counter = 1;
                                                $assigned_labels = [];

                                                foreach ($quoteBank as $key => $value) {
                                                    if (!isset($assigned_labels[$value])) {
                                                        $assigned_labels[$value] = 'L' . $label_counter;
                                                        $label_counter++;
                                                    }
                                                    $quoteBank[$key] = $assigned_labels[$value];
                                                }

                                            @endphp
                                            <td>
                                                @if(!empty($version) && $version != '')
                                                    @if(isset($detail->vendor->user_id) && isset($quoteBank[$detail->vendor->user_id]))
                                                        {{$quoteBank[$detail->vendor->user_id]}}
                                                    @endif
                                                @endif
                                            </td>--}}

                                            @php
                                                asort($quoteBank);

                                                $label_counter = 1;
                                                $prev_quote = null;
                                                $labeledQuoteBank = [];

                                                foreach ($quoteBank as $userId => $quoteValue) {
                                                    if ($quoteValue !== $prev_quote) {
                                                        $label = 'L' . $label_counter;
                                                        $label_counter++;
                                                        $prev_quote = $quoteValue;
                                                    }
                                                    $labeledQuoteBank[$userId] = $label;
                                                }
                                            @endphp

                                            <td>
                                                @if(!empty($version))
                                                    @php $userId = $detail->vendor->user_id ?? null; @endphp
                                                    @if($userId && isset($labeledQuoteBank[$userId]))
                                                        {{ $labeledQuoteBank[$userId] }}
                                                    @endif
                                                @endif
                                            </td>

                                        @if(isset($status->status))
                                                @if($status->status=="open")
                                                    <td><a style="cursor: pointer"
                                                           @if(Auth::user()->hasRole('admin') && $inquiry->approval_status != 'approved')
                                                               onclick="changeStatus({{$inquiry->id}},{{@$detail->vendor->user_id}},'close')" @endif><span
                                                                class="badge badge-success">{{@$status->status ? ucfirst($status->status) : ""}}</span></a>
                                                    </td>
                                                @else
                                                    <td>
                                                        @if(isset($detail->vendor->id) && $detail->vendor->id != '')
                                                            <a style="cursor: pointer"
                                                               @if(Auth::user()->hasRole('admin') && $inquiry->approval_status != 'approved')
                                                                   onclick="changeStatus({{$inquiry->id}},{{@$detail->vendor->user_id}},'open')" @endif><span
                                                                    class="badge badge-danger">{{@$status->status ? ucfirst($status->status) : ""}}</span></a>
                                                        @endif
                                                    </td>
                                                @endif
                                            @else
                                                <td></td>
                                            @endif
                                            @if (Auth::user()->hasRole('admin') && $inquiry->approval_status == 'approved')
                                                <td>
                                                    @if(isset($detail->vendor->user_id))
                                                        <a href="javascript:void(0);"
                                                           onclick="inquiryAwardStore('{{$detail->vendor->user_id}}')"
                                                           title="Allocation" class="action-button">
                                                            <i class="ti ti-award @if(@$inquiryAward->vendor_id == $detail->vendor->user_id) text-success fw-bold @else text-secondary @endif"></i>
                                                        </a>
                                                        @if(@$inquiryAward->vendor_id == $detail->vendor->user_id)
                                                            @php $mailVendorName = $mailVendorCityName = ""; @endphp
                                                            @if(isset($detail->vendor->business_name))
                                                                @php $mailVendorName =$detail->vendor->business_name; @endphp
                                                            @endif
                                                            @if(isset($detail->city->name))
                                                                @php $mailVendorCityName = $detail->city->name; @endphp
                                                            @endif

                                                            &nbsp;<a href="javascript:void(0);"
                                                                     onclick="sendMailShowFormModal('{{$detail->vendor->user_id}}','{{$mailVendorName}}','{{$mailVendorCityName}}','{{$totalAmount}}','{{$gstTotalAmount}}','{{$grandTotal}}')"
                                                                     title="Allocation" class="action-button">
                                                                <i class="ti ti-mail text-success fw-bold"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                <ul class="d-flex pl-0" style="list-style-type: none;">
                                                    @if(isset($vendorDetail) && $vendorDetail != '')
                                                        <li>
                                                            <form id="" method="get"
                                                                  enctype="multipart/form-data"
                                                                  action="{{ route('inquiry-master.compare-product') }}">
                                                                <input type="hidden" id="inquiry_id"
                                                                       name="inquiry_id"
                                                                       value="{{$inquiry->id}}">
                                                                <input type="hidden" id="vendor_id"
                                                                       name="vendor_id"
                                                                       value="{{@$vendorDetail->user_id}}">
                                                                <input type="hidden" id="vendor_id"
                                                                       name="products[]" value="all">
                                                                <input type="hidden" id="vendor_id"
                                                                       name="vendor[]"
                                                                       value="{{@$vendorDetail->user_id}}">
                                                                <button type="submit"
                                                                        class="action-button border-0 bg-transparent">
                                                                    <i class="ti ti-eye action-icons"></i>
                                                                </button>
                                                            </form>
                                                        </li>
                                                        @if($inquiry->status == 'open' && $inquiry->admin_status == 'Pending')
                                                            @if(Auth::user()->hasRole('drafter'))
                                                                <li>
                                                                    &nbsp;<a class="action-button"
                                                                             href="{{route('inquiry.product-list',[$inquiry,$vendorDetail])}}"><i
                                                                            class="ti ti-plus action-icons"></i></a>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a class="action-button"
                                                                       href="{{route('inquiry-master.product-list',[$inquiry,$vendorDetail])}}"><i
                                                                            class="ti ti-plus action-icons"></i></a>
                                                                </li>
                                                            @endif
                                                            <li>
                                                                @if(Auth::user()->hasRole('drafter'))
                                                                    @if($inquiry->admin_status=="Pending")
                                                                        <a href="javascript:void(0);"
                                                                           class="action-button m-2"
                                                                           onclick="showAllocateModal({{$detail->id}})">
                                                                            <i class="ti ti-edit action-icons"></i>
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <a href="javascript:void(0);"
                                                                       class="action-button m-2"
                                                                       onclick="showAllocateModal({{$detail->id}})">
                                                                        <i class="ti ti-edit action-icons"></i>
                                                                    </a>
                                                                @endif
                                                            </li>
                                                            <li>
                                                                @if(isset($detail->vendor->id) && $detail->vendor->id != '')
                                                                    <a href="javascript:void(0);"
                                                                       onclick="deleteData('{{$detail->vendor->user_id}}')"
                                                                       title="Delete" class="action-button">
                                                                        <i class="ti ti-trash action-icons"></i>
                                                                    </a>
                                                                @endif
                                                            </li>
                                                        @endif
                                                        @if($inquiry->status == "open" && $inquiry->admin_status == "Approved")
                                                            @if(isset($detail->vendor->id) && $detail->vendor->id != '')
                                                                <li>
                                                                    <img
                                                                        src="{{asset('assets/images/loader.gif')}}"
                                                                        class="d-none ml-3"
                                                                        id="follow-up-vendor-loader"
                                                                        style="width: 20px;" alt="loader">
                                                                    <a class="btn btn-primary btn-sm ml-3 follow-up-vendor"
                                                                       onclick="followUpVendor('{{$detail->vendor->user_id}}')">
                                                                        Follow Up
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2">No Data Available</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="head-label text-left">
                    <h5 class="card-title mb-0">Inquiry Product Details</h5>
                </div>
                @if($inquiry->status == 'open' && $inquiry->admin_status == 'Pending')
                    <div class="dt-action-buttons text-end">
                        @if(Auth::user()->hasRole('drafter'))
                            @if($inquiry->admin_status == "Pending")
                                <div class="dt-buttons d-flex justify-content-end">
                                    <button type="button"
                                            class="dt-button create-new btn btn-custom-primary mb-2 me-2"
                                            data-bs-toggle="modal" data-bs-target="#importExcelFormModal">
                                            <span>
                                                <i class="ti ti-file-import me-sm-1"></i>
                                                <span class="d-none d-sm-inline-block">Import</span>
                                            </span>
                                    </button>
                                    <button type="button" class="dt-button create-new btn btn-success mb-2"
                                            onclick="showFormModal()">
                                            <span>
                                                <i class="ti ti-plus me-sm-1"></i>
                                                <span class="d-none d-sm-inline-block">Add New</span>
                                            </span>
                                    </button>
                                </div>
                            @endif
                        @else
                            <div class="dt-buttons d-flex justify-content-end">
                                <button type="button" class="dt-button create-new btn btn-custom-primary mb-2 me-2"
                                        data-bs-toggle="modal" data-bs-target="#importExcelFormModal">
                                            <span>
                                                <i class="ti ti-file-import me-sm-1"></i>
                                                <span class="d-none d-sm-inline-block">Import</span>
                                            </span>
                                </button>
                                <button type="button" class="dt-button create-new btn btn-success mb-2"
                                        onclick="showFormModal()">
                                        <span>
                                            <i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
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
                                    <th>Item Description</th>
                                    <th>Additional Info</th>
                                    <th>Budget</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    @if($inquiry->status == 'open' && $inquiry->admin_status == 'Pending')
                                        @if(Auth::user()->hasRole('drafter'))
                                            @if($inquiry->admin_status=="Pending")
                                                <th>Action</th>
                                            @endif
                                        @else
                                            <th>Action</th>
                                        @endif
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($productInquires as $key => $detail)
                                    <tr>
                                        <td class="sorting_1 dtr-control">{{$key+1}}</td>
                                        <td>{!! nl2br(e($detail->item_description)) !!}</td>
                                        <td>{!! nl2br(e($detail->additional_info)) !!}</td>
                                        <td>{{$detail->price}}</td>
                                        <td>{{$detail->qty}}</td>
                                        <td>{{$detail->unit}}</td>
                                        @if($inquiry->status == 'open' && $inquiry->admin_status == 'Pending')
                                            @if(Auth::user()->hasRole('drafter'))
                                                @if($inquiry->admin_status=="Pending")
                                                    <td>
                                                        <ul class="d-flex pl-0" style="list-style-type: none;">
                                                            <li><a href="javascript:void(0);"
                                                                   class="action-button m-2"
                                                                   onclick="showFormModal({{$detail->id}})"><i
                                                                        class="ti ti-edit action-icons"></i></a>
                                                            </li>
                                                            <li><a onclick="deleteProduct('{{$detail->id}}')"
                                                                   title="Delete" class="action-button"><i
                                                                        class="ti ti-trash action-icons"></i></a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                @endif
                                            @else
                                                <td>
                                                    <ul class="d-flex pl-0" style="list-style-type: none;">
                                                        <li><a href="javascript:void(0);" class="action-button m-2"
                                                               onclick="showFormModal({{$detail->id}})"><i
                                                                    class="ti ti-edit action-icons"></i></a>
                                                        </li>
                                                        <li><a onclick="deleteProduct('{{$detail->id}}')"
                                                               title="Delete" class="action-button"><i
                                                                    class="ti ti-trash action-icons"></i></a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            @endif
                                        @endif
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

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="head-label text-left">
                    <h5 class="card-title mb-0">Inquiry General Charges</h5>
                </div>
                @if($inquiry->status == 'open' && $inquiry->admin_status == 'Pending')
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons">
                            @if(Auth::user()->hasRole('drafter'))
                                @if($inquiry->admin_status=="Pending")
                                    <button type="button" class="dt-button create-new btn btn-success"
                                            onclick="generalChargesShowFormModal()">
                                        <span>
                                            <i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add / Update</span>
                                        </span>
                                    </button>
                                @endif
                            @else
                                <button type="button" class="dt-button create-new btn btn-success"
                                        onclick="generalChargesShowFormModal()">
                                        <span>
                                            <i class="ti ti-plus me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Add / Update</span>
                                        </span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div id="inquiry-general-charges-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table datatables-basic dataTable no-footer dtr-inline"
                                   id="inquiry-general-charges-table"
                                   aria-describedby="inquiry-general-charges_info"
                                   style="width: 995px;">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Remark/Price & Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($inquiryGeneralCharges as $key => $inquiryGeneralCharge)
                                    <tr>
                                        <td class="sorting_1 dtr-control">{{$key+1}}</td>
                                        <td>{{$inquiryGeneralCharge->generalCharge->name}}
                                            @if(isset($inquiryGeneralCharge->vendor->business_name))
                                            @endif
                                        </td>
                                        <td>{{str_replace('_',' ',$inquiryGeneralCharge->status)}}</td>
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

    @if($inquiry->status == 'open')
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Description/Term Conditions</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('inquiry-master.description.store',$inquiry)}}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-2">
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      rows="10" name="description_term_condition" id="description_term_condition"
                                      placeholder="Enter description/Term Condition">{{$inquiry->description_term_condition ?? ''}}</textarea>
                                @error('description_term_condition')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            @if($inquiry->status == 'open' && $inquiry->admin_status == 'Pending')
                                @if(Auth::user()->hasRole('drafter'))
                                    @if($inquiry->admin_status=="Pending")
                                        <div class="col-md-12 mt-2 text-right">
                                            <button type="submit" class="btn btn-submit text-capitalize">Save
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <div class="col-md-12 mt-2 text-right">
                                        <button type="submit" class="btn btn-submit text-capitalize">Save
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Description/Term Conditions</h5>
                    </div>
                </div>
                <div class="card-body">
                    {!! $inquiry->description_term_condition !!}
                </div>
            </div>
        </div>
    @endif

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="head-label text-left">
                    <h5 class="card-title mb-0">Supported Documents</h5>
                </div>
                @if($inquiry->status == 'open' && $inquiry->admin_status == 'Pending')
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons">
                            @if(Auth::user()->hasRole('drafter'))
                                @if($inquiry->admin_status == "Pending")
                                    <button type="button"
                                            class="dt-button create-new btn btn-success waves-effect waves-light"
                                            onclick="showDocument()">
                                    <span>
                                        <i class="ti ti-plus me-sm-1"></i>
                                        <span class="d-none d-sm-inline-block">Add Document</span>
                                    </span>
                                    </button>
                                @endif
                            @else
                                <button type="button"
                                        class="dt-button create-new btn btn-success waves-effect waves-light"
                                        onclick="showDocument()">
                                    <span>
                                        <i class="ti ti-plus me-sm-1"></i>
                                        <span class="d-none d-sm-inline-block">Add Document</span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
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
                                @foreach($images as $key => $image)
                                    @php
                                        $extension = pathinfo($image->image, PATHINFO_EXTENSION);
                                    @endphp
                                    <tr>
                                        <td class="sorting_1 dtr-control">{{$key+1}}</td>
                                        <td>{{$image->name}}</td>
                                        <td>
                                            <ul class="d-flex pl-0" style="list-style-type: none;">
                                                @if($extension=="pdf" || $extension=="jpg" || $extension=="jpeg" || $extension=="png")
                                                    <li><a href="javascript:void(0);" class="action-button m-2"
                                                           onclick="showImage('{{$image->image}}')"><i
                                                                class="ti ti-eye action-icons"></i></a></li>
                                                    <li></li>
                                                @else
                                                    @if(Auth::user()->hasRole('drafter') && $image->image != "")
                                                        <li>
                                                            <a href="{{ route('inquiry.file.download', ['file' => $image->image]) }}"
                                                               class="action-button m-2"
                                                            ><i class="ti ti-eye action-icons"></i></a></li>
                                                    @elseif($image->image != "")
                                                        <li>
                                                            <a href="{{ route('inquiry-master.file.download', ['file' => $image->image]) }}"
                                                               class="action-button m-2"
                                                            ><i class="ti ti-eye action-icons"></i></a></li>
                                                    @endif
                                                @endif

                                                @if(Auth::user()->hasRole('drafter') && $image->image != "")
                                                    <li>
                                                        <a href="{{ route('inquiry.file.download', ['file' => $image->image]) }}"
                                                           class="action-button m-2"
                                                        ><i
                                                                class="ti ti-download action-icons"></i></a>
                                                    </li>
                                                @elseif($image->image != "")
                                                    <li>
                                                        <a href="{{ route('inquiry-master.file.download', ['file' => $image->image]) }}"
                                                           class="action-button m-2"><i
                                                                class="ti ti-download action-icons"></i></a>
                                                    </li>
                                                @endif
                                                @if($inquiry->status == 'open' && $inquiry->admin_status == 'Pending')
                                                    @if(Auth::user()->hasRole('drafter'))
                                                        @if($inquiry->admin_status == "Pending")
                                                            <li><a onclick="deleteAttachments('{{$image->id}}')"
                                                                   title="Delete" class="action-button"><i
                                                                        class="ti ti-trash action-icons"></i></a>
                                                            </li>
                                                        @endif
                                                    @else
                                                        <li><a onclick="deleteAttachments('{{$image->id}}')"
                                                               title="Delete" class="action-button"><i
                                                                    class="ti ti-trash action-icons"></i></a>
                                                        </li>
                                                    @endif
                                                @endif
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

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="head-label text-left">
                    <h5 class="card-title mb-0">Terms And Conditions</h5>
                </div>
            </div>
            <div class="card-body">
                @foreach($generalTermConditionsCategories as $k => $generalTermConditionsCategory)
                    <div class="col-md-12">
                        <div class="fw-bold mb-3">{{$k+1}}.&nbsp;{{$generalTermConditionsCategory->name}}</div>
                    </div>
                    @foreach($generalTermConditionsCategory->termConditions as $key => $termConditions)
                        <div class="col-md-12">
                            <div class="col-md-12">
                                {!! $termConditions->description !!}
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="head-label text-left">
                    <h5 class="card-title mb-0">Category Wise T&C</h5>
                </div>
            </div>
            <div class="card-body">
                @foreach($termConditionsCategories as $k => $termConditionsCategory)
                    <div class="col-md-12">
                        <div class="fw-bold mb-3">{{$k+1}}.&nbsp;{{$termConditionsCategory->name}}</div>
                    </div>
                    @foreach($termConditionsCategory->termConditions as $key => $termConditions)
                        <div class="col-md-12">
                            <div class="col-md-12">
                                {!! $termConditions->description !!}
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="head-label text-left">
                    <h5 class="card-title mb-0">Terms And Condition Documents</h5>
                </div>
            </div>
            <div class="card-body">
                <div id="terms-and-condition-document-table_wrapper"
                     class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table datatables-basic dataTable no-footer dtr-inline"
                                   id="terms-and-condition-document-table"
                                   aria-describedby="terms-and-condition-document-table_info">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Document</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($termConditionsDocuments as $key => $termConditionsDocument)
                                    <tr>
                                        <td class="sorting_1 dtr-control">{{$key+1}}</td>
                                        <td>{{$termConditionsDocument->name}}</td>
                                        <td>
                                            <a href="{{ asset('document/' . $termConditionsDocument->document) }}"
                                               download>
                                                <i class="ti ti-download"></i>
                                            </a>
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

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="head-label text-left">
                    <h5 class="card-title mb-0">Inquiry Contact Details</h5>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-flex justify-content-end">
                        <button type="button" class="dt-button create-new btn btn-success mb-2"
                                onclick="inquiryContactDetailShowFormModal()">
                                            <span>
                                                <i class="ti ti-plus me-sm-1"></i>
                                                <span class="d-none d-sm-inline-block">Add New</span>
                                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="inquiry-contact-detail-table_wrapper"
                     class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table datatables-basic dataTable no-footer dtr-inline"
                                   id="terms-and-condition-document-table"
                                   aria-describedby="inquiry-contact-detail-table_info">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile Number</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($inquiryContactDetails as $key => $inquiryContactDetail)
                                    <tr>
                                        <td class="sorting_1 dtr-control">{{$key+1}}</td>
                                        <td>{{$inquiryContactDetail->name}}</td>
                                        <td>{{$inquiryContactDetail->email}}</td>
                                        <td>{{'+'.$inquiryContactDetail->country_code.' '.$inquiryContactDetail->mobile_number}}</td>
                                        <td>
                                            <ul class="d-flex pl-0" style="list-style-type: none;">
                                                <li><a href="javascript:void(0);"
                                                       class="action-button m-2"
                                                       onclick="inquiryContactDetailShowFormModal({{$inquiryContactDetail->id}})"><i
                                                            class="ti ti-edit action-icons"></i></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                       onclick="inquiryContactDetailDelete('{{$inquiryContactDetail->id}}')"
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

    <div class="col-md-12 mt-4">
        <button id="scrollToTop">
            <i class="ti ti-arrow-up"></i>
        </button>
    </div>

    <div class="modal fade" id="productModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="productFormModalTitle">Products</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <div id="inquiry-product-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-md-12">

                                <table class="table datatables-basic dataTable no-footer dtr-inline"
                                       id="inquiry-product-table" aria-describedby="inquiry-product-table_info"
                                       style="width: 995px;">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Category</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($productInquires as $detail)
                                        <tr>
                                            <td><input type="hidden" id="vendor_table_id"><input type="checkbox"
                                                                                                 name="products[]"
                                                                                                 id="product_{{$detail->id}}"
                                                                                                 value="{{$detail->id}}"
                                                                                                 checked></td>
                                            <td>{{$detail->category}}</td>
                                            <td>{{$detail->name}}</td>
                                            <td>{{$detail->price}}</td>
                                            <td>{{$detail->qty}}</td>
                                            <td>{{$detail->unit}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick="submitProduct()">Allocate
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
                        <input type="hidden" id="product_id" name="product_id" value="">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Item Description</label>
                                <textarea class="form-control" name="item_description" id="item_description"></textarea>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Additional Info</label>
                                <textarea class="form-control" name="additional_info" id="additional_info"></textarea>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Qty<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" required name="qty" id="qty"
                                       placeholder="Enter Product Qty">
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Budget</label>
                                <input type="number" step="any" class="form-control" name="price" id="price"
                                       placeholder="Enter Product Budget">
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Unit</label>
                                <input type="text" class="form-control" name="unit" id="unit"
                                       placeholder="Enter Product Unit">
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

    <div class="modal fade" id="importExcelFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="importExcelFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="importExcelFormModalTitle">Import</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                    <a href="{{asset('assets/sample_files/Product_File.csv')}}"
                       class="btn btn-custom-primary mr-3">Sample File</a>
                </div>
                <div class="modal-body">
                    <form method="post" id="importExcelForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="file" class="form-label">Excel File<span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="file" id="file" accept=".csv">
                                <span class="error" style="color: red">Note : To ensure that the item_description, qty, and unit fields are not blank and that qty is more than 0</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit text-capitalize"
                            onclick="submitTermsImport()">Import
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="inquiryGeneralChargesFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="inquiryGeneralChargesFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="inquiryGeneralChargesFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="inquiryGeneralChargesForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            @foreach($generalCharges as $key => $generalCharge)
                                <div class="col-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               value="{{$generalCharge->id}}"
                                               id="general_charge_{{$generalCharge->id}}"
                                               name="general_charges_name[]"
                                               @if(isset($generalChargesData[$generalCharge->id])) checked @endif>
                                        <label class="form-check-label"
                                               for="general_charge_{{$generalCharge->id}}">
                                            {{$generalCharge->name}}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="general_charge_type_[{{$generalCharge->id}}]"
                                               id="remark_{{$generalCharge->id}}"
                                               value="only_remark"
                                               @if(isset($generalChargesData[$generalCharge->id]) && $generalChargesData[$generalCharge->id]['status'] === 'only_remark') checked @endif>
                                        <label class="form-check-label" for="remark_{{$generalCharge->id}}">
                                            Only Remark
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="general_charge_type_[{{$generalCharge->id}}]"
                                               id="price_qty_{{$generalCharge->id}}"
                                               value="with_price_qty"
                                               @if(!isset($generalChargesData[$generalCharge->id]) || $generalChargesData[$generalCharge->id]['status'] === 'with_price_qty') checked @endif>
                                        <label class="form-check-label" for="price_qty_{{$generalCharge->id}}">
                                            With Price & Qty
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-12 mb-2 text-danger" id="general_charges_error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick=" generalChargesSubmitForm()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="document" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="importExcelFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="supportDocumentForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="inquiry_id" value="{{$inquiry->id}}">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="supportDocumentFormModalTitle">Add Supported Document</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="ti ti-x close-button-icon"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="type" class="form-label">Document Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="type" class="form-label">Supported Documents<span
                                        class="text-danger">*</span></label>
                                <input type="file" class="filepond" name="images[]" multiple
                                       data-max-files="10" accept=".jpg.jpeg.png.pdf.csv" required>
                            </div>
                            <div class="col-md-12 mb-2 text-danger">Note : Supported documents are
                                .jpg, .jpeg, .png,
                                .pdf, .csv
                            </div>
                            <div class="col-md-12 mb-2 text-danger" id="image_error_doc"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect waves-light"
                                data-bs-dismiss="modal">Close
                        </button>
                        <button type="button" class="btn btn-submit" onclick="supportDocumentSubmit()">Submit</button>
                    </div>
                </form>
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
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="allocateFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="allocateFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="allocateFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="allocateForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="allocate_id" name="allocate_id" value="">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Vendor<span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2" name="vendor_id" id="vendor_id1">
                                    <option value="" selected hidden="">Select One</option>
                                    @foreach($vendors as $vendor)
                                        @if(@$vendor->user->id)
                                            <option
                                                value="{{$vendor->user->id}}">{{$vendor->business_name}}</option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Branch<span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2" name="branch_id" id="branch_id">
                                    <option value="" selected hidden="">Select One</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-submit" onclick="submitAllocation()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="compareFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="allocateFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="allocateFormModalTitle">Compare Vendor Price</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="" method="get" enctype="multipart/form-data"
                          action="{{ route('inquiry-master.compare-product') }}">
                        <input type="hidden" id="inquiry_id" name="inquiry_id" value="{{$inquiry->id}}">
                        <input type="hidden" id="vendor_id" name="vendor_id" value="{{@$vendor->user_id}}">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Select Product<span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2" name="products[]" id="products" multiple>
                                    <option value="all">All</option>
                                    @foreach($products as $key => $product)
                                        <option value="{{$product->id}}">{{$product->item_description}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="name" class="form-label">Select Vendor<span
                                        class="text-danger">*</span></label>
                                <select class="form-control select2" name="vendor[]" id="vendor" multiple>
                                    <option value="all">All</option>
                                    @foreach($vendorDetails as $key => $vendorDetail)
                                        <option
                                            value="{{@$vendorDetail->vendor->user_id}}">{{@$vendorDetail->vendor->business_name}}</option>
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

    <div class="modal fade" id="adminStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="userFormModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tit">Changes Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('inquiry-master.admin-status',$inquiry)}}" method="post"
                          id="inquiryStatusForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="admin_status"
                                           id="inlineRadio1"
                                           value="Approved"
                                           @if($inquiry->admin_status == 'Approved') checked @endif>
                                    <label class="form-check-label" for="inlineRadio1">Approved</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="admin_status"
                                           id="inlineRadio2"
                                           value="Pending" @if($inquiry->admin_status == 'Pending') checked @endif>
                                    <label class="form-check-label" for="inlineRadio2">Pending</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="admin_status"
                                           id="inlineRadio3"
                                           value="Rejected"
                                           @if($inquiry->admin_status == 'Rejected') checked @endif>
                                    <label class="form-check-label" for="inlineRadio3">Rejected</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="comment" class="form-label">Comment</label>
                                <input type="text" class="form-control" name="comment" id="comment"
                                       placeholder="Enter Comment">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <img src="{{asset('assets/images/loader.gif')}}" class="d-none" id="submit-all-loader"
                         style="width: 25px;"
                         alt="loader">
                    <button type="button" class="btn btn-submit status-loader" onclick="submitAdminStatus()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!------ Inquiry Approver Modal ------>
    <div class="modal fade" id="inquiryApproverModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="inquiryApproverModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Inquiry Approvers</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body" id="inquiryApproverModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" id="submitApproverButton"
                            onclick="submitApproverForm()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!------ Inquiry Approver Status Modal ------>
    <div class="modal fade" id="approvalStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="approvalFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Changes Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('inquiry-master.approval.status',$inquiry)}}" method="post"
                          id="approvalStatusForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="approval_status"
                                           id="approvalInlineRadio2" value="pending" checked>
                                    <label class="form-check-label" for="approvalInlineRadio2">Pending</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="approval_status"
                                           id="approvalInlineRadio1" value="approved">
                                    <label class="form-check-label" for="approvalInlineRadio1">Approved</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="approval_status"
                                           id="approvalInlineRadio3" value="rejected">
                                    <label class="form-check-label" for="approvalInlineRadio3">Rejected</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="approval_remark" class="form-label">Remark</label>
                                <textarea class="form-control" rows="6" name="remark" id="approval_remark"
                                          placeholder="Enter Remark"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <img src="{{asset('assets/images/loader.gif')}}" class="d-none" id="approval-submit-all-loader"
                         style="width: 25px;" alt="loader">
                    <button type="button" class="btn btn-submit approval-status-loader"
                            onclick="submitApprovalStatus()">Save
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

    <!------ Inquiry Contact Detail Modal ------>
    <div class="modal fade" id="inquiryContactDetailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="inquiryContactDetailFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="inquiryContactDetailFormModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="inquiryContactDetailForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="inquiry_contact_detail_id">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="email" class="form-label">Email<span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email"
                                       placeholder="Enter Email">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="mobile_number" class="form-label">Mobile Number<span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="mobile_number" id="mobile_number"
                                       placeholder="Enter Mobile Number">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick="InquiryContactDetailSubmitForm()">Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!------ Send Mail Modal ------>
    <div class="modal fade" id="sendMailFormModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="sendMailFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="sendMailFormModalTitle">Send Mail</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="ti ti-x close-button-icon"></i></button>
                </div>
                <div class="modal-body">
                    <form id="sendMailForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="send_mail_vendor_id">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="mail_description" class="form-label">Description<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" rows="10" name="mail_description" id="mail_description"
                                          placeholder="Enter Description"></textarea>
                            </div>
                            <div class="col-md-12 mb-3">Send To<span class="text-danger">*</span></div>
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="vendor"
                                           name="send_mail_to_users[]" id="send_mail_vendor">
                                    <label class="form-check-label" for="send_mail_vendor">
                                        Vendor
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="admin_drafter"
                                           name="send_mail_to_users[]" id="send_mail_admin_drafter">
                                    <label class="form-check-label" for="send_mail_admin_drafter">
                                        Admins/Drafters
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="contact_persons"
                                           name="send_mail_to_users[]" id="send_mail_contact_persons">
                                    <label class="form-check-label" for="send_mail_contact_persons">
                                        Contact Persons
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-submit" onclick="sendMailSubmitForm()">Send
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function showFullDescription(description) {
            $('#fullDescriptionFormModal').modal('show');
            $('#full-description-modal-body').text(description);
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


        function showImage(image) {
            let extension = image.split('.').pop().toLowerCase();
            let baseUrl = '{{ asset('images/') }}'
            let filePath = baseUrl + '/' + image;

            $("#imageFormModal").modal('show');

            if (extension == "pdf" || extension == "xlsx") {
                $('.img-1').html('<embed src="' + filePath + '" type="application/pdf" width="100%" height="600px"/>')
            } else {
                $('.img-1').html('<img src="' + filePath + '" id="attachmentPreview" alt="Attachment Preview" class="custom-image"/>')
            }
        }

        function deleteProduct(id) {
            let route = '{{route('inquiry.delete-product')}}'
            @if(Auth::user()->hasRole('admin'))
                route = '{{route('inquiry-master.delete-product',$inquiry)}}'
            @endif

            Swal.fire({
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
                        data: {
                            _token: '{{csrf_token()}}',
                            'id': id,
                        }, success: function (response) {
                            if (response.status === true) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Record has been deleted.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                window.location.reload()
                            } else {
                                Swal.fire({
                                    text: response.message, icon: "warning",
                                });
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 500)
                        },
                    });
                }
            });
        }

        function deleteData(id) {
            Swal.fire({
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
                        url: '{{route('inquiry-master.allocate-delete',$inquiry)}}',
                        data: {
                            _token: '{{csrf_token()}}',
                            'id': id,
                        }, success: function (response) {
                            if (response.status === true) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Record has been deleted.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: "warning",
                                });
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 500)
                        },
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

            $('#userForm').attr('action', '{{route('inquiry.product-store',$inquiry)}}');
            @if(Auth::user()->hasRole('admin'))
            $('#userForm').attr('action', '{{route('inquiry-master.product-store',$inquiry)}}');
            @endif

            if (id != '') {
                $('#userFormModalTitle').text('Update');
                $('#password_blank_message').removeClass('d-none');

                let route = '{{route('inquiry.product-edit',$inquiry)}}'
                @if(Auth::user()->hasRole('admin'))
                    route = '{{route('inquiry-master.product-edit',$inquiry)}}'
                @endif

                $.ajax({
                    type: 'post',
                    url: route,
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        console.log(response.data);
                        if (response.status == true) {
                            $("#product_id").val(response.data.id);

                            $('#userForm').attr('action', '{{route('inquiry.product-update',[$inquiry],'uuid')}}'.replace('uuid', response.data.uuid));
                            @if(Auth::user()->hasRole('admin'))
                            $('#userForm').attr('action', '{{route('inquiry-master.product-update',[$inquiry],'uuid')}}'.replace('uuid', response.data.uuid));
                            @endif

                            $('#item_description').val(response.data.item_description);
                            $('#additional_info').val(response.data.additional_info);
                            $('input[name="qty"]').val(response.data.qty);
                            $('input[name="price"]').val(response.data.price);
                            $('input[name="unit"]').val(response.data.unit);
                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function updateCKEditor(elementId, data) {
            const element = document.getElementById(elementId);
            const existingEditor = element.ckeditorInstance;

            if (existingEditor) {
                existingEditor.setData(data);
            } else {
                ClassicEditor
                    .create(element, {
                        toolbar: ['bold', 'italic', 'underline'], // Configure toolbar
                        enterMode: 1, // Define behavior for Enter
                        shiftEnterMode: 2 // Define behavior for Shift + Enter
                    })
                    .then(editor => {
                        // Store the instance reference for future use
                        element.ckeditorInstance = editor;
                        editor.setData(data);
                    })
                    .catch(error => {
                        console.error(`Error initializing CKEditor for ${elementId}:`, error);
                    });
            }
        }

        function showAllocateModal(id = '') {
            $('#allocateFormModal').modal('show');

            let inputInvalid = $('#allocateForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#allocateFormModalTitle').text('Add New');

            $('#allocateForm').attr('action', '{{route('inquiry.allocate-store',$inquiry)}}');
            @if(Auth::user()->hasRole('admin'))
            $('#allocateForm').attr('action', '{{route('inquiry-master.allocate-store',$inquiry)}}');
            @endif

            if (id != '') {
                $('#allocateFormModalTitle').text('Update');
                $('#password_blank_message').removeClass('d-none');

                let route = '{{route('inquiry.allocate-edit',$inquiry)}}';
                @if(Auth::user()->hasRole('admin'))
                    route = '{{route('inquiry-master.allocate-edit',$inquiry)}}';
                @endif

                $.ajax({
                    type: 'post',
                    url: route,
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        console.log(response.data);
                        if (response.status == true) {
                            $("#allocate_id").val(response.data.id);

                            $('#allocateForm').attr('action', '{{route('inquiry.allocate-update',[$inquiry],'uuid')}}'.replace('uuid', response.data.uuid));
                            @if(Auth::user()->hasRole('admin'))
                            $('#allocateForm').attr('action', '{{route('inquiry-master.allocate-update',[$inquiry],'uuid')}}'.replace('uuid', response.data.uuid));
                            @endif

                            $('#vendor_id').val(response.data.vendor_id).trigger('change');
                            let options = '<option value="" selected hidden="">Select City</option>';
                            $.each(response.cities, function (key, city) {
                                options += '<option value="' + city.id + '">' + city.name + '</option>';
                            });
                            $('#branch_id').html(options);
                            $('#branch_id').val(response.data.city_id).trigger('change');
                        }
                    },
                });
            }
        }

        function submitForm() {
            if (document.getElementById('additional_info').ckeditorInstance) {
                document.getElementById('additional_info').ckeditorInstance.updateSourceElement();
            }
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

        function submitAllocation() {
            let url = $('#allocateForm').attr('action');
            let formData = new FormData($('#allocateForm')[0]);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#allocateFormModal').modal('hide');
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
                    $('#allocateForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#allocateForm').find('[name="' + field + '"]');
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

        function submitTermsImport() {
            let formData = new FormData($('#importExcelForm')[0]);

            let route = '{{route('inquiry.import',$inquiry)}}';
            @if(Auth::user()->hasRole('admin'))
                route = '{{route('inquiry-master.import',$inquiry)}}';
            @endif

            $.ajax({
                type: 'post',
                url: route,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#importExcelFormModal').modal('hide');
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
                    $('#term-conditions-categories-table').DataTable().draw();
                    location.reload();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#importExcelForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#importExcelForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        function deleteAttachments(imageId) {
            let inquiry_id = '{{$inquiry->id}}';
            let route = '{{ route('inquiry.delete-product-data') }}';

            @if(Auth::user()->hasRole('admin'))
                route = '{{ route('inquiry-master.delete-product-data') }}';
            @endif

            Swal.fire({
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
                            'image_id': imageId,
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

        function changeStatus(inquiry_id, vendor_id, status) {
            Swal.fire({
                text: "Do you want to change the status?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, change it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let route = '{{ route('inquiry.change-status') }}';
                    @if(Auth::user()->hasRole('admin'))
                        route = '{{ route('inquiry-master.change-status') }}';
                    @endif

                    $.ajax({
                        type: 'post',
                        url: route,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'status': status,
                            'vendor_id': vendor_id,
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
                            $('#inquiry-table').DataTable().draw();
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
            });
        }

        $('#vendor_id1').change(function () {
            let route = "{{route('inquiry.get-city')}}";
            @if(Auth::user()->hasRole('admin'))
                route = "{{route('inquiry-master.get-city')}}";
            @endif

            let stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    type: 'post',
                    url: route,
                    data: {
                        vendorId: stateId,
                        _token: "{{csrf_token()}}",
                    },
                    success: function (response) {
                        let options = '<option value="" disabled>Select City</option>';
                        $.each(response.data, function (key, city) {
                            options += '<option value="' + city.id + '">' + city.name + '</option>';
                        });
                        $('#branch_id').html(options);
                    }
                });
            } else {
                $('#branch_id').html('<option value="" disabled>Select City</option>');
            }
        });

        $(".select2").select2();

        function showProductModal(vendor_id) {
            let vendor = $("#vendor_table_id").val(vendor_id);
            let inquiry_id = '{{$inquiry->id}}';
            $("#productModal").modal('show');
        }

        function submitProduct() {
            let vendor = $("#vendor_table_id").val();
            let inquiry_id = '{{$inquiry->id}}';
            let checkedValues = [];

            $('input[name="products[]"]:checked').each(function () {
                checkedValues.push($(this).val());
            });

            $.ajax({
                type: 'post',
                url: '{{route('inquiry.update-product')}}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'product_id': checkedValues,
                    'vendor_id': vendor,
                    'inquiry_id': inquiry_id
                },
                success: function (response) {
                    if (response.status == true) {
                        $("#productModal").modal('hide');
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

        function showDocument() {
            $('#document').modal('show')
        }

        function compareProduct() {
            $("#compareFormModal").modal('show');
        }

        function submitProductData(inquiry_id, vendor_id) {
            let selectElement = document.getElementById('vendor');

            let selectedValues = [];

            for (let i = 0; i < selectElement.options.length; i++) {
                if (selectElement.options[i].selected) {
                    selectedValues.push(selectElement.options[i].value);
                }
            }

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

        function generalChargesShowFormModal() {
            $('#inquiryGeneralChargesFormModal').modal('show');

            let inputInvalid = $('#inquiryGeneralChargesForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#inquiryGeneralChargesFormModalTitle').text('Add / Update');

            $('#inquiryGeneralChargesForm').attr('action', '{{route('inquiry.general.charge.store',$inquiry)}}');
            @if(Auth::user()->hasRole('admin'))
            $('#inquiryGeneralChargesForm').attr('action', '{{route('inquiry-master.general.charge.store',$inquiry)}}');
            @endif
        }

        function generalChargesSubmitForm() {
            let url = $('#inquiryGeneralChargesForm').attr('action');
            let formData = new FormData($('#inquiryGeneralChargesForm')[0]);

            let selectFieldLength = $('input[type="checkbox"][name="general_charges_name[]"]:checked').length;

            $('#general_charges_error').text('')
            if (selectFieldLength <= 0) {
                $('#general_charges_error').text('Please select name');
                return;
            }

            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#inquiryGeneralChargesFormModal').modal('hide');
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
                    $('#inquiryGeneralChargesForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#inquiryGeneralChargesForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        function generalChargesDelete(url) {
            Swal.fire({
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
                        url: url,
                        data: {
                            _token: '{{csrf_token()}}',
                        }, success: function (response) {
                            if (response.status === true) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.message,
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    text: response.message, icon: "warning",
                                });
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 1000)
                        },
                    });
                }
            });
        }

        function changeAdminStatus() {
            $("#adminStatusModal").modal('show');
        }

        function submitAdminStatus() {
            let url = $('#inquiryStatusForm').attr('action');
            let formData = new FormData($('#inquiryStatusForm')[0]);

            $('.status-loader').addClass('d-none');
            $('#submit-all-loader').removeClass('d-none');

            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#adminStatusModal').modal('hide');
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
            })
        }

        let selectedApprovers = [];

        function showApprovers(detailUrl, submitUrl) {
            $('#inquiryApproverModalBody').empty();
            selectedApprovers = [];

            $('#inquiryApproverModal').modal('show');
            $('#submitApproverButton').attr('onclick', 'submitApproverForm(`' + submitUrl + '`)')

            $.ajax({
                url: detailUrl,
                method: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                },
                success: function (response) {
                    if (response.status == true) {
                        $('#inquiryApproverModalBody').html(response.data.html);
                        selectedApprovers = [];

                        $('.inquiry-approvers-checkbox:checked').each(function () {
                            const userId = $(this).data('id');
                            const userName = $(this).data('name');
                            selectedApprovers.push({id: userId, name: userName});
                        });
                        inquiryApproversListUpdateTable();
                    }
                },
            });
        }

        $('#inquiryApproverModalBody').on('change', '.inquiry-approvers-checkbox', function () {
            const userId = $(this).data('id');
            const userName = $(this).data('name');

            if ($(this).is(':checked')) {
                selectedApprovers.push({id: userId, name: userName});
            } else {
                const index = selectedApprovers.findIndex(user => user.id === userId);
                if (index !== -1) {
                    selectedApprovers.splice(index, 1);
                }
            }
            inquiryApproversListUpdateTable();
        });

        function inquiryApproversListUpdateTable() {
            $('#inquiryApproversListTable').empty();

            selectedApprovers.forEach(function (user, index) {
                const row = `<tr id="row-${user.id}">
                    <td>${index + 1}</td>
                    <td>${user.name}</td>
                 </tr>`;
                $('#inquiryApproversListTable').append(row);
            });
        }

        function submitApproverForm(url) {
            $('#approver_error').text('');
            if (selectedApprovers.length <= 0 || selectedApprovers == null || selectedApprovers == undefined) {
                $('#approver_error').text('Approver field is required!');
                return;
            }
            let remark = $('#inquiry_approval_remark').val();
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    _token: '{{csrf_token()}}',
                    data: selectedApprovers,
                    remark: remark,
                },
                success: function (response) {
                    $('#inquiryApproverModal').modal('hide');
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
            });
        }

        function approverSendMail() {
            Swal.fire({
                text: "Are you sure want to send for approval?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, send it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.send-for-approval').addClass('d-none');
                    $('#send-for-approval-loader').removeClass('d-none');
                    $.ajax({
                        type: 'post',
                        url: '{{route('inquiry-master.approver.mail.send',$inquiry)}}',
                        data: {
                            _token: '{{csrf_token()}}',
                        }, success: function (response) {
                            if (response.status === true) {
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
                                });
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 1000)
                        },
                    });
                }
            });
        }

        function submitApprovalStatus() {
            let url = $('#approvalStatusForm').attr('action');
            let formData = new FormData($('#approvalStatusForm')[0]);

            $('.approval-status-loader').addClass('d-none');
            $('#approval-submit-all-loader').removeClass('d-none');

            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#approvalStatusModal').modal('hide');
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
                    $('#approvalStatusForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#approvalStatusForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            })
        }

        function followUpVendor(id) {
            Swal.fire({
                text: "Are you sure want to follow up?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, follow up it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.follow-up-vendor').addClass('d-none');
                    $('#follow-up-vendor-loader').removeClass('d-none');
                    $.ajax({
                        type: 'post',
                        url: '{{route('inquiry-master.follow.up',$inquiry)}}',
                        data: {
                            _token: '{{csrf_token()}}',
                            'id': id,
                        }, success: function (response) {
                            if (response.status === true) {
                                Swal.fire({
                                    //title: "follow up!",
                                    text: response.message,
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: "warning",
                                });
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        },
                    });
                }
            });
        }

        ClassicEditor
            .create(document.getElementById("description_term_condition"))
            .then(editor => {
            })
            .catch(error => {
                console.error('Error during initialization of the editor', error);
            });


        document.getElementById("approval_remark").addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.stopPropagation();
            }
        });

        document.getElementById("item_description").addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.stopPropagation(); // Avoid bubbling up
            }
        });

        document.getElementById("additional_info").addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.stopPropagation(); // Avoid bubbling up
            }
        });
        document.getElementById("mail_description").addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                event.stopPropagation(); // Avoid bubbling up
            }
        });


        function inquiryAwardStore(id) {
            Swal.fire({
                text: "Are you sure want to give allocation for this inquiry?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, allocation it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: '{{route('inquiry-master.inquiry.award.store',$inquiry)}}',
                        data: {
                            _token: '{{csrf_token()}}',
                            'vendor_id': id,
                        }, success: function (response) {
                            if (response.status === true) {
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
                                });
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 1000)
                        },
                    });
                }
            });
        };


        //************* Inquiry Contact Detail  *************//
        const input = document.querySelector("#mobile_number");
        const iti = window.intlTelInput(input, {
            initialCountry: "in",
            preferredCountries: ["in"],
            utilsScript: "assets/js/utils.js"
        });

        const dialCodeToCountryCode = {};
        const allCountries = window.intlTelInputGlobals.getCountryData();

        allCountries.forEach(country => {
            dialCodeToCountryCode[country.dialCode] = country.iso2;
        });

        function inquiryContactDetailShowFormModal(id = '') {
            $('#inquiryContactDetailFormModal').modal('show');

            let inputInvalid = $('#inquiryContactDetailForm').find('.is-invalid');
            inputInvalid.removeClass('is-invalid');
            $('.error-message').remove();

            $('#inquiryContactDetailFormModalTitle').text('Add New');
            $('#inquiryContactDetailForm').attr('action', '{{route('inquiry-master.inquiry.contact.detail.store',$inquiry)}}');

            if (id != '') {
                $('#inquiryContactDetailFormModalTitle').text('Update');

                $.ajax({
                    type: 'post',
                    url: '{{route('inquiry-master.inquiry.contact.detail.edit',$inquiry)}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#inquiryContactDetailForm').attr('action', '{{route('inquiry-master.inquiry.contact.detail.update',[$inquiry,'uuid'])}}'.replace('uuid', response.data.uuid));

                            $('input[name="inquiry_contact_detail_id"]').val(response.data.id);
                            $('input[name="name"]').val(response.data.name);
                            $('input[name="email"]').val(response.data.email);
                            const countryIsoCode = dialCodeToCountryCode[response.data.country_code];
                            if (countryIsoCode) {
                                iti.setCountry(countryIsoCode);
                            }
                            $('input[name="mobile_number"]').val(response.data.mobile_number);
                        }
                    },
                    error: function (error) {
                    }
                });
            }
        }

        function InquiryContactDetailSubmitForm() {
            let countryCode = iti.getSelectedCountryData().dialCode;
            let url = $('#inquiryContactDetailForm').attr('action');
            let formData = new FormData($('#inquiryContactDetailForm')[0]);
            formData.append('country_code', countryCode);
            $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#inquiryContactDetailFormModal').modal('hide');
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
                    }, 1000);
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#inquiryContactDetailForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#inquiryContactDetailForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

        function inquiryContactDetailDelete(id) {
            Swal.fire({
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
                        url: '{{route('inquiry-master.inquiry.contact.detail.delete',$inquiry)}}',
                        data: {
                            _token: '{{csrf_token()}}',
                            'id': id,
                        }, success: function (response) {
                            if (response.status === true) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Record has been deleted.",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                window.location.reload()
                            } else {
                                Swal.fire({
                                    text: response.message, icon: "warning",
                                });
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 500)
                        },
                    });
                }
            });
        }

        function sendMailShowFormModal(id, vendorName, vendorCityName, totalAmount, gstTotalAmount, grandTotal) {
            let subject = '{{$inquiry->remarks}}';
            let projectName = '{{$inquiry->name}}';

            let mailDescription =
                'Dear Mr/Ms ' + vendorName + ',\n\n' +
                'This is with reference to trail mail, quotes received, subsequent discussion and negotiation done regarding Supply/Execution of ' + subject +
                'of ' + projectName + ' at (' + vendorCityName + ')\n\n' +
                'We are pleased to place order for said scope as per below mentioned details and attached documents\n\n' +
                'Note:-' +
                'The rates mentioned in attached are inclusive of all taxes and duties whichever and wherever applicable, except GST which shall be payable extra as applicable' +
                'Transportation/Delivery/Mobilization details if any\n\n' +
                'Below listed as well as attached documents shall be forming part of this Purchase/work order confirmation:\n' +
                'Basic Amount: ' + totalAmount + '\n' +
                'GST on Work/Service : ' + gstTotalAmount + '\n' +
                'Total Amount with GST : ' + grandTotal + '\n\n' +
                'Detailed order for the same shall be issued to you shortly, till then you are requested to coordinate at concern team with company representative for work schedule as well as submit compliance documents if applicable as per details mentioned in attached documents to concern person within 7 days from this order confirmation.\n\n' +
                'Client’s End Coordination Details:\n' +
                'Project Team (Site/Admin/Design):\n' +
                'Name: Mr. Saurabh Kathiriya\n' +
                'Mobile: +91-87806 81819\n' +
                'Email: saurabh.kathiriya@alembic.co.in\n\n' +
                'Contractor’s/Vendor`s end coordination details\n' +
                'Name: Mr. Samir Patel\n' +
                'Mobile: +91-99798 96469\n' +
                'Email: samirpatel26977@gmail.com';

            $('#mail_description').text(mailDescription);
            $('#sendMailFormModal').modal('show');
            $('input[name="send_mail_vendor_id"]').val(id);
        };

        function sendMailSubmitForm() {
            let formData = new FormData($('#sendMailForm')[0]);
            $.ajax({
                type: 'post',
                url: '{{route('inquiry-master.send.mail.store',$inquiry)}}',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#sendMailFormModal').modal('hide');
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
                    }, 1000);
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#sendMailForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#sendMailForm').find('[name="' + field + '"], [name="' + field + '[]"]');
                        if (inputField.attr('type') === 'checkbox' || inputField.attr('type') === 'radio') {
                            let firstCheckbox = inputField.last();
                            let label = firstCheckbox.closest('.form-check').find('label');
                            label.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                        } else {
                            inputField.addClass('is-invalid');
                            inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                        }
                    });
                }
            });
        }


        function supportDocumentSubmit() {
            let actionUrl = "{{ Auth::user()->hasRole('admin') ? route('inquiry-master.store-product-data') : route('inquiry.store-product-data') }}";

            $('#supportDocumentForm').attr('action', actionUrl);
            let url = $('#supportDocumentForm').attr('action');
            let formData = new FormData($('#supportDocumentForm')[0]);
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
                    $('#document').modal('hide');
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
                    $('#supportDocumentForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField;
                        if (field.includes('images')) {
                            inputField = $('#supportDocumentForm').find('[name="images[]"]');
                        } else {
                            inputField = $('#supportDocumentForm').find('[name="' + field + '"]');
                        }
                        inputField.addClass('is-invalid');
                        if (inputField.attr('type') === 'file') {
                            $('#image_error_doc').html(messages.join('<br>'));
                        } else {
                            inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                            //$('#image_error_doc').html(messages.join('<br>'));
                        }
                    });
                }
            });
        }
    </script>
@endpush
