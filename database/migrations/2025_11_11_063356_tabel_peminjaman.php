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
        Schema::create('tabel_peminjaman', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_ruangan');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->enum('status_peminjaman', ['pending', 'aktif', 'selesai', 'ditolak'])
                  ->default('pending');
            $table->text('keterangan')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('id_pengguna')
                  ->references('id_pengguna')
                  ->on('tabel_pengguna')
                  ->onDelete('cascade');

            $table->foreign('id_ruangan')
                  ->references('id_ruangan')
                  ->on('tabel_ruangan')
                  ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_peminjaman');
    }
};
