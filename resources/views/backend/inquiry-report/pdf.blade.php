<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inquiry Report PDF</title>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-4.4.1.css')}}"/>
</head>
<body>
<h5 class="text-center">Inquiry Report</h5>
<table class="table table-bordered mt-4 mb-4">
    <thead>
    <tr class="table-active">
        <th>#</th>
        <th>Inquiry Date</th>
        <th>End Date</th>
        <th>Project Name</th>
        <th>Vendor Type</th>
        <th>Nos Of Inquiry</th>
        <th>Product Inquiry</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key=> $item)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$item['inquiry_date']}}</td>
            <td>{{$item['end_date']}}</td>
            <td>{{$item['project_name']}}</td>
            <td>{{$item['vendor_type']}}</td>
            <td>{{$item['nos_of_inquiry']}}</td>
            <td>{{$item['product_inquiry']}}</td>
            <td>{{$item['status']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
