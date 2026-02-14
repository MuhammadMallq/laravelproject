<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    
    protected $fillable = [
        'nama_pembeli',
        'no_telepon',
        'detail_pesanan',
        'total_harga',
        'metode_pembayaran',
        'status_pesanan',
        'waktu_pesan',
    ];

    public $timestamps = false;

    protected $casts = [
        'waktu_pesan' => 'datetime',
    ];
}
