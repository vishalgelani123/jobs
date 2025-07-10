<html lang="en">
<head>
    <title>Test Email</title>
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
