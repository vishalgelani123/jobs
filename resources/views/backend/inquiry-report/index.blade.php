@extends('backend.layouts.app')
@section('title')
    Inquiry Report
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
                        <h5 class="card-title mb-0">Inquiry Report</h5>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons">
                            <a href="{{route('inquiry-report.export')}}?type=pdf&{{http_build_query(request()->query())}}"
                               class="dt-button create-new btn btn-submit text-white">
                                        <span>
                                           <i class="ti ti-pdf me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">PDF</span>
                                        </span>
                            </a>&nbsp;&nbsp;
                            <a href="{{route('inquiry-report.export')}}?type=excel&{{http_build_query(request()->query())}}"
                               class="dt-button create-new btn btn-submit text-white">
                                        <span>
                                           <i class="ti ti-file-spreadsheet me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Excel</span>
                                        </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="get" action="{{route('inquiry-report.index')}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="inquiry_date">Inquiry Date</label>
                                        <input type="text" class="form-control" name="inquiry_date" id="inquiry_date"
                                               placeholder="Select Date Range" value="{{ request()->inquiry_date ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="end_date">End Date</label>
                                        <input type="text" class="form-control" name="end_date" id="end_date"
                                               placeholder="Select Date Range" value="{{ request()->end_date ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="vendor_type">Vendor Type</label>
                                        <select id="vendor_type" name="vendor_type" class="form-control select2">
                                            <option value="">All</option>
                                            @foreach($vendorTypes as $vendorType)
                                                <option value="{{$vendorType->id}}"
                                                        @if(request()->vendor_type == $vendorType->id) selected @endif >{{$vendorType->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status">Status</label>
                                        <select class="form-control select2" name="status" id="status">
                                            <option value="">All</option>
                                            @php $statusArr = ['open','close']; @endphp
                                            @foreach($statusArr as $status)
                                                <option value="{{$status}}"
                                                        @if(request()->status == $status) selected @endif>{{ucfirst(str_replace("_"," ",$status))}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <button type="submit" class="btn btn-submit"><i
                                                class="ti ti-filter"></i>&nbsp;Apply
                                        </button>
                                        <a href="{{route('inquiry-report.index')}}"
                                           class="btn btn-danger"><i
                                                class="ti ti-reload"></i>&nbsp;Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>

        $(function () {
            $('input[name="inquiry_date"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="inquiry_date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('input[name="inquiry_date"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });

        $(function () {
            $('input[name="end_date"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="end_date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('input[name="end_date"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endpush


