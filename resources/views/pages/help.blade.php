@extends('layouts.app')

@section('content')
<style>
    .help-hero {
        background: linear-gradient(135deg, #0d6efd, #0ea5e9);
        padding: 60px 0 40px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .help-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .help-hero h1 {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }
    .help-hero p {
        font-size: 16px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: 0 auto;
    }
    .help-hero .hero-icon {
        font-size: 50px;
        margin-bottom: 16px;
        display: block;
        position: relative;
        z-index: 1;
    }
    .help-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 20px;
    }
    .help-section {
        margin-bottom: 48px;
    }
    .help-section h2 {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .faq-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        margin-bottom: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .faq-item:hover {
        border-color: #3b82f6;
    }
    .faq-question {
        padding: 18px 24px;
        font-weight: 700;
        font-size: 15px;
        color: #1e293b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .faq-question i {
        transition: transform 0.3s;
        color: #94a3b8;
    }
    .faq-item.open .faq-question i {
        transform: rotate(180deg);
        color: #3b82f6;
    }
    .faq-answer {
        padding: 0 24px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
    }
    .faq-item.open .faq-answer {
        padding: 0 24px 18px;
        max-height: 300px;
    }
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }
    .contact-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        text-align: center;
        transition: all 0.3s;
    }
    .contact-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        transform: translateY(-3px);
    }
    .contact-card .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 16px;
    }
    .contact-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
    }
    .contact-card p {
        font-size: 13px;
        color: #64748b;
    }
    .help-cta {
        background: linear-gradient(135deg, #0d6efd, #0ea5e9);
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        color: white;
    }
    .help-cta h3 {
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 10px;
    }
    .help-cta p {
        opacity: 0.9;
        margin-bottom: 20px;
    }
    .help-cta a {
        display: inline-block;
        background: white;
        color: #0d6efd;
        padding: 12px 32px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s;
    }
    .help-cta a:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
</style>

<div class="help-hero">
    <span class="hero-icon">💡</span>
    <h1>Help Center</h1>
    <p>Find answers to frequently asked questions and get the support you need</p>
</div>

<div class="help-container">
    {{-- FAQ Section --}}
    <div class="help-section">
        <h2><i class="fa-solid fa-circle-question" style="color: #3b82f6;"></i> Frequently Asked Questions</h2>
        
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
        <h2><i class="fa-solid fa-headset" style="color: #10b981;"></i> Get in Touch</h2>
        <div class="contact-grid">
            <div class="contact-card">
                <div class="icon-circle" style="background: #e0f2fe; color: #0ea5e9;">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h3>Email Us</h3>
                <p>support@brand.com</p>
                <p style="margin-top: 4px; font-size: 12px;">Response within 24 hours</p>
            </div>
            <div class="contact-card">
                <div class="icon-circle" style="background: #dcfce7; color: #10b981;">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <h3>Call Us</h3>
                <p>+92 300 1234567</p>
                <p style="margin-top: 4px; font-size: 12px;">Mon-Sat, 9 AM - 6 PM</p>
            </div>
            <div class="contact-card">
                <div class="icon-circle" style="background: #f3e8ff; color: #7c3aed;">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3>Live Chat</h3>
                <p>Message us from your dashboard</p>
                <p style="margin-top: 4px; font-size: 12px;">Available 24/7</p>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="help-section">
        <div class="help-cta">
            <h3>Still need help?</h3>
            <p>Send us a detailed inquiry and our team will get back to you!</p>
            <a href="{{ route('home') }}#inquiry-section"><i class="fa-solid fa-paper-plane"></i> Send Inquiry</a>
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
