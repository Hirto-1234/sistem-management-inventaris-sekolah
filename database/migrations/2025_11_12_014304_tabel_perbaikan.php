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
        Schema::create('tabel_perbaikan', function (Blueprint $table) {
            $table->id('id_perbaikan');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_inventaris_ruangan');
            $table->integer('jumlah_barang')->default(1);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('deskripsi_kerusakan');
            $table->text('deskripsi_perbaikan')->nullable();
            $table->json('foto_sebelum')->nullable();
            $table->json('foto_sesudah')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'diproses', 'berhasil', 'gagal', 'dibatalkan',])->default('pending');
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('tabel_pengguna')->onDelete('cascade');
            $table->foreign('id_inventaris_ruangan')->references('id_inventaris_ruangan')->on('tabel_inventaris_ruangan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_perbaikan');
    }
};
