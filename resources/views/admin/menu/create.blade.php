<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #ff7e5f; --secondary: #feb47b; --dark: #2d3436; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { color: var(--dark); text-align: center; margin-top: 0; font-size: 24px; font-weight: 600; }
        h2 span { color: var(--primary); }

        .form-group { margin-bottom: 18px; }

        label { display: block; margin-bottom: 8px; font-size: 13px; color: #555; font-weight: 600; text-transform: uppercase; }

        input, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            box-sizing: border-box;
            font-family: inherit;
            transition: 0.3s;
        }

        input:focus, textarea:focus { border-color: var(--primary); outline: none; background: #fff; }

        input[type="file"] { background: #f8f8f8; padding: 10px; font-size: 12px; cursor: pointer; }

        #preview {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-top: 10px;
            display: none;
            border: 2px solid #eee;
        }

        button {
            width: 100%;
            padding: 15px;
            background: var(--dark);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        button:hover { background: var(--primary); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(255,126,95,0.4); }

        .back { display: block; text-align: center; margin-top: 20px; color: #bbb; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .back:hover { color: var(--dark); }

        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .alert-error { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🍹 Tambah <span>Menu Baru</span></h2>

        @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nama Minuman</label>
                <input type="text" name="nama_menu" placeholder="Contoh: Thai Tea Original" required>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" placeholder="Contoh: 10000" min="0" required>
            </div>

            <div class="form-group">
                <label>Stok Awal</label>
                <input type="number" name="stok" placeholder="Contoh: 50" value="0" min="0">
            </div>

            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan keunikan rasa menu ini..."></textarea>
            </div>

            <div class="form-group">
                <label>Foto Produk</label>
                <input type="file" name="gambar" id="inputGambar" accept="image/*" required>
                <img id="preview" src="#" alt="Preview Gambar">
            </div>

            <button type="submit">Simpan ke Daftar Menu</button>
            <a href="{{ route('admin.dashboard') }}#stok" class="back">← Batal & Kembali</a>
        </form>
    </div>

    <script>
        const inputGambar = document.getElementById('inputGambar');
        const preview = document.getElementById('preview');
        inputGambar.onchange = evt => {
            const [file] = inputGambar.files;
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        }
    </script>
</body>
</html>
