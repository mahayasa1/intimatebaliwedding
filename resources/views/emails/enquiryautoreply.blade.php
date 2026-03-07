<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You – Intimate Bali Wedding</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', serif;
            background-color: #f5f0eb;
            color: #333;
        }
        .wrapper {
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
        }
        /* Header */
        .header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 48px 48px 36px;
            text-align: center;
        }
        .header-ornament {
            color: #D4AF37;
            font-size: 32px;
            letter-spacing: 10px;
            margin-bottom: 16px;
        }
        .header h1 {
            color: #ffffff;
            font-size: 26px;
            font-weight: normal;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .header-tagline {
            color: #D4AF37;
            font-size: 13px;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-style: italic;
        }
        /* Gold Banner */
        .gold-banner {
            background: linear-gradient(90deg, #C9972A, #D4AF37, #C9972A);
            padding: 16px 48px;
            text-align: center;
        }
        .gold-banner p {
            color: #1a1a1a;
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: bold;
        }
        /* Hero Image Text */
        .hero-section {
            background: linear-gradient(rgba(26,26,26,0.7), rgba(26,26,26,0.7)),
                        url('https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80') center/cover;
            padding: 56px 48px;
            text-align: center;
        }
        .hero-section h2 {
            color: #ffffff;
            font-size: 28px;
            font-weight: normal;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }
        .hero-section h2 span {
            color: #D4AF37;
        }
        .hero-section p {
            color: rgba(255,255,255,0.8);
            font-size: 15px;
            line-height: 1.7;
        }
        /* Body */
        .body {
            padding: 44px 48px;
        }
        .intro-text {
            font-size: 15px;
            color: #555;
            line-height: 1.9;
            margin-bottom: 32px;
        }
        /* Summary Card */
        .summary-card {
            background: #faf7f3;
            border: 1px solid #e8e0d5;
            border-radius: 4px;
            padding: 28px 32px;
            margin-bottom: 36px;
        }
        .summary-card-title {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #D4AF37;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e8e0d5;
        }
        .summary-row {
            display: flex;
            padding: 9px 0;
            border-bottom: 1px solid #f0ebe4;
            font-size: 14px;
        }
        .summary-row:last-child {
            border-bottom: none;
        }
        .summary-label {
            color: #999;
            width: 42%;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding-top: 1px;
        }
        .summary-value {
            color: #333;
            flex: 1;
            font-weight: 500;
        }
        /* What Happens Next */
        .next-steps {
            margin-bottom: 36px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #D4AF37;
            border-bottom: 1px solid #e8e0d5;
            padding-bottom: 8px;
            margin-bottom: 24px;
        }
        .step-item {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
            align-items: flex-start;
        }
        .step-number {
            background: #D4AF37;
            color: #1a1a1a;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .step-content h4 {
            font-size: 14px;
            color: #1a1a1a;
            margin-bottom: 4px;
        }
        .step-content p {
            font-size: 13px;
            color: #777;
            line-height: 1.6;
        }
        /* Contact Box */
        .contact-box {
            background: #1a1a1a;
            border-radius: 4px;
            padding: 28px 32px;
            text-align: center;
            margin-bottom: 32px;
        }
        .contact-box p {
            color: #999;
            font-size: 13px;
            margin-bottom: 16px;
            line-height: 1.6;
        }
        .contact-box .contact-links {
            display: inline-block;
        }
        .contact-link {
            display: inline-block;
            color: #D4AF37;
            text-decoration: none;
            font-size: 13px;
            margin: 4px 12px;
            letter-spacing: 0.5px;
        }
        /* Social */
        .social-section {
            text-align: center;
            margin-bottom: 32px;
        }
        .social-section p {
            color: #999;
            font-size: 13px;
            margin-bottom: 14px;
        }
        .social-btn {
            display: inline-block;
            border: 1px solid #D4AF37;
            color: #D4AF37;
            text-decoration: none;
            padding: 10px 24px;
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border-radius: 2px;
            margin: 0 6px;
        }
        /* Divider */
        .divider {
            text-align: center;
            margin: 28px 0;
            color: #D4AF37;
            font-size: 16px;
            letter-spacing: 8px;
        }
        /* Footer */
        .footer {
            background: #1a1a1a;
            padding: 32px 48px;
            text-align: center;
        }
        .footer-logo {
            color: #D4AF37;
            font-size: 16px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .footer p {
            color: #666;
            font-size: 11px;
            line-height: 1.8;
        }
        .footer a {
            color: #D4AF37;
            text-decoration: none;
        }
        .footer-note {
            color: #444 !important;
            font-size: 10px !important;
            margin-top: 16px;
            border-top: 1px solid #2d2d2d;
            padding-top: 14px;
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <!-- Header -->
        <div class="header">
            <div class="header-ornament">💍</div>
            <h1>Intimate Bali Wedding</h1>
            <p class="header-tagline">Crafting Timeless Love Stories in Bali</p>
        </div>

        <!-- Gold Banner -->
        <div class="gold-banner">
            <p>✨ Thank You for Reaching Out ✨</p>
        </div>

        <!-- Hero Section -->
        <div class="hero-section">
            <h2>Dear <span>{{ $enquiry->name }}</span>,</h2>
            <p>
                Your enquiry has been received and we are absolutely<br>
                delighted to hear from you.
            </p>
        </div>

        <!-- Body -->
        <div class="body">

            <p class="intro-text">
                Thank you so much for considering <strong>Intimate Bali Wedding</strong> for your special day. 
                We understand how important this moment is, and we are fully committed to making your 
                dream wedding in Bali a beautiful and unforgettable reality.<br><br>
                Our team is already reviewing your enquiry and will be in touch with you shortly — 
                typically within <strong>1–2 business days</strong>.
            </p>

            <!-- Summary Card -->
            <div class="summary-card">
                <div class="summary-card-title">📋 Your Enquiry Summary</div>

                <div class="summary-row">
                    <span class="summary-label">Name</span>
                    <span class="summary-value">{{ $enquiry->name }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Email</span>
                    <span class="summary-value">{{ $enquiry->email }}</span>
                </div>
                @if($enquiry->phone)
                <div class="summary-row">
                    <span class="summary-label">Phone</span>
                    <span class="summary-value">{{ $enquiry->phone }}</span>
                </div>
                @endif
                @if($enquiry->wedding_date)
                <div class="summary-row">
                    <span class="summary-label">Wedding Date</span>
                    <span class="summary-value">{{ \Carbon\Carbon::parse($enquiry->wedding_date)->format('d F Y') }}</span>
                </div>
                @endif
                @if($enquiry->wedding_type)
                <div class="summary-row">
                    <span class="summary-label">Wedding Type</span>
                    <span class="summary-value">{{ $enquiry->wedding_type }}</span>
                </div>
                @endif
                @if($enquiry->guest_count)
                <div class="summary-row">
                    <span class="summary-label">Guest Count</span>
                    <span class="summary-value">{{ $enquiry->guest_count }} guests (approx.)</span>
                </div>
                @endif
                <div class="summary-row">
                    <span class="summary-label">Message</span>
                    <span class="summary-value" style="font-style: italic; color: #555;">{{ $enquiry->message }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Submitted</span>
                    <span class="summary-value" style="color: #999; font-size: 13px;">
                        {{ $enquiry->created_at->format('d F Y, H:i') }} UTC
                    </span>
                </div>
            </div>

            <!-- What Happens Next -->
            <div class="next-steps">
                <div class="section-title">What Happens Next?</div>
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Review Your Enquiry</h4>
                        <p>Our team will carefully review your details and start crafting a personalised proposal for your wedding.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Initial Consultation</h4>
                        <p>We'll reach out via email or WhatsApp within 1–2 business days to schedule a free consultation call.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Custom Proposal</h4>
                        <p>After understanding your vision, we'll present a bespoke wedding package tailored just for you.</p>
                    </div>
                </div>
            </div>

            <div class="divider">✦ ✦ ✦</div>

            <!-- Contact Box -->
            <div class="contact-box">
                <p>
                    Can't wait? Feel free to reach out to us directly.<br>
                    We're always happy to chat about your dream day!
                </p>
                <div class="contact-links">
                    <a href="mailto:intimatebaliwedding@gmail.com" class="contact-link">
                        ✉ intimatebaliwedding@gmail.com
                    </a>
                    <a href="https://wa.me/6282145678901" class="contact-link">
                        💬 WhatsApp Us
                    </a>
                </div>
            </div>

            <!-- Social -->
            <div class="social-section">
                <p>Follow our journey and get inspired by real Bali weddings</p>
                <a href="https://instagram.com/intimatebaliwedding" class="social-btn">
                    📸 Instagram
                </a>
            </div>

        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">Intimate Bali Wedding</div>
            <p>
                Jl. Puri Dewata No. 98, Sidakarya, Denpasar, Bali<br>
                <a href="mailto:intimatebaliwedding@gmail.com">intimatebaliwedding@gmail.com</a>
                &nbsp;·&nbsp;
                <a href="https://wa.me/6282145678901">+62 821 4567 8901</a>
            </p>
            <p class="footer-note">
                This email was sent because you submitted an enquiry on our website.<br>
                If you did not submit this enquiry, please disregard this email.
            </p>
        </div>

    </div>
</body>
</html>