@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h3 class="fw-bold mb-4">Checkout Details</h3>

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('cart.placeOrder') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control rounded-3" value="{{ old('email', auth()->user()->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control rounded-3" value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Zip Code</label>
                                <input type="text" name="zip_code" class="form-control rounded-3" value="{{ old('zip_code') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control rounded-3" value="{{ old('address') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control rounded-3" value="{{ old('city') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control rounded-3" value="{{ old('state') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control rounded-3" value="{{ old('country', 'Pakistan') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Order Notes</label>
                                <textarea name="notes" rows="3" class="form-control rounded-3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 20px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Order Summary</h4>

                    @foreach($cart as $details)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 10px;">
                            <div class="ms-3 flex-grow-1">
                                <div class="small fw-semibold">{{ \Illuminate\Support\Str::limit($details['name'], 30) }}</div>
                                <small class="text-muted">Qty: {{ $details['quantity'] }}</small>
                            </div>
                            <div class="fw-semibold">${{ number_format($details['price'] * $details['quantity'], 2) }}</div>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span>${{ number_format($totals['subtotal'], 2) }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span>Tax</span><span>${{ number_format($totals['tax'], 2) }}</span></div>
                    <div class="d-flex justify-content-between mb-3"><span>Shipping</span><span>${{ number_format($totals['shipping'], 2) }}</span></div>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4"><span>Total</span><span>${{ number_format($totals['total'], 2) }}</span></div>

                    <button type="submit" form="checkout-form" class="btn btn-primary w-100 py-3 rounded-3 fw-semibold">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
