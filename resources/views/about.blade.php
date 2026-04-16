@extends('layouts.app')

@section('title', 'About Us - Intimate Bali Wedding')

@push('styles')
<style>
    .about-hero {
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                    url('/assets/background/bg_template.jpg');
        background-size: cover;
        background-position: center;
        height: 50vh; /* samakan dengan packages */
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        margin-top: -80px;
        padding-top: 80px;
    }

    .about-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase; /* tambah ini */
        letter-spacing: 2px; /* tambah ini */
    }

    .about-hero p {
        font-size: 1.1rem; /* samakan */
        font-weight: 300;
        letter-spacing: 1px; /* tambah ini */
    }

    .about-content {
        max-width: 900px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .about-content h2 {
        font-family: 'Playfair Display', serif;
        color: #D4AF37;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .about-content p {
        color: #666;
        line-height: 1.8;
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .about-content ul {
        list-style: none;
        padding: 0;
        margin: 2rem 0;
    }

    .about-content ul li {
        padding: 0.75rem 0;
        padding-left: 2rem;
        position: relative;
        color: #666;
        line-height: 1.8;
    }

    .about-content ul li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #D4AF37;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .stats-section {
        background: #f8f8f8;
        padding: 4rem 2rem;
        text-align: center;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 3rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .stat-item {
        padding: 2rem;
    }

    .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        color: #D4AF37;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #666;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 2.5rem;
        }

        .about-content {
            padding: 3rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="about-hero">
    <div>
        <h1>About Us</h1>
        <p>Creating Unforgettable Wedding Experiences in Bali</p>
    </div>
</section>

<!-- About Content -->
<section class="about-content">
    <h2>Intimate Bali Wedding</h2>
    
    <p>
        <strong>Intimate Bali Wedding</strong> is a specialized wedding service company dedicated to wedding affairs, meticulously, 
        and resourcefully wedding. We specialize in creating unforgettable moments for your special day in the most beautiful 
        locations across Bali. With years of experience and a deep passion for crafting extraordinary celebrations, we turn your 
        wedding dreams into reality.
    </p>

    <p>
        Our team of dedicated professionals works tirelessly to ensure every detail of your wedding is perfect. From the initial 
        planning stages to the final moments of your celebration, we are committed to providing exceptional service that exceeds 
        your expectations. We understand that your wedding day is one of the most important days of your life, and we treat it 
        with the care, attention, and creativity it deserves.
    </p>

    <h2 style="margin-top: 3rem;">Why Choose Us?</h2>
    
    <ul>
        <li><strong>Professional Excellence:</strong> We GUARANTEE THAT YOU ARE IN GOOD HANDS FROM BEGINNING TO END. Our experienced team has managed countless successful weddings across Bali.</li>
        <li><strong>Flexible Approach:</strong> Every wedding is unique. We customize our services to match your vision, preferences, and budget perfectly.</li>
        <li><strong>International Experience:</strong> We have worked on various projects around the world, bringing global standards to your Bali wedding.</li>
        <li><strong>Local Expertise:</strong> Deep knowledge of Bali's best venues, vendors, and hidden gems ensures an authentic and magical experience.</li>
        <li><strong>Full-Service Planning:</strong> From concept to execution, we handle every aspect of your wedding with meticulous attention to detail.</li>
        <li><strong>Stress-Free Experience:</strong> Let us handle the logistics while you enjoy your engagement and wedding celebration.</li>
    </ul>

    <p>
        Whether you envision a romantic beach ceremony, an elegant garden celebration, a traditional chapel wedding, or an 
        intimate villa gathering, we have the expertise and resources to bring your vision to life. Our extensive network of 
        trusted vendors, beautiful venues, and creative professionals ensures that your wedding will be everything you've 
        dreamed of and more.
    </p>

    <p style="text-align: center; margin-top: 3rem;">
        <strong style="color: #D4AF37; font-size: 1.2rem;">Let us handle it! Your dream wedding awaits.</strong>
    </p>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number">500+</div>
            <div class="stat-label">Happy Couples</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">15+</div>
            <div class="stat-label">Years Experience</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">50+</div>
            <div class="stat-label">Premium Venues</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">100%</div>
            <div class="stat-label">Satisfaction</div>
        </div>
    </div>
</section>
@endsection