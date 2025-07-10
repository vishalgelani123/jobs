@extends('backend.layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
    {{--<div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            <h4 class="mb-0">{{$totalInward}}</h4>
                            <small>Total Inward</small>
                        </div>
                        <span class="badge bg-label-primary rounded-circle p-2">
                            <i class="ti ti-table-import ti-md"></i>
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
                            <h4 class="mb-0">{{$totalStockTransfer}}</h4>
                            <small>Total Stock Transfer</small>
                        </div>
                        <span class="badge bg-label-success rounded-circle p-2">
                            <i class="ti ti-arrows-transfer-up ti-md"></i>
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
                            <h4 class="mb-0">{{$totalIConsumable}}</h4>
                            <small>Total Consumables Outwards</small>
                        </div>
                        <span class="badge bg-label-info rounded-circle p-2">
                            <i class="ti ti-aperture-off ti-md"></i>
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
                            <h4 class="mb-0">{{$totalIWastage}}</h4>
                            <small>Total Wastage</small>
                        </div>
                        <span class="badge bg-label-danger rounded-circle p-2">
                            <i class="ti ti-trash ti-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}
@endsection
