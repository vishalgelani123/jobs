@extends('backend.layouts.app')
@section('title')
    Notifications
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
        <div class="col-md mb-6 mb-md-0">
            <div class="card">
                <h5 class="card-header">Notification Filter</h5>
                <div class="card-body">
                    <form method="get" action="{{ route('notification.index') }}">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="inquiry_date">Select Date</label>
                                        <input type="text" class="form-control" name="inquiry_date" id="inquiry_date"
                                               value="{{ request()->inquiry_date ?? '' }}">
                                    </div>
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="status">Module</label>
                                        <select class="form-control" name="module" id="status">
                                            <option value="">All</option>
                                            <option
                                                value="Inquiry" {{ request()->module == "Inquiry" ? 'selected' : "" }}>
                                                Inquiry
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-submit me-2"><i
                                                class="ti ti-filter"></i>&nbsp;Apply
                                        </button>
                                        <a href="{{ route('notification.index') }}"
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

        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        @if(request()->inquiry_date != null || request()->end_date != null || request()->module != null)
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
                                    <th colspan="2">Title</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($notifications as $key => $notification)
                                    @php
                                        $inquiry = \App\Models\ResInquiryMaster::find($notification->inquiry_id);
                                        $branchId = \App\Models\Branch::find($notification->branch_id);
                                    @endphp
                                    @if($notification->module == "Inquiry")
                                        <tr class="table-primary">
                                            <td>{{$key + 1}}</td>
                                            <td>{{$notification->created_at->format('Y-m-d h:i A')}}</td>
                                            <td>{{$notification->module}}</td>
                                            <td colspan="2"><b>{{$notification->title}}</b></td>
                                            <td><a href="{{route('vendor-inquiry.inquiry-products',[$inquiry])}}"><i
                                                        class="ti ti-eye action-icons"></i></a></td>
                                        </tr>
                                    @elseif($notification->module == "vendor_document")
                                        <tr class="table-primary">
                                            <td>{{$key + 1}}</td>
                                            <td>{{$notification->created_at->format('Y-m-d h:i A')}}</td>
                                            <td>{{str_replace('_',' ',$notification->module)}}</td>
                                            <td colspan="2"><b>{{ucwords($notification->title)}}</b></td>
                                            <td><a href="{{route('vendor.vendor.document')}}"><i
                                                        class="ti ti-eye action-icons"></i></a></td>
                                        </tr>
                                    @elseif($notification->module == "branch_document")
                                        <tr class="table-primary">
                                            <td>{{$key + 1}}</td>
                                            <td>{{$notification->created_at->format('Y-m-d h:i A')}}</td>
                                            <td>{{str_replace('_',' ',$notification->module)}}</td>
                                            <td colspan="2"><b>{{ucwords($notification->title)}}</b></td>
                                            <td>
                                                <a href="{{route('vendor-branches.branch.document',[isset($branchId)])}}"><i
                                                        class="ti ti-eye action-icons"></i></a></td>
                                        </tr>
                                    @else
                                        <tr class="table-secondary">
                                            <td>{{$key + 1}}</td>
                                            <td>{{$notification->created_at->format('Y-m-d h:i A')}}</td>
                                            <td>{{$notification->module}}</td>
                                            <td colspan="2"><b>{{$notification->title}}</b></td>
                                            <td><a href="{{route('vendor-inquiry.inquiry-products',[$inquiry])}}"><i
                                                        class="ti ti-eye action-icons"></i></a></td>
                                        </tr>
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


