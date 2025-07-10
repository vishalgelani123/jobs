@extends('backend.layouts.app')
@section('title')
    Vendor Inquiry
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
        <div class="col-md-12 col-xl-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-0">Inquiry Details</h5>
                    <hr>
                    <h6>Remarks</h6>
                    <p class="small">{{$inquiry->remarks}}</p>
                    <hr>
                    <div class="row mb-3 g-3">
                        <div class="col-3">
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-2">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                                class="ti ti-user ti-md"></i></span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">{{$inquiry->name}}</h6>
                                    <small>Name</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-2">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                                class="ti ti-calendar ti-md"></i></span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">{{$inquiry->inquiry_date}}</h6>
                                    <small>Inquiry Date</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-2">
                                    <span class="avatar-initial rounded bg-label-danger"><i
                                                class="ti ti-calendar ti-md"></i></span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">{{$inquiry->end_date}}</h6>
                                    <small>End Date</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex">
                                <div class="avatar flex-shrink-0 me-2">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                                class="ti ti-file-typography ti-md"></i></span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">{{$inquiry->vendorType->name}}</h6>
                                    <small>Vendor Type</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Vendor Details</h5>
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
                                        <th>Sr.No</th>
                                        <th>Vendor Name</th>
                                        <th>City</th>
                                        <th>Action</th>
                                        {{-- <th>Product(Category)</th>
                                         <th>Price</th>
                                         <th>Qty</th>
                                         <th colspan="2">Vendor Product Price</th>--}}

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vendors as $key => $details)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td><b>{{$details->business_name}}</b></td>
                                            @php
                                                $vendorCity = \App\Models\InquiryVendorDetail::where('inquiry_id',$inquiry->id)->where('vendor_id',$details->id)->first();
                                                $city = \App\Models\City::where('id',$vendorCity->city_id)->first();
                                            @endphp
                                            <td>{{$city->name}}</td>
                                            <td>
                                                <a href="{{route('inquiry.vendor-product-details',[$inquiry,$details])}}"><i
                                                            class="ti ti-eye action-icons"></i></a></td>
                                            {{--<td><b>{{$details->product->name}}</b> - <span class="avatar-initial rounded bg-label-info"><i class="ti ti-category ti-md"></i></span>{{$details->product->category}}
    --}}{{--                                            <hr style="color:#007bff!important;"><small>{{$details->description}}</small>--}}{{--
                                            </td>
                                            <td>{{$details->product->price}}</td>
                                            <td>{{$details->product->qty}} {{ $details->product->unit }}</td>
                                            <td colspan="2"><b>{{$details->rate}}</b>
                                                <hr style="color:#007bff!important;"><small>{{$details->remarks}}</small>
                                            </td>--}}


                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#submit-all').on('click', function () {

                var data = [];

                $('#inquiry-vendor-table tbody tr').each(function () {
                    var row = $(this);
                    var id = row.find('td:eq(0)').text().trim();  // Assuming the ID is in the first column
                    var inquiry_id = {{ $inquiry->id }} // Extract inquiry_id from onclick attribute
                    var vendor_price = $("#vendor_price_" + id).val();
                    var vendor_description = $("#vendor_description_" + id).val();
                    data.push({
                        product_id: id,
                        inquiry_id: inquiry_id,
                        vendor_price: vendor_price,
                        vendor_description: vendor_description
                    });
                });
                $.ajax({
                    type: 'post',
                    url: '{{ route('vendor-inquiry.store') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'data': data,
                    },
                    success: function (response) {
                        if (response.status == true) {
                            Swal.fire({
                                text: response.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                text: response.message,
                                icon: "warning",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                        $('#inquiry-vendor-table').DataTable().draw();
                        location.reload();
                    },
                    error: function (error) {
                        let errors = error.responseJSON.errors;
                        $('#userForm .form-control').removeClass('is-invalid');
                        $('.error-message').remove();
                        $.each(errors, function (field, messages) {
                            let inputField = $('#userForm').find('[name="' + field + '"]');
                            inputField.addClass('is-invalid');
                            inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                        });
                    }
                });
            });
        });
    </script>
    <script>
        function showFormModal(id, inquiryId) {

            $("#ipd_id").val(id);
            $("#inquiry_id").val(inquiryId);
            $("#userFormModal").modal('show');
            $('#userForm').attr('action', '{{route('vendor-inquiry.store')}}');

        }

        function submitForm(id, inquiryId) {
            var vendorPrice = $("#vendor_price_" + id).val();
            var vendorRemark = $("#vendor_description_" + id).val();
            console.log(vendorPrice, vendorRemark);

            $.ajax({
                type: 'post',
                url: '{{ route('vendor-inquiry.store') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'vendor_price': vendorPrice,
                    'vendor_remarks': vendorRemark,
                    'inquiry_id': inquiryId,
                    'product_id': id,
                },
                success: function (response) {
                    if (response.status == true) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "warning",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                    $('#inquiry-vendor-table').DataTable().draw();
                    location.reload();
                },
                error: function (error) {
                    let errors = error.responseJSON.errors;
                    $('#userForm .form-control').removeClass('is-invalid');
                    $('.error-message').remove();
                    $.each(errors, function (field, messages) {
                        let inputField = $('#userForm').find('[name="' + field + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<div class="text-danger error-message">' + messages.join('<br>') + '</div>');
                    });
                }
            });
        }

    </script>
@endpush
