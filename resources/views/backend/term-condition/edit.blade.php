@extends('backend.layouts.app')
@section('title')
    Edit Term Condition
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 mb-2">
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
                        <h5 class="card-title mb-0">Edit Term Condition</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div><b>Category :</b> {{$termConditionCategory->name}}</div>
                    <form
                        action="{{route('term-conditions.update',[$termConditionCategory,$termCondition])}}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="title" class="form-label">Title<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="title"
                                       placeholder="Enter Title" value="{{$termCondition->title}}">
                                @error('title')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="description" class="form-label">Description<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          name="description" id="description"
                                          placeholder="Enter Description">{!! $termCondition->description !!}</textarea>
                                @error('description')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-3 text-right">
                                <a href="{{route('term-condition-categories.details',$termConditionCategory)}}"
                                   class="btn btn-danger">Cancel
                                </a>
                                <button type="submit" class="btn btn-submit text-capitalize">Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        ClassicEditor
            .create(document.getElementById("description"))
            .then(editor => {
            })
            .catch(error => {
                console.error('Error during initialization of the editor', error);
            });
    </script>
@endpush
