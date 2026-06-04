<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Services\SyncService;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class BarangMasukController extends Controller
{
    //tampilin list barang masuk + relasi barang & pengguna
public function index(Request $request)
{
    try {

        $query = BarangMasuk::with(['barang','pengguna']);

        //kalo ada search, filter berdasarkan nama barang
        if($request->filled('search')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('nama_barang','like','%'.$request->search.'%');
            });
        }

        //kalo filter tanggal dipilih, ambil sesuai tanggal masuk
        if($request->filled('tanggal')) {
            $query->whereDate('tanggal_masuk',$request->tanggal);
        }

        //ambil data terbaru + paginate 10
        $barangs = $query->latest('tanggal_masuk')->paginate(10);

        //ambil semua data barang buat dropdown/modal
        $dataBarang = Barang::all();

        //lempar ke view
        return view('management.barang-masuk', compact('barangs','dataBarang'));

    } catch (\Exception $e) {

        //fallback kalo error pas load data
        return view('management.barang-masuk', [
            'barangs' => BarangMasuk::paginate(10),
            'dataBarang' => Barang::all()
        ])->with('swal_error','Terjadi kesalahan saat memuat data: '.$e->getMessage());
    }
}

//tambah stok barang (input barang masuk)
public function store(Request $request)
{
    $request->validate([
        'id_barang' => 'required|integer|exists:tabel_barang,id_barang',
        'jumlah_barang' => 'required|integer|min:1',
        'asal_dana' => 'nullable|string'
    ]);

    try {

        $barang = Barang::find($request->id_barang);

        //update stok & jumlah barang total
        $barang->jumlah_barang += $request->jumlah_barang;
        $barang->stok_barang += $request->jumlah_barang;
        $barang->diupdate_oleh = auth()->user()->nama_pengguna;
        $barang->save();

        //catat log barang masuk
        BarangMasuk::create([
            'id_pengguna' => auth()->user()->id_pengguna,
            'id_barang' => $request->id_barang,
            'tanggal_masuk' => now(),
            'jumlah_masuk' => $request->jumlah_barang,
            'sumber' => $request->asal_dana
        ]);

        //sinkronisasi ke TU
        SyncService::syncTU();

        return redirect()->route('management.barang-masuk.index')
            ->with('swal_success','Stok berhasil ditambahkan');

    } catch (\Exception $e) {

        return redirect()->route('management.barang-masuk.index')
            ->with('swal_error','Stok gagal ditambahkan: '.$e->getMessage());
    }
}


    // Hapus data barang masuk
    //hapus data barang masuk (stok ga diubah karena logic nya dimatiin)
public function destroy($id)
{
    $data = BarangMasuk::find($id);

    if ($data) {
        $barang = $data->barang;

        //kalo mau balikin stok
        // $barang->stok_barang -= $data->jumlah_masuk;
        // $barang->jumlah_barang -= $data->jumlah_masuk;
        // $barang->diupdate_oleh = auth()->user()->nama_pengguna;
        // $barang->save();

        //hapus data barang masuk
        $data->delete();

        return redirect()->route('management.barang-masuk.index')
            ->with('swal_success','Data berhasil dihapus');
    }

    return redirect()->route('management.barang-masuk.index')
        ->with('swal_error','Data tidak ditemukan');
}

//cetak pdf daftar barang masuk
public function print(Request $request)
{
    try {
        $query = BarangMasuk::with(['barang','pengguna']);

        /* ------------------------- FILTER SEARCH ------------------------- */
        if ($request->filled('search')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('nama_barang','LIKE','%'.$request->search.'%');
            });
        }

        /* ------------------------- FILTER JENIS LAPORAN ------------------------- */
        $tipe = $request->tipe;

        if ($tipe === 'harian' && $request->filled('harian')) {
            $query->whereDate('tanggal_masuk', $request->harian);
        }
        else if ($tipe === 'mingguan' && $request->filled('mingguan')) {
            [$tahun, $minggu] = explode('-W', $request->mingguan);

            $startWeek = \Carbon\Carbon::now()->setISODate($tahun, $minggu)->startOfWeek();
            $endWeek   = \Carbon\Carbon::now()->setISODate($tahun, $minggu)->endOfWeek();

            $query->whereBetween('tanggal_masuk', [$startWeek, $endWeek]);
        }
        else if ($tipe === 'bulanan' && $request->filled('bulanan')) {
            [$tahun, $bulan] = explode('-', $request->bulanan);
            $query->whereYear('tanggal_masuk',$tahun)
                  ->whereMonth('tanggal_masuk',$bulan);
        }
        else if ($tipe === 'rentang' && $request->filled('awal') && $request->filled('akhir')) {
            $query->whereBetween('tanggal_masuk', [
                $request->awal, $request->akhir
            ]);
        }

        /* ------------------------- GET DATA ------------------------- */
        $barangs = $query->latest('tanggal_masuk')->get();

        $html = view('management.print.barang-masuk', compact('barangs'))->render();

        $fileName = 'Laporan-Barang-Masuk-'.date('d-m-Y').'.pdf';
        $pdfPath = storage_path('app/temp/'.$fileName);

        Browsershot::html($html)
            ->format('A4')
            ->margins(10,10,10,10)
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->save($pdfPath);

        return response()->file($pdfPath);

    } catch (\Exception $e) {
        return back()->with('swal_error','Gagal membuat PDF: '.$e->getMessage());
    }
}

public function laporan(Request $request)
{
    try {
        $query = BarangMasuk::with(['barang','pengguna']);

        /* ------------------------- FILTER SEARCH ------------------------- */
        if ($request->filled('search')) {
            $query->whereHas('barang', function($q) use ($request) {
                $q->where('nama_barang','LIKE','%'.$request->search.'%');
            });
        }

        /* ------------------------- FILTER JENIS LAPORAN ------------------------- */
        $tipe = $request->tipe;

        // 1. HARILAN
        if ($tipe === 'harian' && $request->filled('harian')) {
            $query->whereDate('tanggal_masuk', $request->harian);
        }

        // 2. MINGGUAN (format: 2025-W48)
        else if ($tipe === 'mingguan' && $request->filled('mingguan')) {
            [$tahun, $minggu] = explode('-W', $request->mingguan);

            $startWeek = \Carbon\Carbon::now()->setISODate($tahun, $minggu)->startOfWeek();
            $endWeek   = \Carbon\Carbon::now()->setISODate($tahun, $minggu)->endOfWeek();

            $query->whereBetween('tanggal_masuk', [$startWeek, $endWeek]);
        }

        // 3. BULANAN (format: 2025-11)
        else if ($tipe === 'bulanan' && $request->filled('bulanan')) {
            [$tahun, $bulan] = explode('-', $request->bulanan);
            $query->whereYear('tanggal_masuk', $tahun)
                  ->whereMonth('tanggal_masuk', $bulan);
        }

        // 4. RENTANG TANGGAL
        else if ($tipe === 'rentang' && $request->filled('awal') && $request->filled('akhir')) {
            $query->whereBetween('tanggal_masuk', [
                $request->awal,
                $request->akhir
            ]);
        }

        /* ------------------------- EKSEKUSI ------------------------- */
        $barangs = $query->latest('tanggal_masuk')->paginate(10);

        return view('management.laporan-barang-masuk', compact('barangs'));

    } catch (\Exception $e) {
        return back()->with('swal_error','Gagal memuat laporan: '.$e->getMessage());
    }
}


}
