@extends('layouts.app')

@section('title', 'Menu | Indo Ice Tea')

@push('styles')
<style>
    :root {
        --primary: #ff7e5f;
        --primary-dark: #e66e52;
        --secondary: #feb47b;
        --dark: #2d3436;
        --gray: #636e72;
        --light-bg: #f4f4f9;
    }

    body { background-color: var(--light-bg); }

    /* Admin FAB Group */
    .admin-fab-group {
        position: fixed;
        bottom: 30px;
        left: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        z-index: 1001;
    }

    .fab-admin {
        background: var(--dark);
        color: white;
        padding: 14px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fab-admin:hover {
        transform: scale(1.05) translateY(-5px);
        box-shadow: 0 12px 30px rgba(255,126,95,0.3);
    }

    /* Menu Section */
    #menu { padding: 60px 8%; max-width: 1300px; margin: auto; }
    .menu-title { text-align: center; margin-bottom: 50px; }
    .menu-title h2 { font-weight: 700; font-size: 2.5rem; margin-bottom: 10px; color: var(--dark); }
    .menu-title p { color: var(--gray); font-size: 1rem; }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 35px;
    }

    .menu-item {
        background: white;
        border-radius: 30px;
        overflow: hidden;
        border: 2px solid #e0e0e0;
        transition: all 0.4s ease;
        position: relative;
    }

    .menu-item:hover {
        transform: translateY(-10px);
        border-color: var(--primary);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .menu-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: 0.5s;
    }

    .menu-item:hover .menu-img { transform: scale(1.05); }

    .item-details { padding: 25px 25px 15px; }
    .item-details h3 { margin: 0; font-size: 1.35rem; font-weight: 700; color: #333; }
    .item-details p { font-size: 0.9rem; color: var(--gray); margin: 12px 0; line-height: 1.6; height: 45px; overflow: hidden; }

    .price-and-control {
        padding: 0 25px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price { font-weight: 700; color: var(--primary); font-size: 1.4rem; }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8f8f8;
        padding: 8px 18px;
        border-radius: 50px;
        border: 1px solid #eee;
    }

    .quantity-control button {
        background: white;
        border: 1px solid #ddd;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        font-weight: bold;
        color: var(--primary);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .quantity-control button:hover:not(:disabled) {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .quantity-control button:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        box-shadow: none;
    }

    .quantity-input {
        font-weight: 700;
        width: 45px;
        text-align: center;
        color: var(--dark);
        font-size: 1.1rem;
        border: none;
        background: transparent;
        outline: none;
        -moz-appearance: textfield;
    }
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Out of Stock */
    .menu-item.out-of-stock {
        opacity: 0.6;
        pointer-events: none;
        position: relative;
    }
    .menu-item.out-of-stock .quantity-control { display: none; }
    .out-of-stock-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #e74c3c;
        color: white;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        z-index: 10;
        letter-spacing: 0.5px;
    }
    .stock-info {
        font-size: 0.75rem;
        text-align: right;
        margin-top: 4px;
        font-weight: 600;
    }
    .stock-available { color: #27ae60; }
    .stock-low { color: #e67e22; }
    .stock-empty { color: #e74c3c; }

    .admin-controls {
        padding: 15px;
        background: #fafafa;
        display: flex;
        justify-content: space-around;
        border-top: 1px solid #eee;
    }

    .btn-edit { color: #3498db; text-decoration: none; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; }
    .btn-hapus { color: #e74c3c; text-decoration: none; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; }

    /* Floating Cart */
    #floating-cart {
        position: fixed;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--dark);
        color: white;
        padding: 18px 35px;
        border-radius: 100px;
        display: none;
        align-items: center;
        gap: 25px;
        z-index: 1000;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 300px;
        justify-content: space-between;
    }

    #floating-cart:hover { transform: translateX(-50%) translateY(-5px); background: #000; }
    #cart-item-count { background: var(--primary); color: white; padding: 4px 12px; border-radius: 50px; font-weight: bold; font-size: 0.8rem; }

    /* Popup */
    #checkout-popup .popup-content {
        background: #fff;
        width: 90%;
        max-width: 420px;
        text-align: center;
        padding: 35px;
        border-radius: 30px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        animation: scaleIn 0.3s ease-out;
    }

    @keyframes scaleIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .popup-title { color: var(--primary); font-weight: 700; font-size: 1.6rem; margin-bottom: 25px; }
    #popup-items { background: #fdfaf9; border: 1px solid #f0e6e4; border-radius: 20px; padding: 15px 20px; margin-bottom: 20px; max-height: 200px; overflow-y: auto; }
    .total-price-area { font-size: 1.4rem; font-weight: 700; margin: 20px 0; }
    .custom-select { width: 100%; padding: 14px; border-radius: 15px; border: 1px solid #ddd; margin-bottom: 25px; font-family: inherit; }
    .complete-btn { background: var(--primary); color: white; border: none; padding: 16px; border-radius: 15px; font-weight: 700; width: 100%; cursor: pointer; font-size: 1rem; }
    .complete-btn:hover { background: var(--primary-dark); }
    .close-popup-btn { background: none; border: none; color: #bbb; margin-top: 15px; cursor: pointer; }
</style>
@endpush

@section('content')


<section id="menu">
    <div class="menu-title">
        <h2>Daftar Menu Favorit</h2>
        <p>Pilih minuman segar favoritmu untuk menemani hari-harimu!</p>
    </div>

    <div class="menu-grid">
        @foreach($menus as $menu)
        <div class="menu-item {{ $menu->stok <= 0 ? 'out-of-stock' : '' }}" data-name="{{ $menu->nama_menu }}" data-price="{{ $menu->harga }}" data-stok="{{ $menu->stok }}">
            @if($menu->stok <= 0)
                <span class="out-of-stock-badge">HABIS</span>
            @endif
            <div style="overflow:hidden">
                <img src="{{ asset('img/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" class="menu-img">
            </div>
            <div class="item-details">
                <h3>{{ $menu->nama_menu }}</h3>
                <p>{{ $menu->deskripsi }}</p>
            </div>
            <div class="price-and-control">
                <div>
                    <span class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                    @if($menu->stok > 5)
                        <div class="stock-info stock-available">✔ Tersedia</div>
                    @elseif($menu->stok > 0)
                        <div class="stock-info stock-low">⚠ Hampir Habis!</div>
                    @else
                        <div class="stock-info stock-empty">✖ Habis</div>
                    @endif
                </div>
                
                <div class="quantity-control">
                    <button class="btn-minus" disabled>−</button>
                    <input type="number" class="quantity-input" value="0" min="0" max="{{ $menu->stok }}">
                    <button class="btn-plus" {{ $menu->stok <= 0 ? 'disabled' : '' }}>+</button>
                </div>
            </div>

            @if($isAdmin)
            <div class="admin-controls">
                <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn-edit">✎ EDIT</a>
                <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus menu ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-hapus" style="background:none;border:none;cursor:pointer;">🗑 HAPUS</button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</section>

<!-- Checkout Popup -->
<div id="checkout-popup" class="popup" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(8px); justify-content:center; align-items:center; z-index:9999; padding:20px;">
    <div class="popup-content">
        <h3 class="popup-title">Konfirmasi Pesanan</h3>
        <div id="popup-items"></div>
        
        <!-- Input Nama & No Telp -->
        <div style="margin-bottom:15px; text-align:left;">
            <label for="buyer-name" style="display:block;margin-bottom:5px;font-weight:bold;">Nama Pembeli:</label>
            <input type="text" id="buyer-name" placeholder="Masukkan Nama Anda" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:5px;margin-bottom:10px;">
            
            <label for="buyer-phone" style="display:block;margin-bottom:5px;font-weight:bold;">Nomor Telepon:</label>
            <input type="text" id="buyer-phone" placeholder="Contoh: 08123456789" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:5px;">
        </div>

        <div class="total-price-area">
            Total Akhir: <span id="popup-total" style="color:var(--primary);">Rp 0</span>
        </div>
        <div style="text-align:left;">
            <label style="font-size:0.8rem;font-weight:600;color:#888;">METODE PEMBAYARAN</label>
            <select id="payment-method" onchange="toggleQrisDisplay()" class="custom-select">
                <option value="cod">💵 Bayar di Tempat (COD)</option>
                <option value="qris">📱 QRIS / E-Wallet</option>
            </select>
        </div>
        <div id="qris-area" style="display:none; margin-bottom:25px;">
            <img src="{{ asset('img/qris.jpg') }}" style="width:180px; border-radius:10px;">
        </div>
        <button onclick="completeCheckout()" class="complete-btn">Pesan Sekarang</button>
        <button onclick="closeCheckoutPopup()" class="close-popup-btn">Tutup & Edit</button>
    </div>
</div>

<div id="floating-cart" onclick="showCheckoutPopup()">
    <div style="display:flex; align-items:center; gap:15px;">
        <span id="cart-item-count">0</span>
        <span style="font-size:1.3rem;">🛒</span>
        <span style="font-weight:500; font-size:0.95rem;">Keranjang Belanja</span>
    </div>
    <span id="floating-cart-total" style="font-weight:700; color:var(--primary);">Rp 0</span>
</div>
@endsection

@push('scripts')
<script>
function formatRupiah(angka) {
    const reverse = angka.toString().split('').reverse().join('');
    const ribuan = reverse.match(/\d{1,3}/g);
    return 'Rp' + ribuan.join('.').split('').reverse().join('');
}

function toggleQrisDisplay() {
    const method = document.getElementById('payment-method').value;
    const qrisArea = document.getElementById('qris-area');
    if (qrisArea) qrisArea.style.display = (method === 'qris') ? 'block' : 'none';
}

function updateOrderTotal() {
    let total = 0, totalItems = 0;
    document.querySelectorAll('.menu-item:not(.out-of-stock)').forEach(item => {
        const qty = parseInt(item.querySelector('.quantity-input')?.value || 0);
        const price = parseInt(item.dataset.price);
        total += qty * price;
        totalItems += qty;
    });
    
    document.getElementById('floating-cart-total').textContent = `Total: ${formatRupiah(total)}`;
    document.getElementById('cart-item-count').textContent = totalItems;
    document.getElementById('floating-cart').style.display = totalItems > 0 ? 'flex' : 'none';
    return total;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.menu-item:not(.out-of-stock)').forEach(item => {
        const btnMinus = item.querySelector('.btn-minus');
        const btnPlus = item.querySelector('.btn-plus');
        const qtyInput = item.querySelector('.quantity-input');
        const maxStok = parseInt(item.dataset.stok) || 0;

        function updateButtons() {
            const qty = parseInt(qtyInput.value) || 0;
            btnMinus.disabled = qty <= 0;
            btnPlus.disabled = qty >= maxStok;
        }

        if (btnPlus) {
            btnPlus.addEventListener('click', () => {
                let qty = parseInt(qtyInput.value) || 0;
                if (qty < maxStok) {
                    qtyInput.value = qty + 1;
                    updateButtons();
                    updateOrderTotal();
                }
            });
        }
        if (btnMinus) {
            btnMinus.addEventListener('click', () => {
                let qty = parseInt(qtyInput.value) || 0;
                if (qty > 0) {
                    qtyInput.value = qty - 1;
                    updateButtons();
                    updateOrderTotal();
                }
            });
        }

        // Allow user to type custom quantity
        if (qtyInput) {
            qtyInput.addEventListener('input', () => {
                let val = parseInt(qtyInput.value);
                if (isNaN(val) || val < 0) val = 0;
                if (val > maxStok) {
                    val = maxStok;
                    alert(`Stok tersedia hanya ${maxStok}`);
                }
                qtyInput.value = val;
                updateButtons();
                updateOrderTotal();
            });

            qtyInput.addEventListener('blur', () => {
                if (qtyInput.value === '' || isNaN(parseInt(qtyInput.value))) {
                    qtyInput.value = 0;
                    updateButtons();
                    updateOrderTotal();
                }
            });
        }

        updateButtons(); // Set initial button states
    });
});

function closeCheckoutPopup() {
    document.getElementById('checkout-popup').style.display = 'none';
}

function showCheckoutPopup() {
    const popup = document.getElementById('checkout-popup');
    const popupItems = document.getElementById('popup-items');
    const popupTotal = document.getElementById('popup-total');
    
    let total = 0, html = '';
    document.querySelectorAll('.menu-item:not(.out-of-stock)').forEach(item => {
        const qty = parseInt(item.querySelector('.quantity-input')?.value || 0);
        if (qty > 0) {
            const name = item.dataset.name;
            const price = parseInt(item.dataset.price);
            const subtotal = qty * price;
            html += `<div style="display:flex;justify-content:space-between;padding:5px 0;"><span>${qty}x ${name}</span><span>${formatRupiah(subtotal)}</span></div>`;
            total += subtotal;
        }
    });

    popupItems.innerHTML = html;
    popupTotal.textContent = formatRupiah(total);
    popup.style.display = 'flex';
}

function completeCheckout() {
    const buyerName = document.getElementById('buyer-name').value;
    const buyerPhone = document.getElementById('buyer-phone').value;
    const method = document.getElementById('payment-method').value;
    const totalRaw = document.getElementById('popup-total').textContent;
    const totalHarga = totalRaw.replace(/\D/g, '');
    
    // Validation
    if (!buyerName) {
        alert('Mohon isi Nama Pembeli');
        return;
    }
    if (!buyerPhone) {
        alert('Mohon isi Nomor Telepon');
        return;
    }
    
    let detailPesan = '', pesanWA = '*PESANAN BARU - INDO ICE TEA*%0A', dataStok = [];
    
    document.querySelectorAll('.menu-item:not(.out-of-stock)').forEach(item => {
        const qty = parseInt(item.querySelector('.quantity-input')?.value || 0);
        if (qty > 0) {
            const nama = item.dataset.name;
            detailPesan += `${nama} (${qty}x), `;
            pesanWA += `• ${nama} (${qty}x)%0A`;
            dataStok.push({ nama, jumlah: qty });
        }
    });

    const formData = new FormData();
    formData.append('nama_pembeli', buyerName);
    formData.append('no_telepon', buyerPhone);
    formData.append('detail', detailPesan);
    formData.append('total', totalHarga);
    formData.append('metode', method);
    formData.append('data_stok', JSON.stringify(dataStok));
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("pesanan.store") }}', { 
        method: 'POST', 
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(data => {
        const nomorWA = "6282122339125";
        window.open(`https://wa.me/${nomorWA}?text=${pesanWA}%0ATotal: ${totalRaw}%0AMetode: ${method}%0ANama: ${buyerName}%0ANo Telp: ${buyerPhone}`, '_blank');
        
        // Reset Inputs
        document.getElementById('buyer-name').value = '';
        document.getElementById('buyer-phone').value = '';
        document.querySelectorAll('.menu-item .quantity-input').forEach(q => {
            q.value = '0';
            // Re-enable plus, disable minus after reset
            const item = q.closest('.menu-item');
            if (item) {
                const maxStok = parseInt(item.dataset.stok) || 0;
                item.querySelector('.btn-minus').disabled = true;
                item.querySelector('.btn-plus').disabled = maxStok <= 0;
            }
        });
        
        updateOrderTotal();
        closeCheckoutPopup();
        alert('Pesanan berhasil dibuat!');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal membuat pesanan. Silakan coba lagi.');
    });
}
</script>
@endpush
