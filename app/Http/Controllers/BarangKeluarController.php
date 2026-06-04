<?php
namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\InventarisRuangan;
use App\Services\SyncService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = BarangKeluar::with(['inventarisRuangan.barang', 'inventarisRuangan.ruangan', 'pengguna']);
            $query = $this->filterIndex($query, $request); // FILTER SEARCH & KATEGORI

            $daftarBarangKeluar = $query->latest()->paginate(10);

            // MENGAMBIL BARANG DARI INVENTARIS YANG MASIH ADA STOKNYA
            $daftarInventaris = InventarisRuangan::with(['ruangan', 'barang'])
                ->whereHas('barang')
                ->where('jumlah_barang', '>', 0)
                ->get()
                ->sortBy(fn($item) => $item->ruangan->nama_ruangan ?? '');

            return view('management.barang-keluar', compact('daftarBarangKeluar', 'daftarInventaris'));
        } catch (\Exception $e) {
            return back()->with('swal_error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function filterIndex($query, Request $request)
    {
        if ($request->filled('search')) {
            $query->whereHas('inventarisRuangan.barang', function ($q) use ($request) {
                $q->where('nama_barang', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori_keluar')) {
            $query->where('kategori_keluar', $request->kategori_keluar);
        }
        return $query;
    }

    public function store(Request $request)
    {
        $data = $this->validasiInput($request);

        try {
            DB::transaction(function () use ($data) {

                $inventaris = InventarisRuangan::with(['ruangan', 'barang'])
                    ->findOrFail($data['id_inventaris_ruangan']);

                $this->kurangiStok($inventaris, $data['jumlah_barang']); // MENGURANGI STOK SESUAI RUANGAN

                BarangKeluar::create([
                    'id_pengguna'           => auth()->user()->id_pengguna,
                    'id_inventaris_ruangan' => $data['id_inventaris_ruangan'],
                    'jumlah_keluar'         => $data['jumlah_barang'],
                    'tanggal_keluar'        => now(),
                    'kategori_keluar'       => $data['kategori_keluar'],
                    'keterangan'            => $data['keterangan'],
                ]);
            });

            return redirect()->route('management.barang-keluar.index')->with('swal_success', 'Data barang keluar berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('swal_error', $e->getMessage());
        }
    }

    private function validasiInput(Request $request)
    {
        return $request->validate([
            'id_inventaris_ruangan' => 'required|exists:tabel_inventaris_ruangan,id_inventaris_ruangan',
            'jumlah_barang'         => 'required|integer|min:1',
            'kategori_keluar'       => 'required|in:habis_terpakai,rusak_total,hilang,kadaluarsa,aus_tidak_layak,penghapusan_aset',
            'keterangan'            => 'nullable|string|max:1000',
        ]);
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                BarangKeluar::findOrFail($id)->delete();
            });

            return redirect()->route('management.barang-keluar.index')->with('swal_success', 'Data barang keluar berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('swal_error', $e->getMessage());
        }
    }

    /* =======================================================
       KURANGI STOK BARANG
       =======================================================
       PENJELASAN:
       - JIKA RUANGAN = "TU" → KURANGI STOK BARANG PUSAT
       - JIKA RUANGAN BIASA → KURANGI JUMLAH INVENTARIS RUANGAN
       =======================================================*/
    private function kurangiStok($inventaris, $jumlah)
    {
        $namaRuangan = strtoupper($inventaris->ruangan->nama_ruangan);

        if ($namaRuangan === 'TU') {
            // KURANGI STOK MASTER DI BARANG UTAMA
            $barang = $inventaris->barang;
            if ($barang->stok_barang < $jumlah) {
                throw new \Exception("STOK TIDAK CUKUP. SISA: {$barang->stok_barang}");
            }
            $barang->decrement('stok_barang', $jumlah);
            SyncService::syncTU(); // SINKRONISASI STOK TU

        } else {
            // KURANGI STOK DI INVENTARIS RUANGAN
            if ($inventaris->jumlah_barang < $jumlah) {
                throw new \Exception(
                    "STOK DI RUANGAN {$inventaris->ruangan->nama_ruangan} TIDAK CUKUP. SISA: {$inventaris->jumlah_barang}"
                );
            }
            $inventaris->decrement('jumlah_barang', $jumlah);
        }
    }

    /* =======================================================
       PRINT PDF DENGAN FILTER
       =======================================================*/
    public function print(Request $request)
    {
        try {
            $query = BarangKeluar::with(['inventarisRuangan.barang', 'inventarisRuangan.ruangan', 'pengguna']);
            $query = $this->filterLaporan($query, $request);  // GUNAKAN FILTER YANG BENAR
            $data  = $query->latest('tanggal_keluar')->get(); // DATA FULL TANPA PAGINATE

            $html = view('management.print.barang-keluar', ['barangKeluar' => $data])->render();

            $namaFile = 'Barang-Keluar-' . date('d-m-Y') . '.pdf';
            $path     = storage_path('app/temp/' . $namaFile);

            if (! is_dir(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            // BROWSER SHOT MEMBUAT PDF
            Browsershot::html($html)->format('A4')->showBackground()
                ->margins(5, 5, 5, 5)->waitUntilNetworkIdle()->save($path);

            return response()->file($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('swal_error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    /* =======================================================
       FILTER UNTUK LAPORAN DAN PRINT
       =======================================================*/
    private function filterLaporan($query, Request $request)
    {
        // FILTER SEARCH
        if ($request->filled('search')) {
            $query->whereHas('inventarisRuangan.barang', function ($q) use ($request) {
                $q->where('nama_barang', 'LIKE', '%' . $request->search . '%');
            });
        }

        $tipe = $request->tipe;

        // FILTER HARIAN
        if ($tipe === 'harian' && $request->filled('harian')) {
            $query->whereDate('tanggal_keluar', $request->harian);
        }
        // FILTER MINGGUAN
        elseif ($tipe === 'mingguan' && $request->filled('mingguan')) {
            [$tahun, $minggu] = explode('-W', $request->mingguan);
            $start            = Carbon::now()->setISODate($tahun, $minggu)->startOfWeek();
            $end              = Carbon::now()->setISODate($tahun, $minggu)->endOfWeek();
            $query->whereBetween('tanggal_keluar', [$start, $end]);
        }
        // FILTER BULANAN
        elseif ($tipe === 'bulanan' && $request->filled('bulanan')) {
            [$tahun, $bulan] = explode('-', $request->bulanan);
            $query->whereYear('tanggal_keluar', $tahun)->whereMonth('tanggal_keluar', $bulan);
        }
        // FILTER RENTANG TANGGAL
        elseif ($tipe === 'rentang' && $request->filled('awal') && $request->filled('akhir')) {
            $query->whereBetween('tanggal_keluar', [$request->awal, $request->akhir]);
        }

        // FILTER KATEGORI KELUAR
        if ($request->filled('kategori_keluar')) {
            $query->where('kategori_keluar', $request->kategori_keluar);
        }

        return $query;
    }

    /* =======================================================
       LAPORAN + FILTER TANGGAL
       =======================================================*/
    public function laporan(Request $request)
    {
        try {
            $query = BarangKeluar::with(['inventarisRuangan.barang', 'inventarisRuangan.ruangan', 'pengguna']);
            $query = $this->filterLaporan($query, $request); // GUNAKAN FILTER YANG SAMA

            $barangs = $query->latest('tanggal_keluar')->paginate(10);
            return view('management.laporan-barang-keluar', compact('barangs'));
        } catch (\Exception $e) {
            return back()->with('swal_error', 'Gagal memuat laporan: ' . $e->getMessage());
        }
    }
}