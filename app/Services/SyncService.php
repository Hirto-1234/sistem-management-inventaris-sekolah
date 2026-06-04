<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\InventarisRuangan;
use App\Models\Ruangan;

class SyncService {

    public static function syncTU() {
        $ruanganTU = Ruangan::where('nama_ruangan', 'TU')->first();
        $semuaBarang = Barang::all();
        $management = [1, 2];

        foreach ($semuaBarang as $barang) {

            $inventarisTU = InventarisRuangan::where('id_ruangan', $ruanganTU->id_ruangan)
                ->where('id_barang', $barang->id_barang)
                ->first();
            
            if ($inventarisTU) {
                $inventarisTU->update([
                    'jumlah_barang' => $barang->stok_barang
                ]);
            } else {
                InventarisRuangan::create([
                    'id_ruangan' => $ruanganTU->id_ruangan,
                    'id_barang' => $barang->id_barang,
                    'id_pengguna' => auth()->user()->id_pengguna ?? $management[array_rand($management)],
                    'jumlah_barang' => $barang->stok_barang
                ]);
            }
        }
    }
}