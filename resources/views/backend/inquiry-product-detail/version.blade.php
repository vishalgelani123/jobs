@extends('backend.layouts.app')
@section('title')
    Versions
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
        @foreach($versionArr as $key => $version)
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Version {{$key}}'s Details</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="inquiry-vendor-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-md-12">

                                </div>
                                <div class="col-md-12">
                                    <table class="table"
                                           id="inquiry-vendor-table" aria-describedby="inquiry-vendor-table_info"
                                           style="width: 996px;">
                                        <thead>
                                        <tr>
                                            <th>Sr.No
                                            </th>
                                            <th>Product(Category)
                                            </th>
                                            <th>
                                                Price
                                            </th>
                                            <th>Qty
                                            </th>
                                            <th colspan="2">
                                                Version Price
                                            </th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($version as   $vr)
                                            @foreach($vr as $key => $v)

                                                <tr>
                                                    <td class="sorting_1 dtr-control">{{$key+1}}</td>

                                                    <td><b>{{$v->product->name}}</b> - {{$v->product->category}}</td>
                                                    <td>{{$v->product->price}}</td>
                                                    <td>{{$v->product->qty}} {{$v->product->unit}}</td>
                                                    <td colspan="2"><b>{{$v->rate}}</b>
                                                        <hr>
                                                        <small>{{$v->remarks}}</small>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection

