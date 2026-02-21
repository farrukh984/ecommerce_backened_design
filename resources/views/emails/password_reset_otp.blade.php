<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password</title>
    <style>
        /* paste the CSS here or link externally (not recommended for email) */

        /* password-reset-otp.css – Modern, professional styles for password reset OTP email */

/* Base reset for email clients */
body {
    margin: 0;
    padding: 0;
    background-color: #f1f5f9;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    line-height: 1.5;
    color: #1e293b;
}

/* Outer wrapper for full background */
.email-wrapper {
    width: 100%;
    background-color: #f1f5f9;
    padding: 40px 0;
}

/* Main card container */
.container {
    max-width: 560px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 32px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    border: 1px solid rgba(226, 232, 240, 0.6);
}

/* Inner padding */
.inner-content {
    padding: 40px 36px;
}

/* Header with brand */
.header {
    text-align: center;
    margin-bottom: 32px;
}

.header h1 {
    font-size: 32px;
    font-weight: 700;
    letter-spacing: -0.02em;
    background: linear-gradient(145deg, #2563eb, #1e40af);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    color: #2563eb; /* fallback */
    margin: 0;
}

/* Main content area */
.content {
    background: #f8fafc;
    padding: 36px 28px;
    border-radius: 28px;
    text-align: center;
    border: 1px solid #e2e8f0;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
}

.content h2 {
    font-size: 24px;
    font-weight: 600;
    color: #0f172a;
    margin: 0 0 12px 0;
}

.content p {
    font-size: 16px;
    color: #475569;
    margin: 12px 0;
}

/* OTP code display */
.otp-code {
    font-size: 44px;
    font-weight: 700;
    letter-spacing: 8px;
    color: #2563eb;
    background: linear-gradient(145deg, #ffffff, #f1f5f9);
    padding: 18px 24px;
    border-radius: 20px;
    display: inline-block;
    margin: 24px 0 16px;
    border: 2px solid #2563eb;
    box-shadow: 0 8px 20px -8px rgba(37, 99, 235, 0.3);
    font-family: 'Courier New', monospace;
}

/* Expiry note */
.expiry-note {
    font-size: 14px;
    color: #64748b;
    background: #ffffff;
    padding: 14px 18px;
    border-radius: 40px;
    display: inline-block;
    margin: 16px 0 8px;
    border: 1px solid #e2e8f0;
}

.expiry-note i {
    color: #2563eb;
    margin-right: 6px;
}

/* Divider */
.divider {
    height: 1px;
    background: linear-gradient(to right, transparent, #e2e8f0, transparent);
    margin: 30px 0 20px;
}

/* Footer */
.footer {
    text-align: center;
    font-size: 13px;
    color: #94a3b8;
}

.footer p {
    margin: 8px 0;
}

.footer a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.footer a:hover {
    text-decoration: underline;
}

/* Small responsiveness */
@media only screen and (max-width: 600px) {
    .email-wrapper {
        padding: 20px 12px;
    }
    .container {
        border-radius: 24px;
    }
    .inner-content {
        padding: 32px 20px;
    }
    .content {
        padding: 28px 16px;
    }
    .otp-code {
        font-size: 36px;
        letter-spacing: 6px;
        padding: 14px 18px;
    }
}
    </style>
</head>
<body style="margin:0; padding:0; background:#f1f5f9;">
    <div class="email-wrapper">
        <div class="container">
            <div class="inner-content">
                <div class="header">
                    <h1>ShopBrand.</h1>
                </div>
                <div class="content">
                    <h2>Reset Your Password</h2>
                    <p>Hello {{ $user->name }},</p>
                    <p>You requested a password reset. Use the following 6‑digit OTP code to proceed:</p>
                    <div class="otp-code">{{ $otp }}</div>
                    <div class="expiry-note">
                        <i class="fa-regular fa-clock"></i> Expires in 10 minutes
                    </div>
                    <p>If you did not request this, please ignore this email.</p>
                </div>
                <div class="divider"></div>
                <div class="footer">
                    <p>© 2026 ShopBrand Inc. All rights reserved.</p>
                    <p><a href="mailto:support@shopbrand.com">support@shopbrand.com</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>