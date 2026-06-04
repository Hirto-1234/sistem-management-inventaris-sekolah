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
        Schema::create('tabel_barang_masuk', function (Blueprint $table) {
            $table->id('id_barang_masuk');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_barang');
            $table->date('tanggal_masuk');
            $table->integer('jumlah_masuk');
            $table->string('sumber', 100);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            //Relasi
            $table->foreign('id_pengguna')->references('id_pengguna')->on('tabel_pengguna')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('tabel_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tabel_barang_masuk');
    }
};
