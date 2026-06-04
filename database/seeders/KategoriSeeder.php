<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Kategori::insert([
            [
                'nama_kategori' => 'elektronik'
            ],
            [
                'nama_kategori' => 'kebersihan'
            ],
            [
                'nama_kategori' => 'olahraga'
            ]
        ]);
    }
}
