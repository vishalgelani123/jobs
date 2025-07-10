@extends('backend.layouts.app')
@section('title')
    SMTP Settings
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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">SMTP Setting</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('smtp-settings.store',$smtpSetting)}}" id="smtpSettingForm"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="mail_host" class="form-label">Mail Host
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control" name="mail_host" id="mail_host"
                                           placeholder="Enter Mail Host" value="{{$smtpSetting->mail_host ?? ''}}">
                                    @error('mail_host')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="mail_port" class="form-label">Mail Port
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control" name="mail_port" id="mail_port"
                                           placeholder="Enter Port" value="{{$smtpSetting->mail_port ?? ''}}">
                                    @error('mail_port')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="mail_username" class="form-label">Mail Username
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control" name="mail_username" id="mail_username"
                                           placeholder="Enter Mail Username"
                                           value="{{$smtpSetting->mail_username ?? ''}}">
                                    @error('mail_username')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="mail_password" class="form-label">Mail Password
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="password" class="form-control" name="mail_password" id="mail_password"
                                           placeholder="Enter Mail Password"
                                           value="{{$smtpSetting->mail_password ?? ''}}">
                                    @error('mail_password')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="mail_encryption" class="form-label">Mail Encryption
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <select class="form-control" name="mail_encryption" id="mail_encryption">
                                        <option value="" disabled selected>Select mail encryption</option>
                                        <option
                                            value="ssl" {{isset($smtpSetting->mail_encryption) && $smtpSetting->mail_encryption == 'ssl' ? 'selected' : ''}}>
                                            ssl
                                        </option>
                                        <option
                                            value="tls" {{isset($smtpSetting->mail_encryption) && $smtpSetting->mail_encryption == 'tls' ? 'selected' : ''}}>
                                            tls
                                        </option>
                                        <option
                                            value="starttls" {{isset($smtpSetting->mail_encryption) && $smtpSetting->mail_encryption == 'starttls' ? 'selected' : ''}}>
                                            starttls
                                        </option>
                                    </select>
                                    @error('mail_encryption')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="mail_from_email" class="form-label">Mail From Email
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control" name="mail_from_email" id="mail_from_email"
                                           placeholder="Enter Mail From Email"
                                           value="{{$smtpSetting->mail_from_address ?? ''}}">
                                    @error('mail_from_email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="mail_from_name" class="form-label">Mail From Name
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control" name="mail_from_name" id="mail_from_name"
                                           placeholder="Enter Mail From Name"
                                           value="{{$smtpSetting->mail_from_name ?? ''}}">
                                    @error('mail_from_name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right mt-4">
                            <a href="{{route('smtp-settings.index')}}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Test SMTP Configuration</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('smtp-settings.test.mail')}}" id="testEmailForm"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-7 mb-2">
                                    <input type="email" class="form-control" name="email" id="email"
                                           placeholder="Enter Email" value="">
                                    @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-md-5 mb-2">
                                    <button type="submit" class="btn btn-submit">Send Test Email</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
