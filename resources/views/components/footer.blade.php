<style>
    footer {
        background: #3a3a3a;
        color: white;
        padding: 4rem 0 0;
        overflow-x: hidden;
    }

    .footer-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3rem;
        margin-bottom: 3rem;
        padding-bottom: 3rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .footer-section h3 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .footer-section p {
        color: #ccc;
        line-height: 1.8;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .footer-section a {
        color: #ccc;
        text-decoration: none;
        display: block;
        margin-bottom: 0.7rem;
        transition: all 0.25s;
        line-height: 1.8;
        font-size: 0.9rem;
    }

    .footer-section a:hover {
        color: #D4AF37;
        padding-left: 5px;
    }

    /* Logo */
    .footer-logo {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: #D4AF37;
        margin-bottom: 1rem;
        display: block;
    }

    .footer-description { color: #ccc; line-height: 1.8; margin-bottom: 1.5rem; font-size: 0.9rem; }

    /* Social */
    .social-links { display: flex; gap: 0.75rem; flex-wrap: wrap; }

    .social-links a {
        width: 42px; height: 42px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s;
        margin-bottom: 0;
        padding-left: 0 !important;
    }

    .social-links a:hover {
        background: #D4AF37;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(212,175,55,0.3);
    }

    .social-links svg { width: 18px; height: 18px; }

    /* Contact items */
    .contact-info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
        color: #ccc;
    }

    .contact-info-item a {
        display: inline;
        margin-bottom: 0;
        padding-left: 0 !important;
        font-size: 0.88rem;
    }

    .contact-icon { color: #D4AF37; min-width: 20px; font-size: 1.1rem; margin-top: 2px; }
    .contact-text { flex: 1; line-height: 1.7; font-size: 0.88rem; }

    /* Footer bottom */
    .footer-bottom {
        padding: 1.25rem 0;
        text-align: center;
        color: #999;
        font-size: 0.85rem;
    }

    .footer-bottom-content { display: flex; flex-direction: column; gap: 0.75rem; align-items: center; }

    .footer-bottom-links { display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center; }

    .footer-bottom-links a {
        color: #999; text-decoration: none;
        transition: color 0.25s; font-size: 0.85rem;
    }

    .footer-bottom-links a:hover { color: #D4AF37; }

    /* Responsive */
    @media (max-width: 900px) {
        .footer-content { grid-template-columns: 1fr 1fr; gap: 2rem; }
    }

    @media (max-width: 600px) {
        footer { padding: 2.5rem 0 0; }
        .footer-container { padding: 0 1.25rem; }
        .footer-content { grid-template-columns: 1fr; gap: 2rem; }
        .footer-section a:hover { padding-left: 0; }
        .social-links { justify-content: flex-start; }
        .footer-bottom-links { flex-direction: column; gap: 0.5rem; align-items: center; }
    }
</style>

<footer id="contact">
    <div class="footer-container">
        <div class="footer-content">
            <!-- About -->
            <div class="footer-section">
                <div class="footer-logo">INTIMATE BALI WEDDING</div>
                <p class="footer-description">
                    Making dreams come true, one celebration at a time. We specialize in creating unforgettable
                    moments for your special day across Bali.
                </p>
                <div class="social-links">
                    {{-- <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a> --}}
                    <a href="https://instagram.com/intimatebaliwedding" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 01-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 017.8 2m-.2 2A3.6 3.6 0 004 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 003.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 011.25 1.25A1.25 1.25 0 0117.25 8 1.25 1.25 0 0116 6.75a1.25 1.25 0 011.25-1.25M12 7a5 5 0 015 5 5 5 0 01-5 5 5 5 0 01-5-5 5 5 0 015-5m0 2a3 3 0 00-3 3 3 3 0 003 3 3 3 0 003-3 3 3 0 00-3-3z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h3>Quick Menu</h3>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about') }}">About Us</a>
                <a href="{{ route('packages.public') }}">Wedding Packages</a>
                <a href="{{ route('gallery.public') }}">Gallery &amp; Testimonials</a>
                <a href="{{ route('blogs.public') }}">Blog</a>
                <a href="{{ route('contact') }}">Contact Us</a>
            </div>

            <!-- Contact -->
            <div class="footer-section">
                <h3>Contact Us</h3>
                <div class="contact-info-item">
                    <div class="contact-icon">📧</div>
                    <div class="contact-text">
                        <a href="mailto:intimatebaliwedding@gmail.com">intimatebaliwedding@gmail.com</a>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-icon">📱</div>
                    <div class="contact-text">
                        <a href="tel:+6287861775445">+62 878-6177-5445</a>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-icon">📍</div>
                    <div class="contact-text">
                        Jl. Anggrek No.27, <br> Kota Denpasar, Bali
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; {{ date('Y') }} SKYNUSA TECH. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="#privacy">Privacy Policy</a>
                    <a href="#terms">Terms of Service</a>
                    <a href="#sitemap">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>