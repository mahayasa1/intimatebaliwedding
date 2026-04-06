@extends('layouts.app')

@section('title', 'Contact Us - Intimate Bali Wedding')

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=1920&q=80');
        background-size: cover;
        background-position: center;
        height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-top: -80px;
        padding-top: 80px;
    }

    .contact-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 700;
    }

    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 0.5fr 2fr;
        gap: 4rem;
        margin-top: 3rem;
    }

    .contact-info {
        background: #f8f8f8;
        padding: 2.5rem;
        border-radius: 8px;
    }

    .contact-info h3 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        font-size: 1.5rem;
        margin-bottom: 2rem;
    }

    .contact-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        align-items: start;
    }

    .contact-icon {
        font-size: 1.5rem;
        color: #D4AF37;
        min-width: 30px;
    }

    .contact-details h4 {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .contact-details p,
    .contact-details a {
        color: #666;
        text-decoration: none;
        line-height: 1.6;
    }

    .contact-details a:hover {
        color: #D4AF37;
    }

    .contact-form {
        background: white;
    }

    .contact-form h3 {
        font-family: 'Playfair Display', serif;
        color: #333;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        color: #333;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #D4AF37;
    }

    .form-group textarea {
        min-height: 150px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .submit-btn {
        background: #D4AF37;
        color: white;
        padding: 1rem 3rem;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.95rem;
        width: 100%;
    }

    .submit-btn:hover {
        background: #B8941F;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1.5rem;
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

    .map-container {
        margin-top: 4rem;
        border-radius: 8px;
        overflow: hidden;
        height: 400px;
        background: #f0f0f0;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    @media (max-width: 768px) {
        .contact-hero h1 {
            font-size: 2.5rem;
        }

        .contact-container {
            padding: 3rem 1rem;
        }

        .contact-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="contact-hero">
    <div>
        <h1>CONTACT US</h1>
        <p style="font-size: 1.1rem; font-weight: 300;">Let's Create Your Dream Wedding Together</p>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-container">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h2 style="font-family: 'Playfair Display', serif; color: #D4AF37; font-size: 2rem; margin-bottom: 1rem;">
            Get In Touch
        </h2>
        <p style="color: #666; max-width: 700px; margin: 0 auto;">
            Ready to start planning your perfect wedding? Contact us today for a free consultation. 
            We're here to answer all your questions and help bring your vision to life.
        </p>
    </div>

    <div class="contact-grid">
        <!-- Contact Info -->
        <div class="contact-info">
            <h3>Contact Information</h3>

            <div class="contact-item">
                <div class="contact-icon">📍</div>
                <div class="contact-details">
                    <h4>Our Office</h4>
                    <p>
                        Jl. Puri Dewata No. 98,<br>
                        Sidakarya, Denpasar, Bali<br>
                    </p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon">📧</div>
                <div class="contact-details">
                    <h4>Email</h4>
                    <a href="mailto:initmatebaliwedding@gmail.com">initmatebaliwedding@gmail.com</a><br>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon">📱</div>
                <div class="contact-details">
                    <h4>Phone / WhatsApp</h4>
                    <a href="tel:+6282145678901">+62 821 4567 8901</a><br>
                </div>
            </div>

            {{-- <div class="contact-item">
                <div class="contact-icon">🕐</div>
                <div class="contact-details">
                    <h4>Working Hours</h4>
                    <p>
                        Monday - Friday: 9:00 AM - 6:00 PM<br>
                        Saturday: 10:00 AM - 4:00 PM<br>
                        Sunday: By Appointment
                    </p>
                </div>
            </div> --}}
        </div>

        <!-- Contact Form -->
        <div class="contact-form">
            <h3>Send Us a Message</h3>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.5rem;">
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
                            <option value="Beach Wedding" {{ old('wedding_type') == 'Beach Wedding' ? 'selected' : '' }}>Beach Wedding</option>
                            <option value="Garden Wedding" {{ old('wedding_type') == 'Garden Wedding' ? 'selected' : '' }}>Garden Wedding</option>
                            <option value="Chapel Wedding" {{ old('wedding_type') == 'Chapel Wedding' ? 'selected' : '' }}>Chapel Wedding</option>
                            <option value="Villa Wedding" {{ old('wedding_type') == 'Villa Wedding' ? 'selected' : '' }}>Villa Wedding</option>
                            <option value="Other" {{ old('wedding_type') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="guest_count">Guest Count (Approx.)</label>
                        <input type="number" id="guest_count" name="guest_count" value="{{ old('guest_count') }}" placeholder="2">
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">Your Message *</label>
                    <textarea id="message" name="message" required placeholder="Tell us about your dream wedding...">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </div>

    <!-- Map -->
    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3943.9420480960516!2d115.22811858456305!3d-8.697053657340675!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd241042d75b901%3A0xf63a36a1e45364d9!2sJl.%20Puri%20Dewata%20No.98%2C%20Sidakarya%2C%20Denpasar%20Selatan%2C%20Kota%20Denpasar%2C%20Bali%2080224!5e0!3m2!1sid!2sid!4v1772613900681!5m2!1sid!2sid" 
            width="600" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

</section>
@endsection

@push('scripts')
<script>
    document.getElementById('enquiryForm')?.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('.submit-btn');
        submitBtn.textContent = 'Sending...';
        submitBtn.disabled = true;
    });
</script>
@endpush