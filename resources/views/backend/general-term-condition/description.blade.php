@extends('backend.layouts.app')
@section('title')
    General Term Condition Description
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
            <div class="card overflow-scroll">
                <div class="card-header justify-content-between">
                    <div class="row">
                        <div class="col-6 head-label text-left">
                            <h5 class="card-title mb-0">General Term Condition Description</h5>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{route('general-term-condition-categories.details',$generalTermConditionCategory)}}"
                               class="btn btn-danger">Back
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $generalTermCondition->description !!}
                </div>
            </div>
        </div>
    </div>
@endsection
