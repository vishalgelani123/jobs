@extends('backend.layouts.app')
@section('title')
    General Term Conditions
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

        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <div class="row">
                        <div class="col-6 head-label text-left">
                            <h5 class="card-title mb-0">General Term Conditions</h5>
                        </div>
                        <div class="col-6 text-right"><a href="{{route('general-term-condition-categories.index')}}" class="btn btn-danger">Back</a></div>
                        <div class="col-12 mt-3">Category : {{$generalTermConditionCategory->name}}</div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
