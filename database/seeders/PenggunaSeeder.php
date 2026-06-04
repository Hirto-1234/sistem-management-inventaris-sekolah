<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Pengguna::insert([
            [
                'nama_pengguna'     => 'Petugas',
                'nama_lengkap'      => 'Petugas123',
                'password_pengguna' => Hash::make('12345678'),
                'role_pengguna'     => 'petugas',
                'email'             => null,
                'email_verified_at' => now(),
            ],
            [
                'nama_pengguna'     => 'Admin',
                'nama_lengkap'      => 'Admin123',
                'password_pengguna' => Hash::make('12345678'),
                'role_pengguna'     => 'admin',
                'email'             => null,
                'email_verified_at' => now(),
            ],
            [
                'nama_pengguna'     => 'Peminjam',
                'nama_lengkap'      => 'Peminjam123',
                'password_pengguna' => Hash::make('12345678'),
                'role_pengguna'     => 'peminjam',
                'email'             => null,
                'email_verified_at' => now(),
            ],
        ]);
    }
}
