<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500;700&display=swap');
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }
        .email-header {
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            padding: 50px 40px;
            text-align: center;
            color: white;
        }
        .email-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 32px;
            margin: 0;
            font-weight: 800;
        }
        .email-header p {
            font-size: 16px;
            opacity: 0.9;
            margin: 10px 0 0;
        }

        .content-body {
            padding: 40px;
        }

        /* Order Tracker Styles */
        .order-tracker {
            padding: 30px 0;
            margin-bottom: 30px;
            text-align: center;
        }
        .tracker-steps {
            display: table;
            width: 100%;
            table-layout: fixed;
            position: relative;
        }
        .step {
            display: table-cell;
            text-align: center;
            position: relative;
        }
        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-block;
            line-height: 36px;
            font-size: 14px;
            font-weight: bold;
            z-index: 2;
            position: relative;
        }
        .step-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 10px;
            color: #94a3b8;
        }
        
        /* Line between circles */
        .step::after {
            content: '';
            position: absolute;
            top: 18px;
            left: 50%;
            width: 100%;
            height: 3px;
            background: #e2e8f0;
            z-index: 1;
        }
        .step:last-child::after {
            display: none;
        }

        /* Active/Completed States */
        .completed .step-circle {
            background: #10b981;
            color: white;
        }
        .completed .step-label {
            color: #10b981;
        }
        .completed::after {
            background: #10b981;
        }

        .active .step-circle {
            background: #0ea5e9;
            color: white;
            box-shadow: 0 0 0 5px rgba(14, 165, 233, 0.2);
        }
        .active .step-label {
            color: #0ea5e9;
        }

        .pending .step-circle {
            background: #f1f5f9;
            color: #cbd5e1;
        }

        .order-summary {
            background: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            margin-top: 30px;
        }
        .summary-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            display: block;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-table td {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .item-table tr:last-child td {
            border-bottom: none;
        }

        .footer-premium {
            background: #f1f5f9;
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: white !important;
            padding: 16px 40px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            margin: 30px 0;
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2);
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div style="font-size: 13px; font-weight: 700; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">Update on Order #{{ $order->id }}</div>
            <h1>Your Order is Moving!</h1>
            <p>We're excited to give you a quick update.</p>
        </div>

        <div class="content-body">
            <p style="font-size: 16px; margin-bottom: 30px;">Hi <strong>{{ $order->name }}</strong>,</p>
            <p>Your order status has been updated from <strong>{{ ucfirst($oldStatus) }}</strong> to <strong>{{ ucfirst($order->status) }}</strong>. Here's exactly where it stands now:</p>

            <!-- Progress Tracker -->
            <div class="order-tracker">
                <div class="tracker-steps">
                    @php
                        $allStatuses = ['pending', 'approved', 'processing', 'shipped', 'delivered'];
                        $currentIndex = array_search($order->status, $allStatuses);
                        if($currentIndex === false) $currentIndex = -1;
                    @endphp

                    @foreach($allStatuses as $index => $status)
                        @php
                            $class = 'pending';
                            if ($index < $currentIndex) $class = 'completed';
                            elseif ($index === $currentIndex) $class = 'active';
                        @endphp
                        <div class="step {{ $class }}">
                            <div class="step-circle">
                                @if($class == 'completed')
                                    ✓
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>
                            <span class="step-label">{{ $status }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="text-align: center; margin-top: 40px;">
                @if($order->status == 'delivered')
                    <div style="background: #ecfdf5; border-radius: 12px; padding: 20px; color: #065f46; font-weight: 600;">
                        🎉 Amazing! Your package has been delivered.
                    </div>
                @else
                    <div style="background: #f0f9ff; border-radius: 12px; padding: 20px; color: #0369a1; font-weight: 600;">
                        🚀 We're working hard to get your order to you!
                    </div>
                @endif

                <a href="{{ route('user.orders.show', $order->id) }}" class="cta-button">View Order Journey</a>
            </div>

            <!-- Summary Card -->
            <div class="order-summary">
                <span class="summary-title">Order Recap</span>
                <table class="item-table">
                    @foreach($order->items as $item)
                    <tr>
                        <td style="font-weight: 600; color: #0f172a;">
                            {{ $item->product->name ?? 'Product' }}
                            <div style="font-size: 11px; color: #94a3b8; font-weight: 400; margin-top: 2px;">Qty: {{ $item->quantity }}</div>
                        </td>
                        <td align="right" style="font-weight: 700; color: #0ea5e9;">
                            ${{ number_format($item->price * $item->quantity, 2) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="padding-top: 20px; font-size: 16px; font-weight: 800;">Amount Paid</td>
                        <td align="right" style="padding-top: 20px; font-size: 18px; font-weight: 800; color: #1e293b;">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer-premium">
            <p>Thank you for choosing <strong>{{ config('app.name') }}</strong>.</p>
            <p style="margin-top: 15px; opacity: 0.6;">&copy; {{ date('Y') }} All rights reserved.</p>
        </div>
    </div>
</body>
</html>
