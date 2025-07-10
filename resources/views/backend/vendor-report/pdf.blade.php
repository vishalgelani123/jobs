<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vendor Report PDF</title>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-4.4.1.css')}}"/>
</head>
<body>
<h5 class="text-center">Vendor Report</h5>
<table class="table table-bordered mt-4 mb-4">
    <thead>
    <tr class="table-active">
        <th>#</th>
        <th>Vendor Name</th>
        <th>Allocation Inquiries</th>
        <th>Open Status</th>
        <th>Close Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key=> $item)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$item['vendor_name']}}</td>
            <td>{{$item['allocation_inquiry']}}</td>
            <td>{{$item['open_status']}}</td>
            <td>{{$item['close_status']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
