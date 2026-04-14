@extends('layouts.app')

@section('title', 'Contact Us - Intimate Bali Wedding')

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                    url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=1920&q=80');
        background-size: cover;
        background-position: center;
        height: 50vh; /* samakan */
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-top: -80px; /* penting */
        padding-top: 80px; /* penting */
    }
    .contact-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    
    .contact-hero p {
        font-size: 1.1rem;
        font-weight: 300;
        letter-spacing: 1px;
    }

    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .contact-intro {
        text-align: center;
        margin-bottom: 3rem;
    }

    .contact-intro h2 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .contact-intro p {
        color: #666;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.8;
    }

    /* ---- Grid layout ---- */
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 3rem;
        align-items: start;
    }

    @media (max-width: 900px) {
        .contact-grid { grid-template-columns: 1fr; gap: 2rem; }
    }

    /* ---- Contact Info sidebar ---- */
    .contact-info {
        background: #f8f8f8;
        padding: 2rem;
        border-radius: 10px;
        position: sticky;
        top: 100px;
    }

    .contact-info h3 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        font-size: 1.4rem;
        margin-bottom: 1.75rem;
    }

    .contact-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.75rem;
        align-items: flex-start;
    }

    .contact-icon {
        font-size: 1.4rem;
        color: #D4AF37;
        min-width: 28px;
        margin-top: 2px;
    }

    .contact-details h4 {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.4rem;
        font-size: 0.95rem;
    }

    .contact-details p,
    .contact-details a {
        color: #666;
        text-decoration: none;
        line-height: 1.7;
        font-size: 0.9rem;
    }

    .contact-details a:hover { color: #D4AF37; }

    @media (max-width: 900px) {
        .contact-info { position: static; }
    }

    /* ---- Form ---- */
    .contact-form h3 {
        font-family: 'Playfair Display', serif;
        color: #333;
        font-size: 1.75rem;
        margin-bottom: 1.75rem;
    }

    .form-group { margin-bottom: 1.25rem; }

    .form-group label {
        display: block;
        color: #444;
        font-weight: 600;
        margin-bottom: 0.45rem;
        font-size: 0.88rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1.5px solid #ddd;
        border-radius: 6px;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        transition: border-color 0.25s, box-shadow 0.25s;
        background: white;
        color: #333;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #D4AF37;
        box-shadow: 0 0 0 3px rgba(212,175,55,0.12);
    }

    .form-group textarea {
        min-height: 140px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    @media (max-width: 640px) {
        .form-row { grid-template-columns: 1fr; gap: 0; }
    }

    /* ---- Submit ---- */
    .submit-btn {
        background: #D4AF37;
        color: white;
        padding: 0.9rem 2.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.95rem;
        width: 100%;
        margin-top: 0.5rem;
    }

    .submit-btn:hover {
        background: #B8941F;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(212,175,55,0.35);
    }

    .submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    /* ---- Alerts ---- */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* ---- Map ---- */
    .map-container {
        margin-top: 4rem;
        border-radius: 10px;
        overflow: hidden;
        height: 400px;
        background: #f0f0f0;
    }

    .map-container iframe {
        width: 100%; height: 100%;
        border: none;
        display: block;
    }

    @media (max-width: 768px) {
        .contact-container { padding: 3rem 1rem; }
        .map-container { height: 300px; }
    }
</style>
@endpush

@section('content')
<section class="contact-hero">
    <div>
        <h1>CONTACT US</h1>
        <p>Let's Create Your Dream Wedding Together</p>
    </div>
</section>

<section class="contact-container">
    <div class="contact-intro">
        <h2>Get In Touch</h2>
        <p>Ready to start planning your perfect wedding? Contact us today for a free consultation.
           We're here to answer all your questions and help bring your vision to life.</p>
    </div>

    <div class="contact-grid">
        <!-- Info Sidebar -->
        <div class="contact-info">
            <h3>Contact Information</h3>

            <div class="contact-item">
                <div class="contact-icon">📍</div>
                <div class="contact-details">
                    <h4>Our Office</h4>
                    <p>Jl. Anggrek No.27, <br> Kota Denpasar, Bali</p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon">📧</div>
                <div class="contact-details">
                    <h4>Email</h4>
                    <a href="mailto:intimatebaliwedding@gmail.com">intimatebaliwedding@gmail.com</a>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon">📱</div>
                <div class="contact-details">
                    <h4>Phone / WhatsApp</h4>
                    <a href="tel:+6287861775445">+62 878-6177-5445</a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="contact-form">
            <h3>Send Us a Message</h3>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('enquiry.store') }}" method="POST" id="enquiryForm">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="John & Jane">
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email *</label>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="email@example.com">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+62 812 3456 7890">
                    </div>
                    <div class="form-group">
                        <label for="wedding_date">Wedding Date</label>
                        <input type="date" id="wedding_date" name="wedding_date" value="{{ old('wedding_date') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="wedding_type">Wedding Type</label>
                        <select id="wedding_type" name="wedding_type">
                            <option value="">Select Type</option>
                            <option value="Beach Wedding"  {{ old('wedding_type') == 'Beach Wedding'  ? 'selected' : '' }}>Beach Wedding</option>
                            <option value="Garden Wedding" {{ old('wedding_type') == 'Garden Wedding' ? 'selected' : '' }}>Garden Wedding</option>
                            <option value="Chapel Wedding" {{ old('wedding_type') == 'Chapel Wedding' ? 'selected' : '' }}>Chapel Wedding</option>
                            <option value="Villa Wedding"  {{ old('wedding_type') == 'Villa Wedding'  ? 'selected' : '' }}>Villa Wedding</option>
                            <option value="Other"          {{ old('wedding_type') == 'Other'          ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="guest_count">Guest Count (Approx.)</label>
                        <input type="number" id="guest_count" name="guest_count" value="{{ old('guest_count') }}" placeholder="e.g. 2" min="1">
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">Your Message *</label>
                    <textarea id="message" name="message" required placeholder="Tell us about your dream wedding...">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">Send Message</button>
            </form>
        </div>
    </div>

    <!-- Map -->
    <div class="map-container">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15777.54422603834!2d115.2241908960825!3d-8.65478399261499!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd2409b54344ba7%3A0x7323c96f2c696381!2sJl.%20Anggrek%20No.27%2C%20Dangin%20Puri%20Kangin%2C%20Kec.%20Denpasar%20Utara%2C%20Kota%20Denpasar%2C%20Bali%2080236!5e0!3m2!1sid!2sid!4v1775898931395!5m2!1sid!2sid"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Our Office Location">
        </iframe>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.getElementById('enquiryForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.textContent = 'Sending…';
        btn.disabled = true;
    });
</script>
@endpush