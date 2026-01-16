@extends('layouts.app')

@section('title', 'Home | Indo Ice Tea')

@push('styles')
<style>
    /* Hero Section */
    .hero {
        position: relative;
        background: linear-gradient(135deg, rgba(255,126,95,0.9), rgba(254,180,123,0.8));
        color: white;
        text-align: center;
        padding: 100px 20px;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
        overflow: hidden;
    }
    
    .hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=1600&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        opacity: 0.2;
        z-index: -1;
    }

    .hero-content {
        max-width: 800px;
        margin: auto;
        position: relative;
        z-index: 1;
        animation: fadeIn 1s ease-out;
    }

    .hero h1 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.2;
        text-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .hero p {
        font-size: 1.3rem;
        margin-bottom: 40px;
        font-weight: 500;
        opacity: 0.95;
    }

    .btn-cta {
        display: inline-block;
        padding: 18px 45px;
        background: white;
        color: var(--primary);
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .btn-cta:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        background: var(--dark);
        color: white;
    }

    /* Features Section */
    .features {
        padding: 80px 20px;
        text-align: center;
        max-width: 1200px;
        margin: auto;
    }

    .section-title {
        font-size: 2.2rem;
        color: var(--dark);
        margin-bottom: 15px;
        font-weight: 700;
    }

    .section-subtitle {
        color: var(--gray);
        margin-bottom: 60px;
        font-size: 1.1rem;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .feature-card {
        background: white;
        padding: 40px 30px;
        border-radius: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        transition: 0.3s;
        border: 1px solid #f0f0f0;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        border-color: var(--primary);
    }

    .feature-icon {
        font-size: 3rem;
        margin-bottom: 25px;
        background: #fff5f5;
        width: 80px;
        height: 80px;
        line-height: 80px;
        border-radius: 50%;
        margin-left: auto;
        margin-right: auto;
    }

    .feature-card h3 {
        font-size: 1.4rem;
        margin-bottom: 15px;
        color: var(--dark);
    }

    .feature-card p {
        color: var(--gray);
        line-height: 1.6;
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 768px) {
        .hero h1 { font-size: 2.5rem; }
        .hero p { font-size: 1rem; }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Segarnya Hari dengan<br>Indo Ice Tea</h1>
        <p>Nikmati sensasi teh asli Indonesia dengan perpaduan rasa modern yang bikin harimu makin semangat!</p>
        <a href="{{ route('menu.index') }}" class="btn-cta">Pesan Sekarang ➔</a>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <h2 class="section-title">Kenapa Pilih Kami?</h2>
    <p class="section-subtitle">Kami menyajikan lebih dari sekadar teh biasa.</p>
    
    <div class="feature-grid">
        <div class="feature-card">
            <div class="feature-icon">🌿</div>
            <h3>Bahan Berkualitas</h3>
            <p>Menggunakan daun teh pilihan dari perkebunan terbaik di Indonesia untuk rasa yang otentik.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🧊</div>
            <h3>Selalu Segar</h3>
            <p>Dibuat langsung saat dipesan (made to order) sehingga kesegarannya selalu terjamin.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">💰</div>
            <h3>Harga Bersahabat</h3>
            <p>Nikmati minuman premium dengan harga yang tetap ramah di kantong pelajar dan mahasiswa.</p>
        </div>
    </div>
</section>
@endsection
