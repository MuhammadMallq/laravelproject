<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    
    protected $fillable = [
        'nama_menu',
        'harga',
        'deskripsi',
        'gambar',
        'stok',
        'terjual',
    ];

    public $timestamps = false;
}
