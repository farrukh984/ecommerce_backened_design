<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin OTP</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Your one-time admin login code is:</p>
    <h2>{{ $otp }}</h2>
    <p>This code expires in 10 minutes. If you did not request this, please contact support.</p>
    <p>— ShopBrand</p>
</body>
</html>
