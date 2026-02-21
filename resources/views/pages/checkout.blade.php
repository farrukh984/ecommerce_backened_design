@extends('layouts.app')

@section('content')
<div class="checkout-wrapper">
    <div class="container py-5">
        <!-- Breadcrumb -->
        <div class="checkout-breadcrumb">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <a href="{{ route('cart') }}">Cart</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Checkout</span>
        </div>

        <!-- Progress Steps -->
        <div class="checkout-steps">
            <div class="step completed">
                <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                <span>Cart</span>
            </div>
            <div class="step-line completed"></div>
            <div class="step active">
                <div class="step-circle">2</div>
                <span>Checkout</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-circle">3</div>
                <span>Confirmation</span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <div class="checkout-card-icon">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div>
                            <h3>Shipping Details</h3>
                            <p>Enter your delivery information</p>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="checkout-alert">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('cart.placeOrder') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="checkout-form-grid">
                            <div class="form-field">
                                <label class="form-field-label">
                                    <i class="fa-solid fa-user"></i> Full Name
                                </label>
                                <input type="text" name="name" class="form-field-input" value="{{ old('name', auth()->user()->name) }}" required placeholder="John Doe">
                            </div>
                            <div class="form-field">
                                <label class="form-field-label">
                                    <i class="fa-solid fa-envelope"></i> Email
                                </label>
                                <input type="email" name="email" class="form-field-input" value="{{ old('email', auth()->user()->email) }}" required placeholder="john@example.com">
                            </div>
                            <div class="form-field">
                                <label class="form-field-label">
                                    <i class="fa-solid fa-phone"></i> Phone
                                </label>
                                <input type="text" name="phone" class="form-field-input" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="+92 300 1234567">
                            </div>
                            <div class="form-field">
                                <label class="form-field-label">
                                    <i class="fa-solid fa-map-pin"></i> Zip Code
                                </label>
                                <input type="text" name="zip_code" class="form-field-input" value="{{ old('zip_code', auth()->user()->zip_code) }}" required placeholder="54000">
                            </div>
                            <div class="form-field full-width">
                                <label class="form-field-label">
                                    <i class="fa-solid fa-location-dot"></i> Address
                                </label>
                                <input type="text" name="address" class="form-field-input" value="{{ old('address', auth()->user()->address) }}" required placeholder="123 Main Street, Block A">
                            </div>
                            <div class="form-field">
                                <label class="form-field-label">
                                    <i class="fa-solid fa-city"></i> City
                                </label>
                                <input type="text" name="city" class="form-field-input" value="{{ old('city', auth()->user()->city) }}" required placeholder="Lahore">
                            </div>
                            <div class="form-field">
                                <label class="form-field-label">
                                    <i class="fa-solid fa-map"></i> State
                                </label>
                                <input type="text" name="state" class="form-field-input" value="{{ old('state', auth()->user()->state) }}" placeholder="Punjab">
                            </div>
                            <div class="form-field">
                                <label class="form-field-label">
                                    <i class="fa-solid fa-globe"></i> Country
                                </label>
                                <input type="text" name="country" class="form-field-input" value="{{ old('country', auth()->user()->country ?? 'Pakistan') }}" required placeholder="Pakistan">
                            </div>
                            <div class="form-field full-width">
                                <label class="form-field-label">
                                    <i class="fa-solid fa-comment-dots"></i> Order Notes (Optional)
                                </label>
                                <textarea name="notes" rows="3" class="form-field-input form-field-textarea" placeholder="Any special delivery instructions...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Secure Payment Info -->
                <div class="checkout-security-banner">
                    <div class="security-item">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Secure Checkout</span>
                    </div>
                    <div class="security-item">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>Fast Delivery</span>
                    </div>
                    <div class="security-item">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Easy Returns</span>
                    </div>
                    <div class="security-item">
                        <i class="fa-solid fa-headset"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="order-summary-card position-sticky" style="top: 20px;">
                    <div class="summary-header">
                        <i class="fa-solid fa-receipt"></i>
                        <h4>Order Summary</h4>
                    </div>

                    <div class="summary-items">
                        @foreach($cart as $details)
                            <div class="summary-product">
                                <div class="summary-product-image">
                                    <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}">
                                    <span class="summary-qty-badge">{{ $details['quantity'] }}</span>
                                </div>
                                <div class="summary-product-info">
                                    <p class="summary-product-name">{{ \Illuminate\Support\Str::limit($details['name'], 28) }}</p>
                                    <span class="summary-product-qty">Qty: {{ $details['quantity'] }}</span>
                                </div>
                                <div class="summary-product-price">${{ number_format($details['price'] * $details['quantity'], 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-totals">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>${{ number_format($totals['subtotal'], 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax (5%)</span>
                            <span>${{ number_format($totals['tax'], 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span class="free-shipping">{{ $totals['shipping'] == 0 ? 'FREE' : '$' . number_format($totals['shipping'], 2) }}</span>
                        </div>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-total-row">
                        <span>Total</span>
                        <span class="summary-total-amount">${{ number_format($totals['total'], 2) }}</span>
                    </div>

                    <button type="submit" form="checkout-form" class="checkout-submit-btn" id="placeOrderBtn">
                        <i class="fa-solid fa-lock"></i>
                        <span>Place Order Securely</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                    <p class="checkout-terms">
                        By placing your order, you agree to our 
                        <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('placeOrderBtn');
    const originalContent = btn.innerHTML;
    
    // Show loading state
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Processing Order...</span>';
    btn.style.opacity = '0.7';
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '🎉 Order Placed Successfully!',
                html: `
                    <div style="text-align: center; padding: 10px 0;">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #0d6efd, #4895ef); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-check" style="color: white; font-size: 32px;"></i>
                        </div>
                        <p style="font-size: 16px; color: #1c1c1c; font-weight: 600; margin-bottom: 8px;">Thank you for your purchase!</p>
                        <p style="font-size: 14px; color: #8b96a5; line-height: 1.6;">Your order has been received and is now being processed. You'll receive a confirmation shortly.</p>
                        <div style="background: #f4f5f7; border-radius: 12px; padding: 14px; margin-top: 16px;">
                            <p style="font-size: 13px; color: #505050; margin: 0;"><i class="fa-solid fa-truck-fast" style="color: #0d6efd; margin-right: 8px;"></i> Estimated delivery: 3-5 business days</p>
                        </div>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: '<i class="fa-solid fa-bag-shopping"></i> View My Orders',
                confirmButtonColor: '#0d6efd',
                allowOutsideClick: false,
                customClass: {
                    popup: 'swal-checkout-popup',
                    confirmButton: 'swal-checkout-btn'
                },
                backdrop: `
                    rgba(0,0,0,0.6)
                    left top
                    no-repeat
                `,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = data.redirect;
                }
            });
        } else {
            throw new Error(data.message || 'Something went wrong');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalContent;
        btn.style.opacity = '1';
        
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: error.message || 'Something went wrong. Please try again.',
            confirmButtonColor: '#0d6efd',
        });
    });
});
</script>
@endsection
