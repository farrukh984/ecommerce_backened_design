<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
        .wrapper { padding: 40px 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1); border-top: 6px solid #8b5cf6; }
        .header { background: #1e293b; padding: 40px; text-align: center; color: white; }
        .content { padding: 40px; color: #334155; }
        .msg-bubble { background: #f3f4f6; border-radius: 12px; padding: 20px; margin: 20px 0; border: 1px solid #e5e7eb; position: relative; }
        .btn { display: inline-block; padding: 14px 28px; background: #8b5cf6; color: white !important; text-decoration: none; border-radius: 10px; font-weight: 700; margin-top: 20px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div style="font-size: 32px; margin-bottom: 10px;">✉️</div>
                <h1 style="margin: 0; font-size: 24px;">New Inquiry!</h1>
            </div>
            <div class="content">
                <p>You have received a new message from <strong>{{ $message->sender->name ?? 'Customer' }}</strong>:</p>
                
                <div class="msg-bubble">
                    <div style="font-size: 12px; font-weight: 700; color: #6b7280; margin-bottom: 10px; text-transform: uppercase;">Message Content:</div>
                    <div style="line-height: 1.6;">{{ $message->message }}</div>
                </div>

                <div style="text-align: center;">
                    <a href="{{ url('/admin/messages') }}" class="btn">Reply to Customer</a>
                </div>
            </div>
            <div class="footer">
                Fast responses lead to higher customer satisfaction.
            </div>
        </div>
    </div>
</body>
</html>
