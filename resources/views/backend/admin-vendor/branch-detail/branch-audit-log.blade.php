@extends('backend.layouts.app')
@section('title')
    Audit Log
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
        <div class="row text-nowrap">
            <div class="col-md-4">
                @include('backend.admin-vendor.branch-detail.branch-view.partial.sidebar')
            </div>
            <div class="col-md-8">
                @include('backend.admin-vendor.branch-detail.branch-view.partial.header')
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Audit Log</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="get" action="{{route('branches.audit.log',[$vendor,$branch])}}">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="date">Date</label>
                                            <input type="date" class="form-control" name="date" id="date"
                                                   value="{{ request()->date ?? '' }}">
                                        </div>
                                        <div class="col-md-4 mt-4">
                                            <button type="submit" class="btn btn-success"><i
                                                    class="ti ti-filter"></i>&nbsp;Apply
                                            </button>
                                            <a href="{{route('branches.audit.log',[$vendor,$branch])}}"
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
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
