<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Services\SyncService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;


use function Illuminate\Support\now;

class BarangMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $jumlahData = 90;
        $idPengguna = [1, 2];
        $idBarang = [1, 2, 3];
        $tanggalSekarang = Carbon::now();
        $sumber = ['BOS', 'Komite', 'Sumbangan'];

        $data = [];

        for ($i = 0; $i <= $jumlahData; $i++) {
            $tanggalOffset = floor($i / 3);

            $data[] = [
                'id_pengguna' => $idPengguna[array_rand($idPengguna)],
                'id_barang' => $idBarang[array_rand($idBarang)],
                'tanggal_masuk' => $tanggalSekarang->copy()->addDays($tanggalOffset),
                'jumlah_masuk' => rand(5, 30),
                'sumber' => $sumber[array_rand($sumber)]
            ];
        }
        BarangMasuk::insert($data);

        foreach ($data as $barang) {
            Barang::where('id_barang', $barang['id_barang'])
                ->increment('stok_barang', $barang['jumlah_masuk']);

            Barang::where('id_barang', $barang['id_barang'])
                ->increment('jumlah_barang', $barang['jumlah_masuk']);
        }

        SyncService::syncTU();
        
    }
}
