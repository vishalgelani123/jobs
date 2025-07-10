<html lang="en">
<head>
    <title>Password Update to Alembic Real Estate Procurement & Contracting!</title>
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
    <p class="mt-4">Dear {{ $user->name }}, </p>
    <div class="mt-4">
        <p>{{$message}}</p>
        <p class="mt-1">Updated Password : {{$plainPassword}}</p><br>
    </div>
    <div class="mt-3">
        <b>
            Best regards,<br>
            Procurement & Contracting<br>
            {{config('app.name')}}
        </b>
    </div>
</div>
</body>
</html>
