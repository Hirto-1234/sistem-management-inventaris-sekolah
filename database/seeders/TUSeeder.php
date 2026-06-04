<?php

namespace Database\Seeders;

use App\Models\InventarisRuangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TUSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        InventarisRuangan::insert([
            [
                'id_ruangan' => 4,
                'id_barang' => 1,
                'id_pengguna' => 1,
                'jumlah_barang' => 30,
                'kondisi_barang' => 'baik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_ruangan' => 4,
                'id_barang' => 2,
                'id_pengguna' => 1,
                'jumlah_barang' => 30,
                'kondisi_barang' => 'baik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_ruangan' => 4,
                'id_barang' => 3,
                'id_pengguna' => 1,
                'jumlah_barang' => 30,
                'kondisi_barang' => 'baik',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
