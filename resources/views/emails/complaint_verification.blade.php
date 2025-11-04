<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify your complaint email</title>
</head>
<body>
    <p>Hello {{ $complaint->first_name ?? '' }},</p>

    <p>Thank you for submitting your complaint (reference: <strong>{{ $complaint->reference }}</strong>).</p>

    <p>To verify your email, please use the code below or click the verification link.</p>

    <p>
        <strong>Reference code:</strong> {{ $complaint->reference }} <br>
        <strong>Verification link:</strong> <a href="{{ $verificationUrl }}">Verify my email</a>
    </p>

    <p>If you did not submit this complaint, you can ignore this message.</p>

    <p>Thanks,<br>The Barangay 605 Team</p>
</body>
</html>
