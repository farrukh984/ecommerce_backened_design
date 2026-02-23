@extends('layouts.app')

@section('content')

<div class="container cart-wrapper">

    <!-- ============ CART HEADER ============ -->
    <div class="cart-header">
        <a href="{{ route('products.index') }}" class="cart-back-link">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="cart-title-desktop">My cart ({{ count($cart) }})</h1>
        <h1 class="cart-title-mobile">Shopping cart</h1>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    @if(count($cart) > 0)
    <div class="cart-layout">

        <!-- ============ CART ITEMS ============ -->
        <div class="cart-items-section">
            @php $subtotal = 0; @endphp
            @foreach($cart as $id => $details)
                @php $subtotal += $details['price'] * $details['quantity']; @endphp
                <div class="cart-item" data-id="{{ $id }}">
                    <div class="cart-item-img">
                        <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}">
                    </div>
                    <div class="cart-item-info">
                        <h4>{{ $details['name'] }}</h4>
                        @if(!empty($details['attributes']))
                            <p class="cart-item-details">
                                Size: {{ $details['attributes']['size'] }}, Color: {{ $details['attributes']['color'] }}, Material: {{ $details['attributes']['material'] }}
                            </p>
                        @endif
                        <p class="cart-item-seller">Seller: {{ $details['seller'] ?? 'Artel Market' }}</p>
                        
                        <!-- Desktop Actions -->
                        <div class="cart-item-actions">
                            <form action="{{ route('cart.remove') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" class="btn-cart-action remove">Remove</button>
                            </form>
                            <form action="{{ route('cart.saveLater', $id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-cart-action save">Save for later</button>
                            </form>
                        </div>
                    </div>

                    <!-- Desktop Quantity & Price -->
                    <div class="cart-item-right">
                        <div class="cart-qty-control">
                            <span class="qty-label">Qty:</span>
                            <div class="qty-selector">
                                <button type="button" class="qty-btn" onclick="updateQtyManual('{{ $id }}', -1)">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="number" class="qty-input" data-id="{{ $id }}" value="{{ $details['quantity'] }}" min="1" readonly>
                                <button type="button" class="qty-btn" onclick="updateQtyManual('{{ $id }}', 1)">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <span class="cart-item-price">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                    </div>

                    <!-- Mobile Three-dot Menu -->
                    <button class="cart-item-menu-btn" onclick="toggleMobileMenu(this)">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>

                    <!-- Mobile Dropdown Menu -->
                    <div class="mobile-dropdown-menu">
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <button type="submit" class="mobile-menu-item">
                                <i class="fa-solid fa-trash"></i> Remove
                            </button>
                        </form>
                        <form action="{{ route('cart.saveLater', $id) }}" method="POST">
                            @csrf
                            <button type="submit" class="mobile-menu-item">
                                <i class="fa-regular fa-heart"></i> Save for later
                            </button>
                        </form>
                    </div>
                    
                    <!-- Mobile Controls (Qty + Price) -->
                    <div class="cart-item-mobile-controls">
                        <div class="qty-stepper">
                            <button class="qty-btn-mobile" onclick="updateQtyManual('{{ $id }}', -1)">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <span class="qty-value">{{ $details['quantity'] }}</span>
                            <button class="qty-btn-mobile" onclick="updateQtyManual('{{ $id }}', 1)">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <span class="cart-item-price-mobile">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                    </div>
                </div>
            @endforeach

            <!-- Cart Bottom Actions (Desktop) -->
            <div class="cart-bottom-actions">
                <a href="{{ route('products.index') }}" class="btn-back-shop">
                    <i class="fa-solid fa-arrow-left"></i> Back to shop
                </a>
                <form action="{{ route('cart.remove') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-remove-all">Remove all</button>
                </form>
            </div>
        </div>

        <!-- ============ CART SIDEBAR (Order Summary) ============ -->
        <div class="cart-sidebar">
            
            <div class="coupon-box">
                <p>Have a coupon?</p>
                <form action="{{ route('cart.applyCoupon') }}" method="POST">
                    @csrf
                    <div class="coupon-input-row">
                        <input type="text" name="coupon" placeholder="Add coupon" value="{{ session('applied_coupon') }}">
                        <button type="submit" class="btn-apply-coupon">Apply</button>
                    </div>
                </form>
            </div>

            <div class="order-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($totals['subtotal'], 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Discount:</span>
                    <span class="text-discount">- ${{ number_format($totals['discount'], 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Tax (5%):</span>
                    <span class="text-tax">+ ${{ number_format($totals['tax'], 2) }}</span>
                </div>
                <hr class="summary-divider">
                <div class="summary-row summary-total">
                    <span>Total:</span>
                    <span class="total-price">${{ number_format($totals['total'], 2) }}</span>
                </div>

                @auth
                    <form action="{{ route('cart.checkout') }}" method="GET">
                        <button type="submit" class="btn-checkout">Checkout</button>
                    </form>
                @else
                    <div class="auth-notice">
                        <p>Please login to complete your order.</p>
                        <a href="{{ route('login') }}" class="btn-checkout">Login to Checkout</a>
                    </div>
                @endauth

                <div class="payment-icons">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/100px-Mastercard-logo.svg.png" alt="Mastercard">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/100px-Visa_Inc._logo.svg.png" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/100px-PayPal.svg.png" alt="PayPal">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/50px-Apple_logo_black.svg.png" alt="Apple Pay">
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MOBILE ORDER SUMMARY ============ -->
    <div class="mobile-order-summary">
        <div class="mobile-summary-content">
            <div class="summary-row">
                <span>Items ({{ count($cart) }}):</span>
                <span>${{ number_format($totals['subtotal'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Shipping:</span>
                <span>${{ number_format($totals['shipping'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Tax:</span>
                <span>${{ number_format($totals['tax'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Discount:</span>
                <span class="text-discount">-${{ number_format($totals['discount'], 2) }}</span>
            </div>
            <div class="summary-row summary-total-mobile">
                <strong>Total:</strong>
                <strong>${{ number_format($totals['total'], 2) }}</strong>
            </div>
        </div>

        @auth
            <form action="{{ route('cart.checkout') }}" method="GET">
                <button type="submit" class="btn-checkout-mobile">Checkout ({{ count($cart) }} items)</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-checkout-mobile">Login to Checkout</a>
        @endauth
    </div>

    <!-- ============ FEATURES SECTION ============ -->
    <div class="cart-features">
        <div class="cart-feature-item">
            <div class="feature-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="feature-text">
                <strong>Secure payment</strong>
                <p>Have you ever finally just</p>
            </div>
        </div>
        <div class="cart-feature-item">
            <div class="feature-icon">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div class="feature-text">
                <strong>Customer support</strong>
                <p>Have you ever finally just</p>
            </div>
        </div>
        <div class="cart-feature-item">
            <div class="feature-icon">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div class="feature-text">
                <strong>Free delivery</strong>
                <p>Have you ever finally just</p>
            </div>
        </div>
    </div>

    @else
    <!-- Empty Cart State -->
    <div class="cart-empty-state">
        <i class="fa-solid fa-cart-shopping"></i>
        <h2>Your cart is empty</h2>
        <p>Looks like you haven't added anything to your cart yet.</p>
        <a href="{{ route('products.index') }}" class="btn-shop-now">Shop Now</a>
    </div>
    @endif

    <!-- ============ SAVED FOR LATER ============ -->
    @if(count($saved) > 0)
    <div class="saved-section">
        <h3>Saved for later</h3>

        <div class="saved-grid">
            @foreach($saved as $sid => $sitem)
            <div class="saved-item">
                <div class="saved-item-img">
                    <img src="{{ asset('storage/' . $sitem['image']) }}" alt="{{ $sitem['name'] }}">
                </div>
                <div class="saved-item-info">
                    <span class="saved-price">${{ number_format($sitem['price'], 2) }}</span>
                    <p class="saved-name">{{ Str::limit($sitem['name'], 50) }}</p>
                    <p class="saved-meta">GoPro HERO6 4K Action Camera - Black</p>
                    <form action="{{ route('cart.moveToCart', $sid) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-move-cart">
                            <i class="fa-solid fa-cart-shopping"></i> Move to cart
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Mobile Saved List -->
        <div class="saved-list-mobile">
            @foreach($saved as $sid => $sitem)
            <div class="saved-list-item">
                <div class="saved-list-img">
                    <img src="{{ asset('storage/' . $sitem['image']) }}" alt="{{ $sitem['name'] }}">
                </div>
                <div class="saved-list-info">
                    <h5>{{ Str::limit($sitem['name'], 35) }}</h5>
                    <span class="saved-list-price">${{ number_format($sitem['price'], 2) }}</span>
                    <div class="saved-list-actions">
                        <form action="{{ route('cart.moveToCart', $sid) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-saved-move">Move to cart</button>
                        </form>
                        <form action="{{ route('cart.removeSaved', $sid) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-saved-remove">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ============ DISCOUNT BANNER ============ -->
    <div class="discount-banner">
        <div class="discount-banner-content">
            <h3>Super discount on more than 100 USD</h3>
            <p>Have you ever finally just wrote dummy info</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-shop-banner">Shop now</a>
    </div>

</div>

@endsection

@section('scripts')
<script>
    // Update quantity via input change
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            let qty = parseInt(this.value);
            if (isNaN(qty) || qty < 1) qty = 1;
            const id = this.getAttribute('data-id');
            updateCartQty(id, qty);
        });
    });

    function updateQtyManual(id, delta) {
        const input = document.querySelector(`.qty-input[data-id="${id}"]`);
        const mobileQtyValue = document.querySelector(`.cart-item[data-id="${id}"] .qty-value`);
        
        let currentQty = parseInt(input.value);
        let newQty = currentQty + delta;
        if (newQty < 1) newQty = 1;
        
        updateCartQty(id, newQty);
    }

    function updateCartQty(id, qty) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('cart.update') }}";
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = "{{ csrf_token() }}";
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;
        
        const qtyInput = document.createElement('input');
        qtyInput.type = 'hidden';
        qtyInput.name = 'quantity';
        qtyInput.value = qty;
        
        form.appendChild(csrf);
        form.appendChild(idInput);
        form.appendChild(qtyInput);
        document.body.appendChild(form);
        form.submit();
    }

    // Toggle mobile dropdown menu
    function toggleMobileMenu(button) {
        const dropdown = button.nextElementSibling;
        const allDropdowns = document.querySelectorAll('.mobile-dropdown-menu');
        
        // Close all other dropdowns
        allDropdowns.forEach(menu => {
            if (menu !== dropdown) {
                menu.classList.remove('show');
            }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.cart-item-menu-btn') && !event.target.closest('.mobile-dropdown-menu')) {
            document.querySelectorAll('.mobile-dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
</script>
@endsection
