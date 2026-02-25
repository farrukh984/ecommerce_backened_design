<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
        .wrapper { padding: 40px 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1); border-top: 6px solid #f59e0b; }
        .header { background: #1e293b; padding: 40px; text-align: center; color: white; }
        .content { padding: 40px; color: #334155; }
        .review-quote { background: #fffcf0; border-left: 4px solid #f59e0b; padding: 20px; margin: 20px 0; font-style: italic; color: #92400e; border-radius: 0 12px 12px 0; }
        .rating { color: #f59e0b; font-size: 24px; margin-bottom: 10px; }
        .item-info { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 14px 28px; background: #f59e0b; color: white !important; text-decoration: none; border-radius: 10px; font-weight: 700; margin-top: 20px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div style="font-size: 32px; margin-bottom: 10px;">⭐</div>
                <h1 style="margin: 0; font-size: 24px;">New Review!</h1>
            </div>
            <div class="content">
                <p><strong>{{ $review->user->name ?? 'A customer' }}</strong> has left a review for:</p>
                <h3 style="margin-top: 5px;">{{ $review->product->name ?? 'Product' }}</h3>
                
                <div class="rating">
                    @for($i=0; $i<5; $i++)
                        @if($i < $review->rating) ★ @else ☆ @endif
                    @endfor
                </div>

                <div class="review-quote">
                    "{{ $review->comment }}"
                </div>

                <div style="text-align: center;">
                    <a href="{{ url('/admin/reviews') }}" class="btn">Approve / Moderate Review</a>
                </div>
            </div>
            <div class="footer">
                Approved reviews will be visible on the product page.
            </div>
        </div>
    </div>
</body>
</html>
