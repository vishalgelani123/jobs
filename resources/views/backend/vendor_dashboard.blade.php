@extends('backend.layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="col-12">
        @if(Session::has('success'))
            <div class="alert alert-success">{{Session::get('success')}}</div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">{{Session::get('error')}}</div>
        @endif
    </div>
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('drafter'))
                                <h4 class="mb-0">{{$totalDrafterInquiry}}</h4>
                            @else
                                <h4 class="mb-0">{{$totalInquiries}}</h4>
                            @endif
                            <small>Total Inquiries</small>
                        </div>
                        <span class="badge bg-label-primary rounded-circle p-2">
                            <i class="ti ti-3d-cube-sphere ti-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('drafter'))
                                <h4 class="mb-0">{{$openDrafterInquiry}}</h4>
                            @else
                                <h4 class="mb-0">{{$openInquiries}}</h4>
                            @endif
                            <small>Open Inquiries</small>
                        </div>
                        <span class="badge bg-label-primary rounded-circle p-2">
                        <i class="ti ti-lock-open ti-md"></i>
                    </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('drafter'))
                                <h4 class="mb-0">{{$closeDrafterInquiry}}</h4>
                            @else
                                <h4 class="mb-0">{{$closedInquiries}}</h4>
                            @endif

                            <small>Closed Inquiries</small>
                        </div>
                        <span class="badge bg-label-primary rounded-circle p-2">
                        <i class="ti ti-lock-open-off ti-md"></i>
                    </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            <h4 class="mb-0">0</h4>
                            <small>Awarded Inquiries</small>
                        </div>
                        <span class="badge bg-label-primary rounded-circle p-2">
                        <i class="ti ti-award ti-md"></i>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xl-7 col-12 mb-4">
            <div class="card" style="height: 500px">
                <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Month Wise Inquiry Chart</h5>
                </div>
                <div class="card-body">
                    <canvas id="year-wise-service-chart" height="450">
                    </canvas>
                </div>
            </div>
        </div>

        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('drafter'))
            <div class="col-xl-5 col-12 mb-4">
                <div class="card" style="height: 496px">
                    <h5 class="card-header">Vendor Type Wise Chart</h5>
                    <div class="card-body">
                        <canvas id="doughnutCharts" class="mb-4"></canvas>
                    </div>
                </div>
            </div>
        @endif
        {{--<div class="col-xl-7 col-12 mb-4">
            <div class="card">
                <h5 class="card-header">Inquiry Chart</h5>
                <div class="card-body">
                    <canvas id="subscription-wise-service-chart" class="mb-4" style="height: 288px!important;"></canvas>
                </div>
            </div>
        </div>--}}
    </div>


    @if (Auth::user()->hasRole('vendor'))
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Open Inquiries</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        const h = document.getElementById("doughnutCharts");
        if (h) {
            h.width = 391; // Set your desired width
            h.height = 461; // Set your desired height
            new Chart(h, {
                type: "doughnut",
                data: {
                    labels: JSON.parse('{!! json_encode($serviceName) !!}'),
                    datasets: [{
                        data: JSON.parse('{!! json_encode($serviceCountData) !!}'),
                        backgroundColor: ['#1da1f2', '#FDAC34', '#3b5998'],
                        borderWidth: 0,
                        pointStyle: "rectRounded"
                    }]
                },
                options: {
                    responsive: !0,
                    animation: {duration: 500},
                    cutout: "68%",
                }
            })
        }
    </script>
    <script>
        var subscriptionWiseServiceChart = new Chart(document.getElementById('subscription-wise-service-chart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Open',
                    backgroundColor: '#005192',
                    borderColor: '#005192',
                    data: {!! json_encode($openInquiryCounts) !!}, // Adjusted for a single dataset
                },
                    {
                        label: 'Close',
                        backgroundColor: '#FF5733',
                        borderColor: '#FF5733',
                        data: {!! json_encode($closeInquiryCounts) !!} // Adjusted for a single dataset
                    }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 500
                },
                scales: {
                    xAxes: [{ // Corrected from x to xAxes
                        ticks: {
                            stepSize: 1
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                height: 400 // Adjust this value as needed
            }
        });
    </script>
    <script>
        var yearWiseServiceChart = new Chart(document.getElementById("year-wise-service-chart"), {
            type: "bar",
            data: {
                labels: JSON.parse('{!! json_encode($serviceYears) !!}'),
                datasets: [{
                    data: JSON.parse("{{ json_encode($serviceCount) }}"),
                    backgroundColor: "#28dac6",
                    borderColor: "transparent",
                    maxBarThickness: 15,
                    borderRadius: {
                        topRight: 15, topLeft: 15
                    }
                }]
            },
            options: {
                responsive: !0,
                maintainAspectRatio: !1,
                animation: {duration: 500},
                plugins: {
                    tooltip: {
                        borderWidth: 1,
                    }, legend: {
                        display: !1
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                }
            }
        });
    </script>
@endpush
