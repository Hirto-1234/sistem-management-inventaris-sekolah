<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Services\SyncService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;


use function Illuminate\Support\now;

class BarangKeluarSeeder extends Seeder
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
        $kategoriKeluar = ['habis_terpakai', 'rusak_total', 'hilang', 'kadaluarsa', 'aus_tidak_layak','penghapusan_aset'];
        $data = [];

        for ($i = 0; $i<=$jumlahData; $i++) {
            $tanggalOffset = floor($i / 3);

            $data[] = [
                'id_pengguna' => $idPengguna[array_rand($idPengguna)],
                'id_inventaris_ruangan' => 1,
                'tanggal_keluar' => $tanggalSekarang->copy()->addDays($tanggalOffset),
                'jumlah_keluar' => rand(1, 4),
                'kategori_keluar' => $kategoriKeluar[array_rand($kategoriKeluar)]
            ];
        }

        BarangKeluar::insert($data);

        foreach ($data as $barangKeluar) {
            $idBarangKeluar = $idBarang[array_rand($idBarang)];
            Barang::where('id_barang', $idBarangKeluar)
                ->decrement('jumlah_barang', $barangKeluar['jumlah_keluar']);
        }

        SyncService::syncTU();
    }
}
