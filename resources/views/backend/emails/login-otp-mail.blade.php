<html lang="en">
<head>
    <title>OTP for Login</title>
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
    <p>Dear {{ $user->name}}</p>
    <p class="mt-4"></p>

    <p class="mt-3">You have received a OTP : {{$otp}} for login from {{config('app.name')}}. Kindly check it on
        portal.
    </p>
    <p class="mt-2">Thank you for keep us updated!</p>
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
