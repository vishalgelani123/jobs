<html lang="en">
<head>
    <title>Welcome to Alembic Real Estate Procurement & Contracting!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .btn-custom-primary, .btn-custom-primary:hover, .btn-custom-primary:focus + .btn-custom-primary, .btn-custom-primary:focus {
            background-color: #015dab !important;
            color: #fff !important;
        }

        th, td {
            white-space: normal;
        }
    </style>
</head>
<body>
<div class="container mt-3 p-3">
    <p>Dear {{ $inquiryApproval->approvalUser->name }}, </p>
    <div class="mt-4">

        <p>You are request to approve the inquiry. Here below you can find out the inquiry details</p><br>

        <div class="col-12 mt-1">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="head-label text-left">
                        <h6 class="card-title mb-0">Inquiry Details</h6>
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
                                        <td>{{$inquiry->inquiry_date}}</td>
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
        <p class="text-center mt-3">
            <a href="{{ route('inquiry-master.detail',$inquiry) }}" class="btn btn-custom-primary">Inquiry Approve</a>
        </p>
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
