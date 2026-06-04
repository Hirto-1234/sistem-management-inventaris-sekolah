<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\InventarisRuangan;
use App\Models\Ruangan;
use App\Services\SyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventariBarangRuanganController extends Controller
{
    //
    public function index(Request $request) {
        try {
            $querry = InventarisRuangan::query();
            if ($request->filled('nama_ruangan')) {
                $idRuangan = $request->input('nama_ruangan');
                $querry->where('id_ruangan', $idRuangan);
            }
            $data = $querry->with(['ruangan', 'barang', 'pengguna'])
                    ->get()
                    ->groupBy('id_ruangan');
            $ruangan = Ruangan::whereRaw('LOWER(nama_ruangan) != ?', ['tu'])->get();
            $existedRuangan = InventarisRuangan::select('id_ruangan')->distinct()->pluck('id_ruangan')->toArray();
            $ruangan = $ruangan->filter(function ($item) use ($existedRuangan) {
                return !in_array($item->id_ruangan, $existedRuangan);
            });
            $barang = Barang::all();
            return view('management.inventaris-barang-ruangan', compact('data', 'ruangan', 'barang'));
        } catch (\Exception $e) {
            return redirect()->route('management.inventaris-barang-ruangan')->with('error_index', 'Terjadi kesalahan saat memuat data inventaris barang ruangan.');
        }
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'id_ruangan' => 'required|exists:tabel_ruangan,id_ruangan',
                'barang' => 'required|array',
                'barang.*' => 'exists:tabel_barang,id_barang',
                'jumlah' => 'required|array'
            ]);
            foreach ($request->barang as $index => $idBarang) {
                $jumlahDipakai = $request->jumlah[$index];
                $barang = Barang::find($idBarang);
                if ($barang->stok_barang < $jumlahDipakai) {
                    return redirect()->route('management.inventaris-barang-ruangan.index')->with('swal_error', 'Jumlah melebihi stok barang');
                }
                
                $existing = InventarisRuangan::where('id_ruangan', $request->id_ruangan)
                    ->where('id_barang', $idBarang)
                    ->first();
                if ($existing) {
                    $existing->increment('jumlah_barang', $jumlahDipakai);
                } else {
                    InventarisRuangan::create([
                        'id_ruangan' => $request->id_ruangan,
                        'id_barang' => $idBarang,
                        'id_pengguna' => auth()->user()->id_pengguna,
                        'jumlah_barang' => $jumlahDipakai,
                    ]); 
                }
                $barang->decrement('stok_barang', $jumlahDipakai);
                SyncService::syncTU();
            }
            return redirect()->route('management.inventaris-barang-ruangan.index')->with('swal_success', 'Inventaris barang ruangan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('management.inventaris-barang-ruangan.index')->with('swal_error', 'Terjadi kesalahan saat menambahkan inventaris barang ruangan : ' . $e->getMessage());
        }
    }
    public function detail($id) {
        try {
            $data = InventarisRuangan::where('id_ruangan', $id)
                ->with(['barang', 'ruangan', 'pengguna'])
                ->orderBy('updated_at', 'desc')
                ->get();
            $ruangan = $data[0]->ruangan;
            $updatedBy = $data[0]->pengguna;
            $updatedAt = $data[0]->updated_at;
            $barangList = $data->map(function ($item) {
                return [
                    'id_barang' => $item->id_barang,
                    'nama_barang' => $item->barang->nama_barang,
                    'jumlah_barang' => $item->jumlah_barang,
                    'stok_barang' => $item->barang->stok_barang
                ];
            });
            $result = [
                'id_ruangan' => $ruangan->id_ruangan,
                'nama_ruangan' => $ruangan->nama_ruangan,
                'updated_by' => $updatedBy->nama_pengguna,
                'updated_at' => $updatedAt,
                'barangs' => $barangList
            ];
            return response()->json($result);
        } catch (\Exception $e) {

        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang' => 'required|array',
            'barang.*' => 'exists:tabel_barang,id_barang',
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            // Ambil kondisi existing
            $existing = InventarisRuangan::where('id_ruangan', $id)->get()
                ->keyBy('id_barang'); // supaya id barang menajadi array key

            $barangRequest = $request->barang;
            $jumlahRequest = $request->jumlah;

            foreach ($barangRequest as $idx => $idBarang) {
                $jumlahBaru = (int) $jumlahRequest[$idx];

                $barang = Barang::find($idBarang);

                if (isset($existing[$idBarang])) {
                    // --- CASE: UPDATE BARANG YANG SUDAH ADA ---
                    $oldJumlah = $existing[$idBarang]->jumlah_barang;

                    if ($jumlahBaru > $oldJumlah) {
                        // Tambah penggunaan → kurangi stok
                        $selisih = $jumlahBaru - $oldJumlah;
                        if ($barang->stok_barang < $selisih) {
                            return back()->with('error_store', 'Stok tidak cukup!');
                        }
                        $barang->decrement('stok_barang', $selisih);
                        SyncService::syncTU();
                    } elseif ($jumlahBaru < $oldJumlah) {
                        // Kurangi penggunaan → kembalikan stok global
                        $selisih = $oldJumlah - $jumlahBaru;
                        $barang->increment('stok_barang', $selisih);
                        SyncService::syncTU();
                    }

                    // Update data inventaris ruangan
                    $existing[$idBarang]->update([
                        'jumlah_barang' => $jumlahBaru,
                    ]);

                    // tandai sebagai processed
                    unset($existing[$idBarang]);

                } else {
                    // --- CASE: BARANG BARU DITAMBAHKAN ---
                    if ($barang->stok_barang < $jumlahBaru) {
                        return back()->with('error_store', 'Stok tidak cukup!');
                    }

                    InventarisRuangan::create([
                        'id_ruangan' => $id,
                        'id_barang' => $idBarang,
                        'id_pengguna' => auth()->user()->id_pengguna,
                        'jumlah_barang' => $jumlahBaru,
                    ]);

                    $barang->decrement('stok_barang', $jumlahBaru);
                    SyncService::syncTU();
                }
            }

            // --- CASE: BARANG YANG DIHAPUS DARI FORM ---
            foreach ($existing as $itemYangDihapus) {
                $barang = Barang::find($itemYangDihapus->id_barang);

                // kembalikan stok global
                $barang->increment('stok_barang', $itemYangDihapus->jumlah_barang);
                SyncService::syncTU();
                
                // hapus dari inventaris ruangan
                $itemYangDihapus->delete();
            }

            DB::commit();

            return redirect()->route('management.inventaris-barang-ruangan.index')->with('swal_success', 'Inventaris ruangan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->route('management.inventaris-barang-ruangan.index')->with('swal_error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id) {
        $inventaris = InventarisRuangan::where('id_ruangan', $id)->get();
        foreach ($inventaris as $item) {
            $barang = Barang::find($item->id_barang);
            $barang->update([
                'stok_barang' => $barang->stok_barang + $item->jumlah_barang
            ]);

            $item->delete();
        }

        return redirect()->route('management.inventaris-barang-ruangan.index')->with('swal_success', 'Data berhasil dihapus');
    }
}
