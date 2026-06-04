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
        Schema::create('tabel_inventaris_ruangan', function (Blueprint $table) {
            $table->id('id_inventaris_ruangan');
            $table->unsignedBigInteger('id_ruangan');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_pengguna');
            $table->integer('jumlah_barang');
            $table->enum('kondisi_barang', ['baik', 'rusak ringan', 'rusak berat']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            //Relasi
            $table->foreign('id_ruangan')->references('id_ruangan')->on('tabel_ruangan')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('tabel_barang')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('tabel_pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tabel_inventaris_ruangan');
    }
};
