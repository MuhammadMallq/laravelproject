@extends('layouts.admin')

@section('title', 'Kelola Stok | Indo Ice Tea')

@push('styles')
<style>
    .table-container {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    table { width: 100%; border-collapse: collapse; }

    th {
        text-align: left;
        padding: 15px;
        color: #888;
        border-bottom: 2px solid #eee;
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #f9f9f9;
        vertical-align: middle;
    }

    .table-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .input-stok {
        width: 70px;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #ddd;
        text-align: center;
        font-weight: bold;
        font-family: inherit;
    }

    .btn-icon {
        border: none;
        background: transparent;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-save { color: #27ae60; background: #eafaf1; }
    .btn-save:hover { background: #27ae60; color: white; transform: scale(1.1); }

    .btn-delete { color: #e74c3c; background: #fdeded; margin-left: 5px; }
    .btn-delete:hover { background: #e74c3c; color: white; transform: scale(1.1); }
</style>
@endpush

@section('content')
<h1>📦 Manajemen Stok Barang</h1>
<p style="color:#888; margin-bottom:30px;">Atur stok dan hapus menu yang tidak tersedia.</p>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th width="100">Gambar</th>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Sisa Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
            @php $stokAlert = $menu->stok < 10 ? 'color:#e74c3c; border-color:#e74c3c; background:#fff5f5;' : ''; @endphp
            <tr>
                <td><img src="{{ asset('img/' . $menu->gambar) }}" class="table-img" alt="Menu"></td>
                <td style="font-weight:600;">{{ $menu->nama_menu }}</td>
                <td>Rp {{ number_format($menu->harga) }}</td>
                <td>
                    <input type="number" name="stok" form="update-form-{{ $menu->id }}" class="input-stok" value="{{ $menu->stok }}" style="{{ $stokAlert }}">
                </td>
                <td>
                    <form id="update-form-{{ $menu->id }}" method="POST" action="{{ route('admin.stok.update') }}" style="display:inline-block">
                        @csrf
                        <input type="hidden" name="id_menu" value="{{ $menu->id }}">
                        <button type="submit" class="btn-icon btn-save" title="Simpan Perubahan Stok">
                            <i class="fas fa-save"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.stok.destroy', $menu->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus menu ini selamanya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="Hapus Menu">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
