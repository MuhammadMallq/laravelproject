@extends('layouts.app')

@section('title', 'Kontak | Indo Ice Tea')

@push('styles')
<style>
    .main-container {
        max-width: 700px;
        margin: 40px auto;
        padding: 0 20px;
    }

    #contact-card {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    #contact-card h2 {
        text-align: center;
        margin-bottom: 30px;
        color: var(--dark);
    }

    .contact-info-box {
        background: #fef9f7;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 25px;
        border-left: 4px solid var(--primary);
    }

    .contact-info-box p {
        margin: 10px 0;
        color: #555;
    }

    .map-container {
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .map-container iframe {
        border-radius: 15px;
    }

    #contactForm label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #444;
    }

    .input-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    #contactForm input,
    #contactForm select,
    #contactForm textarea {
        width: 100%;
        padding: 14px;
        border: 2px solid #eee;
        border-radius: 10px;
        font-family: inherit;
        font-size: 1rem;
        transition: 0.3s;
        margin-bottom: 15px;
    }

    #contactForm input:focus,
    #contactForm select:focus,
    #contactForm textarea:focus {
        outline: none;
        border-color: var(--primary);
    }

    #contactForm textarea {
        resize: vertical;
        min-height: 120px;
    }

    .btn-send {
        width: 100%;
        padding: 16px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-send:hover {
        background: #e66e52;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="main-container">
    <section id="contact-card">
        <h2>Hubungi Kami</h2>
        
        <div class="contact-info-box">
            <p><strong>📍 Alamat:</strong><br>Jl. Sariasih No.22, Sarijadi, Kota Bandung, Indonesia</p>
            <p><strong>⏰ Jam Operasional:</strong><br>Senin - Jumat, 09:00 - 18:00</p>
        </div>
        
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.0538645068133!2d107.57685607587421!3d-6.884144367358782!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6900f074a3f%3A0x60037a505e0325d!2sJl.%20Sariasih%20No.22%2C%20Sarijadi%2C%20Kec.%20Sukasari%2C%20Kota%20Bandung%2C%20Jawa%20Barat%2040151!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>

        <form id="contactForm">
            <label>Informasi Anda</label>
            <div class="input-group">
                <input type="text" id="name" placeholder="Nama Lengkap" required>
                <input type="email" id="email" placeholder="Email Anda" required>
            </div>

            <label>Topik & Pesan</label>
            <select id="topic" required>
                <option value="" disabled selected>Pilih Topik</option>
                <option value="Pertanyaan Umum">Pertanyaan Umum</option>
                <option value="Pesanan">Pesanan</option>
                <option value="Saran & Kritik">Saran & Kritik</option>
            </select>
            <textarea id="message" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
            
            <button type="submit" class="btn-send">Kirim Pesan via WhatsApp</button>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const topic = document.getElementById('topic').value;
    const message = document.getElementById('message').value;
    
    const nomorWA = "6282122339125";
    const text = `*PESAN DARI WEBSITE*%0A%0A*Nama:* ${name}%0A*Email:* ${email}%0A*Topik:* ${topic}%0A%0A*Pesan:*%0A${message}`;
    
    window.open(`https://wa.me/${nomorWA}?text=${text}`, '_blank');
});
</script>
@endpush
