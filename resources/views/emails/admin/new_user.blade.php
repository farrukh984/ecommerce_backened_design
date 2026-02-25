<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
        .wrapper { padding: 40px 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .header { background: #1e293b; padding: 40px; text-align: center; color: white; }
        .content { padding: 40px; color: #334155; }
        .stat-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .label { font-size: 13px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px; }
        .value { font-size: 16px; font-weight: 600; color: #1e293b; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
        .btn { display: inline-block; padding: 14px 28px; background: #3b82f6; color: white !important; text-decoration: none; border-radius: 10px; font-weight: 700; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div style="font-size: 32px; margin-bottom: 10px;">👋</div>
                <h1 style="margin: 0; font-size: 24px;">New Registration!</h1>
            </div>
            <div class="content">
                <p>Hello Admin,</p>
                <p>A new user has just joined your platform. Here are the registration details:</p>
                
                <div class="stat-box">
                    <div class="label">Full Name</div>
                    <div class="value">{{ $user->name }}</div>
                </div>
                
                <div class="stat-box">
                    <div class="label">Email Address</div>
                    <div class="value">{{ $user->email }}</div>
                </div>

                <div class="stat-box">
                    <div class="label">Joined Date</div>
                    <div class="value">{{ $user->created_at->format('M d, Y | h:i A') }}</div>
                </div>

                <div style="text-align: center;">
                    <a href="{{ url('/admin/users') }}" class="btn">View Customer Base</a>
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} {{ config('app.name') }} Admin Panel
            </div>
        </div>
    </div>
</body>
</html>
