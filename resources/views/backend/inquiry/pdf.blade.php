<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inquiry Report PDF</title>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-4.4.1.css')}}"/>
    <style>
        body {
            font-size: 12px;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        .table-active {
            background-color: #f8f9fa;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6 !important;
        }
    </style>
</head>
<body>
<h5 class="text-center">Inquiry Report</h5>
<table class="table table-bordered mt-4 mb-4">
    <thead>
    <tr class="table-active">
        <th>#</th>
        <th>Inquiry Date</th>
        <th>End Date</th>
        <th>Subject</th>
        <th>Project Name</th>
        <th>Vendor Type</th>
        <th>Status</th>
        <th>Admin Status</th>
        <th>Created By</th>
        <th>Approved By</th>
        <th>Approver Status</th>
        <th>Approver Date</th>
        <th>Allocation (With Grand Total)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key=> $item)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$item['inquiry_date']}}</td>
            <td>{{$item['end_date']}}</td>
            <td>{{$item['subject']}}</td>
            <td>{{$item['project_name']}}</td>
            <td>{{$item['vendor_type']}}</td>
            <td>{{$item['status']}}</td>
            <td>{{$item['admin_status']}}</td>
            <td>{{$item['created_by']}}</td>
            <td>{{$item['approved_by']}}</td>
            <td>{{$item['approver_status']}}</td>
            <td>{{$item['approver_date']}}</td>
            <td>{{$item['allocation']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
