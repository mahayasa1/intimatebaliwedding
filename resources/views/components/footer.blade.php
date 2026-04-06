<style>
    /* Footer Styles */
    footer {
        background: #3a3a3a;
        color: white;
        padding: 4rem 0 0;
    }

    .footer-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 3rem;
        margin-bottom: 3rem;
        padding-bottom: 3rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-section h3 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .footer-section p {
        color: #ccc;
        line-height: 1.8;
        margin-bottom: 1rem;
    }

    .footer-section a {
        color: #ccc;
        text-decoration: none;
        display: block;
        margin-bottom: 0.8rem;
        transition: all 0.3s;
        line-height: 1.8;
    }

    .footer-section a:hover {
        color: #D4AF37;
        padding-left: 5px;
    }

    /* Footer About */
    .footer-about {
        max-width: 100%;
    }

    .footer-logo {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #D4AF37;
        margin-bottom: 1rem;
        display: block;
    }

    .footer-description {
        color: #ccc;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }

    /* Social Links */
    .social-links {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .social-links a {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        margin-bottom: 0;
    }

    .social-links a:hover {
        background: #D4AF37;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        padding-left: 0;
    }

    .social-links svg {
        width: 20px;
        height: 20px;
    }

    /* Footer Links */
    .footer-links {
        display: flex;
        flex-direction: column;
    }

    /* Contact Info */
    .contact-info-item {
        display: flex;
        align-items: start;
        gap: 0.8rem;
        margin-bottom: 1rem;
        color: #ccc;
    }

    .contact-info-item:hover {
        padding-left: 0;
    }

    .contact-icon {
        font-size: 1.2rem;
        color: #D4AF37;
        min-width: 20px;
        margin-top: 2px;
    }

    .contact-text {
        flex: 1;
        line-height: 1.8;
    }

    .contact-text a {
        display: inline;
        margin-bottom: 0;
    }

    .contact-text a:hover {
        padding-left: 0;
        text-decoration: underline;
    }

    /* Newsletter */
    .newsletter-form {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .newsletter-input {
        flex: 1;
        padding: 0.8rem 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.05);
        color: white;
        border-radius: 4px;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .newsletter-input:focus {
        outline: none;
        border-color: #D4AF37;
        background: rgba(255, 255, 255, 0.1);
    }

    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .newsletter-btn {
        padding: 0.8rem 1.5rem;
        background: #D4AF37;
        color: white;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .newsletter-btn:hover {
        background: #B8941F;
        transform: translateY(-2px);
    }

    /* Footer Bottom */
    .footer-bottom {
        padding: 1rem 0;
        text-align: center;
        color: #999;
        font-size: 0.9rem;
        margin-top: -2rem;
    }

    .footer-bottom-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }

    .footer-bottom-links {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .footer-bottom-links a {
        color: #999;
        text-decoration: none;
        transition: color 0.3s;
        font-size: 0.9rem;
    }

    .footer-bottom-links a:hover {
        color: #D4AF37;
    }

    /* Responsive */
    @media (max-width: 768px) {
        footer {
            padding: 3rem 0 0;
        }

        .footer-container {
            padding: 0 1rem;
        }

        .footer-content {
            grid-template-columns: 1fr;
            gap: 2rem;
            text-align: center;
        }

        .footer-section a:hover {
            padding-left: 0;
        }

        .social-links {
            justify-content: center;
        }

        .contact-info-item {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .newsletter-form {
            flex-direction: column;
        }

        .newsletter-btn {
            width: 100%;
        }

        .footer-bottom-links {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .footer-content {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<!-- Footer -->
<footer id="contact">
    <div class="footer-container">
        <div class="footer-content">
            <!-- About Section -->
            <div class="footer-section footer-about">
                <div class="footer-logo">INTIMATE BALI WEDDING</div>
                <p class="footer-description">
                    Making dreams come true, one celebration at a time. We specialize in creating unforgettable 
                    moments for your special day in the most beautiful locations across Bali.
                </p>
                <div class="social-links">
                    <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                        </svg>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 01-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 017.8 2m-.2 2A3.6 3.6 0 004 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 003.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 011.25 1.25A1.25 1.25 0 0117.25 8 1.25 1.25 0 0116 6.75a1.25 1.25 0 011.25-1.25M12 7a5 5 0 015 5 5 5 0 01-5 5 5 5 0 01-5-5 5 5 0 015-5m0 2a3 3 0 00-3 3 3 3 0 003 3 3 3 0 003-3 3 3 0 00-3-3z"/>
                        </svg>
                    </a>
                    <a href="https://pinterest.com" target="_blank" rel="noopener" aria-label="Pinterest">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9.04 21.54c.96.29 1.93.46 2.96.46a10 10 0 0010-10A10 10 0 0012 2 10 10 0 002 12c0 4.25 2.67 7.9 6.44 9.34-.09-.78-.18-2.07 0-2.96l1.15-4.94s-.29-.58-.29-1.5c0-1.38.86-2.41 1.84-2.41.86 0 1.26.63 1.26 1.44 0 .86-.57 2.09-.86 3.27-.17.98.52 1.84 1.52 1.84 1.78 0 3.16-1.9 3.16-4.58 0-2.4-1.72-4.04-4.19-4.04-2.82 0-4.48 2.1-4.48 4.31 0 .86.28 1.73.74 2.3.09.06.09.14.06.29l-.29 1.09c0 .17-.11.23-.28.11-1.28-.56-2.02-2.38-2.02-3.85 0-3.16 2.24-6.03 6.56-6.03 3.44 0 6.12 2.47 6.12 5.75 0 3.44-2.13 6.2-5.18 6.2-.97 0-1.92-.52-2.26-1.13l-.67 2.37c-.23.86-.86 2.01-1.29 2.7v-.03z"/>
                        </svg>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13 1.33-.22 2.65-.28 1.3-.07 2.49-.1 3.59-.1L12 5c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73z"/>
                        </svg>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Twitter">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.85.38-1.78.64-2.75.76a4.8 4.8 0 002.11-2.65c-.93.55-1.95.95-3.05 1.17a4.8 4.8 0 00-8.18 4.37A13.6 13.6 0 013.39 4.62a4.8 4.8 0 001.49 6.4 4.77 4.77 0 01-2.17-.6v.06a4.8 4.8 0 003.85 4.7 4.77 4.77 0 01-2.16.08 4.8 4.8 0 004.48 3.33A9.62 9.62 0 012 19.54a13.56 13.56 0 007.29 2.14c8.75 0 13.54-7.25 13.54-13.54 0-.21 0-.41-.02-.61A9.65 9.65 0 0024 4.59a9.55 9.55 0 01-2.54.7z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Menu -->
            <div class="footer-section">
                <h3>Quick Menu</h3>
                <div class="footer-links">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('about') }}">About Us</a>
                    <a href="{{ route('packages.public') }}">Wedding Packages</a>
                    <a href="{{ route('gallery.public') }}">Gallery & Testimonials</a>
                    <a href="{{ route('blogs.public') }}">Blog</a>
                    <a href="{{ route('contact') }}">Contact Us</a>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h3>Contact Us</h3>
                <div class="contact-info-item">
                    <div class="contact-icon">📧</div>
                    <div class="contact-text">
                        <a href="mailto:initmatebaliwedding@gmail.com">intimatebaliwedding@gmail.com</a>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-icon">📱</div>
                    <div class="contact-text">
                        <a href="tel:+6282145678901">+62 821 4567 8901</a><br>
                        <a href="tel:+6282198765432">+62 821 9876 5432</a>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-icon">📍</div>
                    <div class="contact-text">
                        Jl. Puri Dewata No. 98<br>
                        Sidakarya, Denpasar, Bali<br>
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            {{-- <div class="footer-section">
                <h3>Newsletter</h3>
                <p>Subscribe to receive our latest news and special offers.</p>
                <form class="newsletter-form" onsubmit="return false;">
                    <input type="email" class="newsletter-input" placeholder="Your email address" required>
                    <button type="submit" class="newsletter-btn">Subscribe</button>
                </form>
            </div> --}}
        </div>

        <!-- Footer Bottom -->
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

<script>
    // Newsletter Form Handler
    document.querySelector('.newsletter-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('.newsletter-input').value;
        
        // Here you would typically send the email to your backend
        alert('Thank you for subscribing! We will send updates to: ' + email);
        this.reset();
    });
</script>