<html lang="en">
<head>
    <title>Welcome to Alembic Real Estate Procurement & Contracting!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .btn-custom-primary, .btn-custom-primary:hover, .btn-custom-primary:focus + .btn-custom-primary, .btn-custom-primary:focus {
            background-color: #015dab !important;
            color: #fff !important;
        }
        th, td{
            white-space: normal;
        }
    </style>
</head>
<body>
<div class="container mt-3 p-3">
    <div class="mt-4">
        <p>{{$message}}</p><br>

    </div>
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="head-label text-left">
                    <h5 class="card-title mb-0">Inquiry Details</h5>
                </div>
            </div>
            <div class="card-body">
                <div id="inquiry-vendor-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive"
                                   id="inquiry-vendor-table" aria-describedby="inquiry-vendor-table_info">
                                <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Project Name</th>
                                    <th>Inquiry date</th>
                                    <th>Inquiry End Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{$inquiry->id }}</td>
                                    <td>{{$inquiry->name }}</td>
                                    <td>{{$inquiry->inquiry_date}}
                                    </td>
                                    <td>{{$inquiry->end_date}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-1">
        <a href="{{ route('vendor-inquiry.inquiry-products',[$inquiry]) }}" class="btn btn-submit">Show Products</a>
    </div>
    <div class="mt-1">
        <b>
            Best regards,<br>
            Procurement & Contracting<br>
            {{config('app.name')}}
        </b>
    </div>
</div>
</body>
</html>
