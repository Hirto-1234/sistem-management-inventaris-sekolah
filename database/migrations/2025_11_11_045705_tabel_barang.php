<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('tabel_barang', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('sku_barang', 50)->unique();
            $table->string('nama_barang', 100)->unique();
            $table->unsignedBigInteger('id_kategori');
            $table->integer('jumlah_barang')->default(0);
            $table->integer('stok_barang')->default(0);
            $table->text('deskripsi_barang')->nullable();
            $table->string('diupdate_oleh')->nullable();
            $table->string('foto_barang')->nullable();
            $table->string('kode_qr')->unique()->nullable();
            $table->timestamps();

            //Relasi
            $table->foreign('id_kategori')->references('id_kategori')->on('tabel_kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tabel_barang');
    }
};
