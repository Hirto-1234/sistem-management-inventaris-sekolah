<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Barang::insert([
            [
                'sku_barang' => 'RKT-01',
                'nama_barang' => 'raket',
                'id_kategori' => 3,
                'diupdate_oleh' => 'Hirto',
                'kode_qr' => 'BRG-' . strtoupper(Str::random(8)),
            ],
            [
                'sku_barang' => 'SPU-01',
                'nama_barang' => 'sapu',
                'id_kategori' => 2,
                'diupdate_oleh' => 'Hirto',
                'kode_qr' => 'BRG-' . strtoupper(Str::random(8)),
            ],
            [
                'sku_barang' => 'PRK-01',
                'nama_barang' => 'proyektor',
                'id_kategori' => 1,
                'diupdate_oleh' => 'Hirto',
                'kode_qr' => 'BRG-' . strtoupper(Str::random(8)),
            ],
        ]);
    }
}
