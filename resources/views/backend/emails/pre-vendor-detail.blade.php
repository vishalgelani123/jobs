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
    <p>Dear {{ $preVendorDetail->name }}, </p>
    <div class="mt-4">
        <p>Welcome to Alembic Real Estate's Procurement & Contracting portal! We are thrilled to have you on board as a
            potential partner in our journey towards excellence.</p><br>

        <p>Your registration with us marks the beginning of what we hope will be a fruitful and mutually beneficial
            collaboration. As a registered vendor, you will gain access to our procurement opportunities and be notified
            of relevant updates and announcements.</p><br>

        <p>To complete your registration process, please click on the following link</p><br>

        <p class="text-center"><a href="{{ route('pre.vendor.invitation.detail',$preVendorDetail->invitation_code) }}"
                                  class="btn btn-custom-primary">Registration Link</a>&nbsp;&nbsp;Invitation
            Code : <b>{{ $preVendorDetail->invitation_code }}</b></p>

        <p>Once again, thank you for choosing to partner with Alembic Real Estate. We look forward to working with you
            and exploring opportunities for success together.</p><br>
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
