@extends('backend.layouts.app')
@section('title')
    Notifications
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
        <div class="col-md mb-6 mb-md-0">
            <div class="card">
                <h5 class="card-header">Notification Filter</h5>
                <div class="card-body">
                    <form method="get" action="{{route('notifications.index')}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="inquiry_date">Select Date</label>
                                        <input type="text" class="form-control" name="inquiry_date" id="inquiry_date"
                                               value="{{ request()->inquiry_date ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="module">Module</label>
                                        <select class="form-control" name="module" id="module">
                                            <option value="">All</option>
                                            <option
                                                    value="Inquiry" {{ request()->module=="Inquiry" ? 'selected' : "" }}>
                                                Inquiry
                                            </option>
                                            <option
                                                    value="vendor_registration" {{ request()->module=="vendor_registration" ? 'selected' : "" }}>
                                                Vendor Registration
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="status">Vendor</label>
                                        <select class="form-control select2" name="vendor_name" id="vendor_name">
                                            <option value="">All</option>
                                            @foreach($filterVendors as $filterVendor)
                                                <option
                                                        value="{{$filterVendor->user_id}}" {{request()->vendor_name == $filterVendor->user_id ? 'selected' : "" }}>
                                                    {{$filterVendor->business_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <button type="submit" class="btn btn-submit"><i
                                                    class="ti ti-filter"></i>&nbsp;Apply
                                        </button>
                                        <a href="{{route('notifications.index')}}"
                                           class="btn btn-danger"><i
                                                    class="ti ti-reload"></i>&nbsp;Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        @if(request()->inquiry_date!=null || request()->end_date!=null || request()->module!=null)
                            <h5 class="card-title mb-0">Notifications</h5>
                        @else
                            <h5 class="card-title mb-0">Today's Notification</h5>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table w-100"
                                   id="inquiry-vendor-table" aria-describedby="inquiry-vendor-table_info">
                                <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Date</th>
                                    <th>Module</th>
                                    <th>Vendor Name</th>
                                    <th colspan="2">Title</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($notifications as $key => $notification)
                                    @php
                                        if($notification->from=="drafter"){
                                             $vendor = \App\Models\User::where('id',$notification->user_id)->first();
                                        } else {
                                             $vendor = \App\Models\Vendor::where('user_id',$notification->user_id)->first();
                                        }
                                            $inquiry = \App\Models\ResInquiryMaster::find($notification->inquiry_id);
                                    @endphp
                                    @if(Auth::user()->hasRole('admin'))
                                        @if($notification->module == "Inquiry")
                                            <tr class="table-primary">
                                                <td>{{$key+1}}</td>
                                                <td>{{$notification->created_at->format('d-m-Y h:i A')}}</td>
                                                <td>{{$notification->module}}</td>
                                                @if($notification->from=="drafter")
                                                    <td>{{$vendor->name}}</td>
                                                @else
                                                    <td>{{isset($vendor->business_name)?$vendor->business_name:''}}</td>
                                                @endif
                                                <td colspan="2"><b>{{$notification->title}}</b></td>
                                                <td>
                                                    <form id="" method="get"
                                                          enctype="multipart/form-data"
                                                          action="{{ route('inquiry-master.compare-product') }}">
                                                        <input type="hidden" id="inquiry_id"
                                                               name="inquiry_id"
                                                               value="{{$inquiry->id}}">
                                                        <input type="hidden" id="vendor_id"
                                                               name="vendor_id"
                                                               value="{{@$vendor->user_id}}">
                                                        <input type="hidden" id="vendor_id"
                                                               name="products[]" value="all">
                                                        <input type="hidden" id="vendor_id"
                                                               name="vendor[]"
                                                               value="{{@$vendor->user_id}}">
                                                        <button type="submit"
                                                                class="action-button border-0 bg-transparent">
                                                            <i class="ti ti-eye action-icons"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @elseif($notification->module == "new_inquiry")
                                            <tr class="table-primary">
                                                <td>{{$key+1}}</td>
                                                <td>{{$notification->created_at->format('d-m-Y h:i A')}}</td>
                                                <td>New Inquiry</td>
                                                <td>{{isset($vendor->name) ? $vendor->name : ''}}</td>
                                                <td colspan="2"><b>{{$notification->title}}</b></td>
                                                <td>
                                                    @if(Auth::user()->hasRole('admin') && $inquiry != null)
                                                        <a href="{{route('inquiry-master.detail',[$inquiry])}}"><i
                                                                    class="ti ti-eye action-icons"></i></a>
                                                    @elseif($inquiry != null && $vendor != null)
                                                        <a href="{{route('inquiry.vendor-product-details',[$inquiry,$vendor])}}"><i
                                                                    class="ti ti-eye action-icons"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @elseif($notification->module=="vendor_registration")
                                            <tr class="table-secondary">
                                                <td>{{$key+1}}</td>
                                                <td>{{$notification->created_at->format('d-m-Y h:i A')}}</td>
                                                <td>Vendor Registration</td>
                                                <td>{{isset($vendor->business_name) ? $vendor->business_name : ''}}</td>
                                                <td colspan="2"><b>{{$notification->title}}</b></td>
                                                <td>
                                                    <a href="{{route('vendors.branch.detail',[isset($vendor)? $vendor : ''])}}"><i
                                                                class="ti ti-eye action-icons"></i></a>
                                                </td>
                                            </tr>
                                        @elseif($notification->module == "vendor_document_upload")
                                            <tr class="table-secondary">
                                                <td>{{$key+1}}</td>
                                                <td>{{$notification->created_at->format('d-m-Y h:i A')}}</td>
                                                <td>Vendor Document</td>
                                                <td>{{isset($vendor->business_name) ? $vendor->business_name : ''}}</td>
                                                <td colspan="2"><b>{{$notification->title}}</b></td>
                                                <td>
                                                    <a href="{{route('vendors.vendor.document',[isset($vendor)? $vendor : ''])}}"><i
                                                                class="ti ti-eye action-icons"></i></a>
                                                </td>
                                            </tr>
                                        @elseif($notification->module == "branch_document_upload")
                                            @php  $vendor = isset($vendor) ? $vendor : '';
                                                        $branch = isset($branch) ? $branch : \App\Models\Branch::find($notification->branch_id); @endphp
                                            <tr class="table-secondary">
                                                <td>{{$key+1}}</td>
                                                <td>{{$notification->created_at->format('d-m-Y h:i A')}}</td>
                                                <td>Branch Document</td>
                                                <td>{{isset($vendor->business_name) ? $vendor->business_name : ''}}</td>
                                                <td colspan="2"><b>{{$notification->title}}</b></td>
                                                <td>
                                                    <a href="{{route('branches.branch.document',[$vendor,$branch])}}"><i
                                                                class="ti ti-eye action-icons"></i></a>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        @if($notification->module == "drafter_inquiry")
                                            <tr class="table-primary">
                                                <td>{{$key+1}}</td>
                                                <td>{{$notification->created_at->format('d-m-Y h:i A')}}</td>
                                                <td>{{str_replace('_',' ' ,$notification->module)}}</td>
                                                @if($notification->from=="drafter")
                                                    <td>{{$vendor->name}}</td>
                                                @else
                                                    <td>{{isset($vendor->business_name)?$vendor->business_name:''}}</td>
                                                @endif
                                                <td colspan="2"><b>{{$notification->title}}</b></td>
                                                <td>
                                                    <form id="" method="get"
                                                          enctype="multipart/form-data"
                                                          action="{{ route('inquiry-master.compare-product') }}">
                                                        <input type="hidden" id="inquiry_id"
                                                               name="inquiry_id"
                                                               value="{{$inquiry->id}}">
                                                        <input type="hidden" id="vendor_id"
                                                               name="vendor_id"
                                                               value="{{@$vendor->user_id}}">
                                                        <input type="hidden" id="vendor_id"
                                                               name="products[]" value="all">
                                                        <input type="hidden" id="vendor_id"
                                                               name="vendor[]"
                                                               value="{{@$vendor->user_id}}">
                                                        <button type="submit"
                                                                class="action-button border-0 bg-transparent">
                                                            <i class="ti ti-eye action-icons"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {
            $('input[name="inquiry_date"]').daterangepicker({
                opens: 'left'
            }, function (start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });
    </script>
@endpush
