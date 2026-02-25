<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
        .wrapper { padding: 40px 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border-top: 6px solid #10b981; }
        .header { background: #1e293b; padding: 40px; text-align: center; color: white; }
        .content { padding: 40px; color: #334155; }
        .summary-card { background: #f0fdf4; border-radius: 16px; padding: 24px; margin-bottom: 30px; text-align: center; }
        .amount { font-size: 32px; font-weight: 800; color: #166534; margin: 10px 0; }
        .item-list { border-top: 1px solid #e2e8f0; margin-top: 20px; padding-top: 20px; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .btn { display: inline-block; padding: 14px 35px; background: #10b981; color: white !important; text-decoration: none; border-radius: 12px; font-weight: 700; margin-top: 20px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div style="font-size: 32px; margin-bottom: 10px;">💰</div>
                <h1 style="margin: 0; font-size: 24px;">New Order Alert!</h1>
                <p style="opacity: 0.8;">Order #{{ $order->id }} has been placed</p>
            </div>
            <div class="content">
                <div class="summary-card">
                    <div style="font-size: 12px; font-weight: 700; color: #166534; text-transform: uppercase;">Total Revenue</div>
                    <div class="amount">${{ number_format($order->total_amount, 2) }}</div>
                    <div style="font-size: 13px; color: #166534;">From: {{ $order->name }}</div>
                </div>

                <h3 style="margin-bottom: 15px;">Order Summary:</h3>
                <div class="item-list">
                    @foreach($order->items as $item)
                        <div style="display: table; width: 100%; margin-bottom: 10px;">
                            <div style="display: table-cell; font-weight: 600;">{{ $item->product->name ?? 'Product' }} (x{{ $item->quantity }})</div>
                            <div style="display: table-cell; text-align: right; color: #64748b;">${{ number_format($item->price * $item->quantity, 2) }}</div>
                        </div>
                    @endforeach
                </div>

                <div style="text-align: center; border-top: 1px solid #e2e8f0; margin-top: 30px;">
                    <a href="{{ url('/admin/orders/'.$order->id) }}" class="btn">Manage This Order</a>
                </div>
            </div>
            <div class="footer">
                Check your Admin Dashboard for full fulfillment details.
            </div>
        </div>
    </div>
</body>
</html>
