@extends('backend.layouts.app')
@section('title')
    Replace File
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
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>Replace File</div>
                </div>
                <div class="card-body">
                    <form action="{{route('replace-files.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="url" class="form-label">Url</label>
                                <input value="{{old('destination_path')}}" type="text" class="form-control"
                                       name="destination_path" id="destination_path"
                                       placeholder="Enter Destination Path">
                                @error('destination_path')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <label for="file" class="form-label">File</label>
                                <input type="file" class="form-control" name="file" id="file">
                                @error('file')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="destination_type"
                                           id="app" value="app">
                                    <label class="form-check-label" for="app">
                                        App
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="destination_type"
                                           id="resources" value="resources">
                                    <label class="form-check-label" for="resources">
                                        Resources
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="destination_type"
                                           id="base" value="base">
                                    <label class="form-check-label" for="base">
                                        Base Path
                                    </label>
                                </div>
                                @error('destination_type')
                                <div class="text-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
