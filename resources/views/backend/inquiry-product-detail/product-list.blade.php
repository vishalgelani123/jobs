@extends('backend.layouts.app')
@section('title')
    Products
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
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h5 class="card-title mb-0">Products</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Item Description</th>
                                <th>Additional Info</th>
                                <th>Rate</th>
                                <th>Qty</th>
                                <th>Unit</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(Auth::user()->hasRole('drafter'))
                                @foreach($totalProducts as $product)
                                    @php
                                        $iProduct = \App\Models\InquiryProductDetail::find($product);
                                    @endphp
                                    <td></td>
                                    <td>{{$iProduct->item_description}}</td>
                                    <td>{{$iProduct->additional_info}}</td>
                                    <td>{{$iProduct->price}}</td>
                                    <td>{{$iProduct->qty}}</td>
                                    <td>{{$iProduct->unit}}</td>
                                @endforeach
                            @else
                                @if($totalProducts!=null)
                                    @foreach($products as $detail)
                                        <tr>
                                            <td>
                                                <input type="hidden" id="vendor_table_id">
                                                <input type="checkbox"
                                                       name="products[]"
                                                       id="product_{{$detail->id}}"
                                                       value="{{$detail->id}}"
                                                    {{in_array($detail->id,$totalProducts) ? 'checked' : ""}}>
                                            </td>
                                            <td>{{$detail->item_description}}</td>
                                            <td>{{$detail->additional_info}}</td>
                                            <td>{{$detail->price}}</td>
                                            <td>{{$detail->qty}}</td>
                                            <td>{{$detail->unit}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach($products as $detail)
                                        <tr>
                                            <td>
                                                <input type="hidden" id="vendor_table_id">
                                                <input type="checkbox" name="products[]" id="product_{{$detail->id}}"
                                                       value="{{$detail->id}}" checked></td>
                                            <td>{{$detail->item_description}}</td>
                                            <td>{{$detail->additional_info}}</td>
                                            <td>{{$detail->price}}</td>
                                            <td>{{$detail->qty}}</td>
                                            <td>{{$detail->unit}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                            </tbody>
                        </table>
                        @if(Auth::user()->hasRole('admin'))
                            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                                <div class="dt-buttons">
                                    <button type="button" class="btn btn-submit mt-4" onclick="submitProduct()">Allocate
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function submitProduct() {
            var vendor = '{{$vendor->user_id}}';
            var inquiry_id = '{{$inquiry->id}}';
            var checkedValues = [];
            $('input[name="products[]"]:checked').each(function () {
                checkedValues.push($(this).val());
            });

            Swal.fire({
                //title: "Are you sure?",
                text: "Are you sure Allocate it?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#398a28",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('inquiry-master.update-product') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'product_id': checkedValues,
                            'vendor_id': vendor,
                            'inquiry_id': inquiry_id
                        },
                        success: function (response) {
                            if (response.status == true) {

                                Swal.fire({
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: 'warning',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }

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
            });

            console.log(checkedValues)
        }
    </script>
@endpush
