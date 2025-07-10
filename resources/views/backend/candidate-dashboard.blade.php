@extends('backend.layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-6 col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            <h4 class="mb-0">{{$totalAppliedJobs}}</h4>
                            <small>Total Applied Jobs</small>
                        </div>
                        <span class="badge bg-label-primary rounded-circle p-2">
                            <i class="ti ti-list ti-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>






    </div>
    {{--<div class="row mt-4">
        <div class="col-xl-4 col-12 mb-4">
            <div class="card">
                <h5 class="card-header">Vendor Type Wise Chart</h5>
                <div class="card-body">
                    <canvas id="doughnutCharts" class="mb-4"  ></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-12 mb-4">
            <div class="card">
                <h5 class="card-header">Vendor Type Wise Inquiry Chart</h5>
                <div class="card-body">
                    <canvas id="subscription-wise-service-chart" class="mb-4" style="height: 288px!important;"></canvas>
                </div>
            </div>
        </div>
    </div>--}}
@endsection

@push('scripts')


    @endpush
