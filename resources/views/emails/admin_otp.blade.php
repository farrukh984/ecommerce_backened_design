<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin OTP</title>
    <style>
        /* paste the CSS here or inline later */

/* admin-otp.css – Modern, professional styles for admin OTP email */

/* Reset & base styles (safe for email clients) */
body {
    margin: 0;
    padding: 0;
    background-color: #f4f6f9;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    line-height: 1.5;
    color: #1e293b;
}

/* Main email container – centered card */
.email-wrapper {
    width: 100%;
    table-layout: fixed;
    background-color: #f4f6f9;
    padding: 40px 0;
}

.email-container {
    max-width: 480px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 24px;
    box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.1), 0 5px 10px -4px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    border: 1px solid #e9eef2;
}

/* Content padding */
.email-content {
    padding: 40px 32px;
}

/* Brand header */
.brand-header {
    text-align: center;
    margin-bottom: 32px;
}

.brand-name {
    font-size: 28px;
    font-weight: 700;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, #2563eb, #1e40af);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    color: #2563eb; /* fallback */
}

/* Greeting text */
.greeting {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 12px;
    color: #0f172a;
}

.greeting span {
    font-weight: 600;
    color: #2563eb;
}

/* Message */
.message {
    font-size: 16px;
    color: #475569;
    margin-bottom: 28px;
}

/* OTP code display */
.otp-container {
    text-align: center;
    margin: 32px 0;
}

.otp-label {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 10px;
}

.otp-code {
    display: inline-block;
    font-size: 42px;
    font-weight: 700;
    letter-spacing: 8px;
    padding: 16px 24px;
    background: linear-gradient(145deg, #f8fafc, #f1f5f9);
    border: 2px dashed #2563eb;
    border-radius: 20px;
    color: #1e293b;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    font-family: 'Courier New', monospace;
}

/* Expiry note */
.expiry-note {
    font-size: 14px;
    color: #64748b;
    background: #f8fafc;
    padding: 16px 20px;
    border-radius: 16px;
    margin: 28px 0 20px;
    border-left: 4px solid #2563eb;
    text-align: left;
}

.expiry-note i {
    color: #2563eb;
    margin-right: 6px;
}

/* Support text */
.support-text {
    font-size: 14px;
    color: #94a3b8;
    margin: 24px 0 10px;
    border-top: 1px solid #e2e8f0;
    padding-top: 24px;
    text-align: center;
}

.support-text a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.support-text a:hover {
    text-decoration: underline;
}

/* Footer signature */
.signature {
    font-size: 16px;
    font-weight: 500;
    color: #0f172a;
    margin-top: 24px;
    text-align: center;
}

.signature i {
    color: #2563eb;
    margin: 0 4px;
}

/* Responsive touch */
@media only screen and (max-width: 600px) {
    .email-container {
        border-radius: 16px;
        margin: 0 16px;
    }
    .email-content {
        padding: 32px 20px;
    }
    .otp-code {
        font-size: 36px;
        letter-spacing: 6px;
        padding: 12px 16px;
    }
}

    </style>
</head>
<body style="margin:0; padding:0; background:#f4f6f9; font-family: 'Inter', ...;">
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-content">
                <div class="brand-header">
                    <div class="brand-name">ShopBrand</div>
                </div>
                <div class="greeting">Hello <span>{{ $user->name }}</span>,</div>
                <div class="message">Your one-time admin login code is:</div>
                <div class="otp-container">
                    <div class="otp-label">Verification code</div>
                    <div class="otp-code">{{ $otp }}</div>
                </div>
                <div class="expiry-note">
                    <i class="fa-regular fa-clock"></i> This code expires in 10 minutes.
                </div>
                <div class="support-text">
                    If you did not request this, please <a href="mailto:support@shopbrand.com">contact support</a>.
                </div>
                <div class="signature">
                    <i class="fa-regular fa-shield"></i> ShopBrand Security
                </div>
            </div>
        </div>
    </div>
</body>
</html>
