@extends('backend.layouts.app')
@section('title')
    Branches
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
                {{--@include('backend.admin-vendor.branch-detail.partial.sidebar')--}}
                @include('backend.admin-vendor.partial.sidebar')
            </div>

            <div class="col-md-8">
                {{--@include('backend.admin-vendor.branch-detail.partial.header')--}}
                @include('backend.admin-vendor.partial.header')
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="head-label text-left">
                            <h5 class="card-title mb-0">Branches</h5>
                        </div>
                        <div class="dt-action-buttons text-end">
                            <div class="dt-buttons">
                                <a href="{{route('branches.create',$vendor)}}"
                                   class="dt-button create-new btn btn-success">
                               <span>
                                  <i class="ti ti-plus me-sm-1"></i>
                                  <span class="d-none d-sm-inline-block">Add New</span>
                               </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}

    <script>
        function isPrimaryFormModal(id) {
            Swal.fire({
                //title: "Are you sure?",
                text: "Are you sure want to make it primary?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: "{{route('branches.is.primary',$vendor)}}",
                        data: {
                            id: id,
                            _token: "{{csrf_token()}}",
                        }, success: function (response) {
                            if (response.status === true) {
                                Swal.fire({
                                    title: "",
                                    text: response.message,
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: "warning",
                                });
                            }
                            $('#branches-table').DataTable().draw();
                        },
                    });
                }
            });
        }

    </script>
@endpush
