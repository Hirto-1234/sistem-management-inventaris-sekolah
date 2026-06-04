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
        Schema::create('tabel_detail_peminjaman', function (Blueprint $table) {
            $table->id('id_detail_peminjaman');
            $table->unsignedBigInteger('id_peminjaman');
            $table->unsignedBigInteger('id_barang');
            $table->integer('jumlah_barang');
            $table->timestamps();

            //Relasi
            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('tabel_peminjaman')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('tabel_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_detail_peminjaman');
    }
};
