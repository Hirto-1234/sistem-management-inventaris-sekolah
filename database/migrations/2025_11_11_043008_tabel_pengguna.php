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
        Schema::create('tabel_pengguna',function (Blueprint $table) {
            $table->id('id_pengguna');
            $table->string('nama_lengkap', 100);    
            $table->string('nama_pengguna',100)->unique();
            $table->string('email', 100)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password_pengguna',100);
            $table->enum('role_pengguna', ['peminjam', 'petugas', 'admin'])->default('peminjam');
            $table->string('foto_pengguna')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tabel_pengguna');
    }
};
