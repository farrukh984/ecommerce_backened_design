@extends('layouts.app')

@section('content')

<div class="container cart-wrapper">

    <!-- ============ CART HEADER ============ -->
    <div class="cart-header">
        <a href="{{ route('products.index') }}" class="cart-back-link">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Shopping cart</h1>
    </div>

    <div class="cart-layout">

        <!-- ============ CART ITEMS ============ -->
        <div class="cart-items-section">

            <!-- Cart Item 1 -->
            <div class="cart-item">
                <div class="cart-item-img">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=150&h=150&fit=crop" alt="T-shirt">
                </div>
                <div class="cart-item-info">
                    <h4>T-shirts with multiple colors for men</h4>
                    <p class="cart-item-details">Size: medium, Color: blue</p>
                    <p class="cart-item-seller">Seller: Artel Market</p>
                    <div class="cart-item-actions">
                        <button class="btn-cart-remove">Remove</button>
                        <button class="btn-cart-save">Save for later</button>
                    </div>
                </div>
                <div class="cart-item-right">
                    <span class="cart-item-price">$78.99</span>
                    <div class="cart-qty-select">
                        Qty: <select>
                            <option>9</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>5</option>
                        </select>
                    </div>
                </div>
                <!-- Mobile: three dot menu + quantity controls -->
                <button class="cart-item-menu-btn"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <div class="cart-item-mobile-controls">
                    <div class="qty-stepper">
                        <button class="qty-btn">−</button>
                        <span class="qty-value">2</span>
                        <button class="qty-btn">+</button>
                    </div>
                    <span class="cart-item-price-mobile">$78.99</span>
                </div>
            </div>

            <!-- Cart Item 2 -->
            <div class="cart-item">
                <div class="cart-item-img">
                    <img src="https://images.unsplash.com/photo-1503602642458-232111445657?w=150&h=150&fit=crop" alt="Backpack">
                </div>
                <div class="cart-item-info">
                    <h4>Solid Backpack blue jeans large size</h4>
                    <p class="cart-item-details">Size: medium, Color: blue</p>
                    <p class="cart-item-seller">Seller: Artel Market</p>
                    <div class="cart-item-actions">
                        <button class="btn-cart-remove">Remove</button>
                        <button class="btn-cart-save">Save for later</button>
                    </div>
                </div>
                <div class="cart-item-right">
                    <span class="cart-item-price">$78.99</span>
                    <div class="cart-qty-select">
                        Qty: <select>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                        </select>
                    </div>
                </div>
                <button class="cart-item-menu-btn"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <div class="cart-item-mobile-controls">
                    <div class="qty-stepper">
                        <button class="qty-btn">−</button>
                        <span class="qty-value">1</span>
                        <button class="qty-btn">+</button>
                    </div>
                    <span class="cart-item-price-mobile">$78.99</span>
                </div>
            </div>

            <!-- Cart Item 3 -->
            <div class="cart-item">
                <div class="cart-item-img">
                    <img src="https://images.unsplash.com/photo-1585515320310-259814833e62?w=150&h=150&fit=crop" alt="Water boiler">
                </div>
                <div class="cart-item-info">
                    <h4>Water boiler black for kitchen, 1200 Watt</h4>
                    <p class="cart-item-details">Size: medium, Color: blue</p>
                    <p class="cart-item-seller">Seller: Artel Market</p>
                    <div class="cart-item-actions">
                        <button class="btn-cart-remove">Remove</button>
                        <button class="btn-cart-save">Save for later</button>
                    </div>
                </div>
                <div class="cart-item-right">
                    <span class="cart-item-price">$78.99</span>
                    <div class="cart-qty-select">
                        Qty: <select>
                            <option>2</option>
                            <option>1</option>
                            <option>3</option>
                        </select>
                    </div>
                </div>
                <button class="cart-item-menu-btn"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <div class="cart-item-mobile-controls">
                    <div class="qty-stepper">
                        <button class="qty-btn">−</button>
                        <span class="qty-value">2</span>
                        <button class="qty-btn">+</button>
                    </div>
                    <span class="cart-item-price-mobile">$78.99</span>
                </div>
            </div>

            <!-- Cart Bottom Actions (Desktop only) -->
            <div class="cart-bottom-actions">
                <a href="{{ route('products.index') }}" class="btn-back-shop"><i class="fa-solid fa-arrow-left"></i> Back to shop</a>
                <a href="#" class="btn-remove-all">Remove all</a>
            </div>

        </div>


        <!-- ============ CART SIDEBAR (Order Summary) ============ -->
        <div class="cart-sidebar">

            <!-- Coupon (desktop) -->
            <div class="coupon-box">
                <p>Have a coupon?</p>
                <div class="coupon-input-row">
                    <input type="text" placeholder="Add coupon">
                    <button class="btn-apply-coupon">Apply</button>
                </div>
            </div>

            <!-- Order Summary (Desktop) -->
            <div class="order-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$1403.97</span>
                </div>
                <div class="summary-row discount-row">
                    <span>Discount:</span>
                    <span class="text-discount">- $60.00</span>
                </div>
                <div class="summary-row">
                    <span>Tax:</span>
                    <span class="text-tax">+ $14.00</span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total:</span>
                    <span class="total-price">$1357.97</span>
                </div>

                <button class="btn-checkout">Checkout</button>

                <div class="payment-icons">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/100px-Mastercard-logo.svg.png" alt="Mastercard">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/100px-Visa_Inc._logo.svg.png" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/100px-PayPal.svg.png" alt="PayPal">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/30px-Apple_logo_black.svg.png" alt="Apple Pay">
                </div>
            </div>

            <!-- Mobile-only summary (matching screenshot) -->
            <div class="mobile-order-summary">
                <div class="summary-row"><span>Items (3):</span><span>$32.00</span></div>
                <div class="summary-row"><span>Shipping:</span><span>$10.00</span></div>
                <div class="summary-row"><span>Tax:</span><span>$7.00</span></div>
                <div class="summary-row summary-total"><span>Total:</span><span class="total-price">$220.00</span></div>
                <button class="btn-checkout btn-checkout-mobile">Checkout (3 items)</button>
            </div>

        </div>

    </div>

    <!-- ============ FEATURES SECTION (Desktop only) ============ -->
    <div class="cart-features">
        <div class="cart-feature-item">
            <i class="fa-solid fa-shield-halved"></i>
            <div>
                <strong>Secure payment</strong>
                <p>Have you ever finally just</p>
            </div>
        </div>
        <div class="cart-feature-item">
            <i class="fa-solid fa-headset"></i>
            <div>
                <strong>Customer support</strong>
                <p>Have you ever finally just</p>
            </div>
        </div>
        <div class="cart-feature-item">
            <i class="fa-solid fa-truck-fast"></i>
            <div>
                <strong>Free delivery</strong>
                <p>Have you ever finally just</p>
            </div>
        </div>
    </div>

    <!-- ============ SAVED FOR LATER ============ -->
    <div class="saved-section">
        <h3>Saved for later</h3>

        <!-- Desktop: grid of cards -->
        <div class="saved-grid">
            <div class="saved-item">
                <div class="saved-item-img">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&h=200&fit=crop" alt="T-shirt">
                </div>
                <div class="saved-item-info">
                    <span class="saved-price">$99.50</span>
                    <p>GoPro HERO6 4K Action Camera - Black</p>
                    <button class="btn-move-cart"><i class="fa-solid fa-cart-shopping"></i> Move to cart</button>
                </div>
            </div>
            <div class="saved-item">
                <div class="saved-item-img">
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=200&h=200&fit=crop" alt="Phone">
                </div>
                <div class="saved-item-info">
                    <span class="saved-price">$99.50</span>
                    <p>GoPro HERO6 4K Action Camera - Black</p>
                    <button class="btn-move-cart"><i class="fa-solid fa-cart-shopping"></i> Move to cart</button>
                </div>
            </div>
            <div class="saved-item">
                <div class="saved-item-img">
                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=200&fit=crop" alt="Headphones">
                </div>
                <div class="saved-item-info">
                    <span class="saved-price">$99.50</span>
                    <p>GoPro HERO6 4K Action Camera - Black</p>
                    <button class="btn-move-cart"><i class="fa-solid fa-cart-shopping"></i> Move to cart</button>
                </div>
            </div>
            <div class="saved-item">
                <div class="saved-item-img">
                    <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=200&h=200&fit=crop" alt="Laptop">
                </div>
                <div class="saved-item-info">
                    <span class="saved-price">$99.50</span>
                    <p>GoPro HERO6 4K Action Camera - Black</p>
                    <button class="btn-move-cart"><i class="fa-solid fa-cart-shopping"></i> Move to cart</button>
                </div>
            </div>
        </div>

        <!-- Mobile: horizontal list layout (matching screenshot) -->
        <div class="saved-list-mobile">
            <div class="saved-list-item">
                <div class="saved-list-img">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=100&h=100&fit=crop" alt="Shirt">
                </div>
                <div class="saved-list-info">
                    <h5>Regular Fit Resort Shirt</h5>
                    <span class="saved-list-price">$57.70</span>
                    <div class="saved-list-actions">
                        <button class="btn-saved-move">Move to cart</button>
                        <button class="btn-saved-remove">Remove</button>
                    </div>
                </div>
            </div>
            <div class="saved-list-item">
                <div class="saved-list-img">
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=100&h=100&fit=crop" alt="Phone">
                </div>
                <div class="saved-list-info">
                    <h5>Regular Fit Resort Shirt</h5>
                    <span class="saved-list-price">$57.70</span>
                    <div class="saved-list-actions">
                        <button class="btn-saved-move">Move to cart</button>
                        <button class="btn-saved-remove">Remove</button>
                    </div>
                </div>
            </div>
            <div class="saved-list-item">
                <div class="saved-list-img">
                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&h=100&fit=crop" alt="Headphones">
                </div>
                <div class="saved-list-info">
                    <h5>Regular Fit Resort Shirt</h5>
                    <span class="saved-list-price">$57.70</span>
                    <div class="saved-list-actions">
                        <button class="btn-saved-move">Move to cart</button>
                        <button class="btn-saved-remove">Remove</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ DISCOUNT BANNER (Desktop only) ============ -->
    <div class="discount-banner">
        <div class="discount-banner-text">
            <h3>Super discount on more than 100 USD</h3>
            <p>Have you ever finally just write dummy info</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-shop-now">Shop now</a>
    </div>

</div>

@endsection
