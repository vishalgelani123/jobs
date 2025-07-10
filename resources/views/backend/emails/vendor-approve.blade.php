<html lang="en">
<head>
    <title>{{config('app.name')}}</title>
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
    <p>Dear {{$vendor->business_name}},</p>
    <div class="mt-4">
        <p>Your registration has been approved. Please check below link to login.</p>
        <p class="text-left"><a href="{{ route('login')}}" class="btn btn-custom-primary">Login</a></p>
        <br>
    </div>
    <div class="mt-2">
        <b>
            Best regards,<br>
            Procurement & Contracting<br>
            {{config('app.name')}}
        </b>
    </div>
</div>
</body>
</html>
