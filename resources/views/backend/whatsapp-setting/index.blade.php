@extends('backend.layouts.app')
@section('title')
    WhatsApp Settings
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-12 mb-2">
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
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">WhatsApp Setting</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('whatsapp-settings.store',$whatsAppSetting)}}" id="smtpSettingForm"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label for="whatsapp_from_name" class="form-label">WhatsApp From Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="whatsapp_from_name"
                                           id="whatsapp_from_name"
                                           placeholder="Enter WhatsApp From Name"
                                           value="{{$whatsAppSetting->whatsapp_from_name ?? ''}}">
                                    @error('whatsapp_from_name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-2">
                                    <label for="whatsapp_number" class="form-label">Whatsapp Number<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="whatsapp_number" id="whatsapp_number"
                                           placeholder="Enter Whatsapp Number"
                                           value="{{$whatsAppSetting->whatsapp_number ?? ''}}">
                                    @error('whatsapp_number')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-right mt-4">
                            <a href="{{route('whatsapp-settings.index')}}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
