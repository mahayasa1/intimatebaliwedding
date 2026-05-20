<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap');

    .ibw-footer {
        background: #1c1a17;
        color: #e8e0d0;
        font-family: 'DM Sans', sans-serif;
        padding: 4rem 0 0;
        border-top: 1px solid rgba(212,175,55,0.15);
        width: 100%;
        box-sizing: border-box;
    }

    .ibw-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .ibw-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr 1.2fr;
        gap: 3.5rem;
        padding-bottom: 3rem;
        border-bottom: 1px solid rgba(255,255,255,0.07);
    }

    .ibw-logo {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: #D4AF37;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        display: block;
        margin-bottom: 1rem;
    }

    .ibw-tagline {
        font-size: 0.83rem;
        color: #9e9488;
        line-height: 1.85;
        margin-bottom: 1.6rem;
        font-weight: 300;
    }

    .ibw-divider {
        width: 36px;
        height: 1px;
        background: #D4AF37;
        margin-bottom: 1.4rem;
        opacity: 0.6;
    }

    .ibw-socials {
        display: flex;
        gap: 10px;
    }

    .ibw-socials a {
        width: 38px;
        height: 38px;
        border: 1px solid rgba(212,175,55,0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9e9488;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .ibw-socials a:hover {
        background: #D4AF37;
        border-color: #D4AF37;
        color: #1c1a17;
    }

    .ibw-socials svg {
        width: 16px;
        height: 16px;
    }

    .ibw-col-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1rem;
        font-weight: 600;
        color: #D4AF37;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 1.4rem;
        margin-top: 0;
    }

    .ibw-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .ibw-links li {
        margin-bottom: 0.6rem;
    }

    .ibw-links a {
        color: #9e9488;
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 300;
        transition: color 0.25s, letter-spacing 0.25s;
        display: inline-block;
    }

    .ibw-links a:hover {
        color: #D4AF37;
        letter-spacing: 0.01em;
    }

    .ibw-contact-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .ibw-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
    }

    .ibw-contact-icon {
        color: #D4AF37;
        font-size: 1rem;
        margin-top: 1px;
        flex-shrink: 0;
        opacity: 0.85;
    }

    .ibw-contact-text {
        font-size: 0.83rem;
        color: #9e9488;
        line-height: 1.7;
        font-weight: 300;
    }

    .ibw-contact-text a {
        color: #9e9488;
        text-decoration: none;
        transition: color 0.25s;
    }

    .ibw-contact-text a:hover {
        color: #D4AF37;
    }

    .ibw-bottom {
        padding: 1.4rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .ibw-copyright {
        font-size: 0.78rem;
        color: #5e5950;
        font-weight: 300;
        margin: 0;
    }

    .ibw-bottom-links {
        display: flex;
        gap: 1.75rem;
    }

    .ibw-bottom-links a {
        font-size: 0.78rem;
        color: #5e5950;
        text-decoration: none;
        transition: color 0.25s;
        font-weight: 300;
    }

    .ibw-bottom-links a:hover {
        color: #D4AF37;
    }

    @media (max-width: 900px) {
        .ibw-grid {
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        .ibw-grid > div:first-child {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 600px) {
        .ibw-footer {
            padding: 2.5rem 0 0;
        }
        .ibw-container {
            padding: 0 1.25rem;
        }
        .ibw-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .ibw-grid > div:first-child {
            grid-column: auto;
        }
        .ibw-bottom {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.6rem;
        }
        .ibw-bottom-links {
            gap: 1.25rem;
            flex-wrap: wrap;
        }
    }
</style>

<footer class="ibw-footer" id="contact">
    <div class="ibw-container">
        <div class="ibw-grid">

            <!-- Brand -->
            <div>
                <span class="ibw-logo">Intimate Bali Wedding</span>
                <p class="ibw-tagline">
                    Making dreams come true, one celebration at a time. We specialize in creating
                    unforgettable moments for your special day across Bali.
                </p>
                <div class="ibw-divider"></div>
                <div class="ibw-socials">
                    <a href="https://youtube.com/@ilovebaliproduction9722" target="_blank" rel="noopener" aria-label="YouTube">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.8 8s-.2-1.4-.8-2c-.8-.8-1.7-.8-2.1-.9C16.1 5 12 5 12 5s-4.1 0-6.9.1c-.4.1-1.3.1-2.1.9-.6.6-.8 2-.8 2S2 9.6 2 11.2v1.6C2 14.4 2.2 16 2.2 16s.2 1.4.8 2c.8.8 1.9.8 2.4.9 1.8.2 6.6.1 6.6.1s4.1 0 6.9-.1c.4-.1 1.3-.1 2.1-.9.6-.6.8-2 .8-2s.2-1.6.2-3.2v-1.6C22 9.6 21.8 8 21.8 8zM10 14.7V9.3l5.2 2.7L10 14.7z"/>
                        </svg>
                    </a>
                    <a href="https://instagram.com/intimatebaliwedding" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 01-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 017.8 2m-.2 2A3.6 3.6 0 004 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 003.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 011.25 1.25A1.25 1.25 0 0117.25 8 1.25 1.25 0 0116 6.75a1.25 1.25 0 011.25-1.25M12 7a5 5 0 015 5 5 5 0 01-5 5 5 5 0 01-5-5 5 5 0 015-5m0 2a3 3 0 00-3 3 3 3 0 003 3 3 3 0 003-3 3 3 0 00-3-3z"/>
                        </svg>
                    </a>
                    <a href="https://web.facebook.com/ilovebaliphotography?locale=id_ID" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="ibw-col-title">Quick Menu</h3>
                <ul class="ibw-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('packages.public') }}">Wedding Packages</a></li>
                    <li><a href="{{ route('gallery.public') }}">Gallery &amp; Testimonials</a></li>
                    <li><a href="{{ route('blogs.public') }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="ibw-col-title">Contact Us</h3>
                <ul class="ibw-contact-list">
                    <li class="ibw-contact-item">
                        <div class="ibw-contact-text">
                            <a href="mailto:intimatebaliwedding@gmail.com">intimatebaliwedding@gmail.com</a>
                        </div>
                    </li>
                    <li class="ibw-contact-item">
                        <div class="ibw-contact-text">
                            <a href="tel:+6287861775445">+62 878-6177-5445</a>
                        </div>
                    </li>
                    <li class="ibw-contact-item">
                        <div class="ibw-contact-text">
                            Jl. Anggrek No.27, Denpasar, Bali
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="ibw-bottom">
            <p class="ibw-copyright">&copy; {{ date('Y') }} SKYNUSA TECH. All rights reserved.</p>
            <div class="ibw-bottom-links">
                <a href="#privacy">Privacy Policy</a>
                <a href="#terms">Terms of Service</a>
                <a href="#sitemap">Sitemap</a>
            </div>
        </div>

    </div>
</footer>