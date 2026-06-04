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
        Schema::create('tabel_barang_keluar', function (Blueprint $table) {
            $table->id('id_barang_keluar');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_inventaris_ruangan')->nullable();
            $table->date('tanggal_keluar');
            $table->integer('jumlah_keluar')->default(1);
            $table->enum('kategori_keluar', ['habis_terpakai', 'rusak_total', 'hilang', 'kadaluarsa', 'aus_tidak_layak','penghapusan_aset'])->default('habis_terpakai');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('tabel_barang_keluar');
    }
};