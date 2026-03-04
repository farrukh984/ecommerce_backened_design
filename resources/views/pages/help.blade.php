@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/help.css') }}">

<div class="help-hero">
    <span class="hero-icon">💡</span>
    <h1>Help Center</h1>
    <p>Find answers to frequently asked questions and get the support you need</p>
</div>

<div class="help-container">
    {{-- FAQ Section --}}
    <div class="help-section">
        <h2><i class="fa-solid fa-circle-question"></i> Frequently Asked Questions</h2>
        
        <div class="faq-item open">
            <div class="faq-question" onclick="toggleFaq(this)">
                How do I place an order?
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Simply browse our products, add items to your cart, and proceed to checkout. Fill in your shipping details and confirm your order. You'll receive a confirmation email shortly after.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                What payment methods do you accept?
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                We accept Cash on Delivery (COD), bank transfers, and major credit/debit cards. More payment options are coming soon!
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                How can I track my order?
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                After placing your order, you can track its status from your Dashboard under "My Orders". You'll see real-time updates on your order status.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                What is your return policy?
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                We offer a 7-day return policy on most items. If you're not satisfied with your purchase, contact us within 7 days of delivery for a hassle-free return.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                How do I cancel an order?
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                You can cancel any pending order from your Dashboard > My Orders section. Simply click the delete button on a pending order. Once an order is shipped, it cannot be cancelled.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                How do deals and discounts work?
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                We regularly offer limited-time deals with countdown timers on our homepage. Keep an eye on "Hot Offers" in the navigation bar for the latest discounts. Loyal customers may also receive exclusive coupon codes!
            </div>
        </div>
    </div>

    {{-- Contact Methods --}}
    <div class="help-section">
        <h2><i class="fa-solid fa-headset green"></i> Get in Touch</h2>
        <div class="contact-grid">
            <div class="contact-card">
                <div class="icon-circle blue">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h3>Email Us</h3>
                <p>farrokh984@gmail.com</p>
                <p class="sub-text">Response within 24 hours</p>
            </div>
            <div class="contact-card">
                <div class="icon-circle green">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <h3>Call Us</h3>
                <p>+92 3708541533</p>
                <p class="sub-text">Mon-Sat, 9 AM - 6 PM</p>
            </div>
            <div class="contact-card">
                <div class="icon-circle purple">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3>Live Chat</h3>
                <p>Message us from your dashboard</p>
                <p class="sub-text">Available 24/7</p>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="help-section">
        <div class="help-cta">
            <h3>Still need help?</h3>
            <p>Send us a detailed inquiry and our team will get back to you!</p>
            <a href="{{ route('home') }}#inquiry-section" class="btn-cta"><i class="fa-solid fa-paper-plane"></i> Send Inquiry</a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function toggleFaq(el) {
    const item = el.closest('.faq-item');
    const wasOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if (!wasOpen) item.classList.add('open');
}
</script>
@endsection
