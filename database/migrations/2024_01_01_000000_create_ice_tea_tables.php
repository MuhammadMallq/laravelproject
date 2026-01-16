<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
        });

        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->decimal('harga', 10, 2);
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->integer('stok')->default(0);
            $table->integer('terjual')->default(0);
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->text('detail_pesanan');
            $table->decimal('total_harga', 10, 2);
            $table->string('metode_pembayaran');
            $table->string('status_pesanan');
            $table->timestamp('waktu_pesan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('admin');
    }
};
