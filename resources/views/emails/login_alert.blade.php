<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; padding: 40px 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #0ea5e9, #4f46e5); padding: 40px 20px; text-align: center; color: #ffffff; }
        .content { padding: 40px; color: #334155; line-height: 1.6; }
        .details { background: #f1f5f9; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .detail-row:last-child { margin-bottom: 0; }
        .label { font-weight: 600; color: #64748b; }
        .value { font-weight: 700; color: #0f172a; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; }
        .button { display: inline-block; background: #4f46e5; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1 style="margin:0; font-size: 24px;">Security Alert</h1>
            </div>
            <div class="content">
                <h2 style="margin-top:0;">Hello, {{ explode(' ', $user->name)[0] }}!</h2>
                <p>This is a security notification to let you know that your account was recently accessed from a new device or location.</p>
                
                <div class="details">
                    <div class="detail-row">
                        <span class="label">Date & Time:</span>
                        <span class="value">{{ $loginTime }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">IP Address:</span>
                        <span class="value">{{ $ipAddress }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Account Role:</span>
                        <span class="value" style="text-transform: capitalize;">{{ $user->role }}</span>
                    </div>
                </div>

                <p>If this was you, you can safely ignore this email. However, if you do not recognize this activity, we strongly recommend that you change your password immediately to secure your account.</p>
                
                <div style="text-align: center;">
                    <a href="{{ route('password.request') }}" class="button">Reset Password</a>
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} Brand Ecommerce. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
