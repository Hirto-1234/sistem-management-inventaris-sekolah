<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Imports\BarangImport;
use App\Exports\BarangExport;
use App\Models\BarangMasuk;
use App\Models\DetailPeminjaman;
use App\Models\InventarisRuangan;
use App\Models\Perbaikan;
use App\Models\Ruangan;
use App\Services\SyncService;
use Illuminate\Support\Facades\File as FacadesFile;
use Laravel\Pail\File;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Browsershot\Browsershot;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        //ambil data barang + relasi kategori buat ditampilin di view
        $query = Barang::with(['kategori'])->latest();

        //jika input search diisi maka akan filter berdasarkan nama barang
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }
        //jika filter kategori dipilih, tampilin barang dengan kategori tsb
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        //paginate hasilnya, 10 data per halaman
        $barangs = $query->paginate(10);
        //mengambil semua kategori buat dropdown filter
        $kategoris = Kategori::all();
        //kirim data nya ke view
        return view('management.inventaris-barang', compact('barangs', 'kategoris'));
    }

    public function indexForPeminjam(Request $request)
{
    //ambil data barang + relasi kategori buat ditampilin ke peminjam
    $query = Barang::with(['kategori']);

    //kalo ada input pencarian, filter nama barang
    if ($request->filled('search')) {
        $query->where('nama_barang', 'like', '%' . $request->search . '%');
    }

    //kalo kategori dipilih, filter berdasarkan id kategori
    if ($request->filled('kategori')) {
        $query->where('id_kategori', $request->kategori);
    }

    //paginate hasilnya 10 item per halaman
    $barangs = $query->paginate(10);

    //ambil semua kategori buat dropdown filter
    $kategoris = Kategori::all();

    //kirim data ke view peminjaman/barang
    return view('peminjaman.barang', compact('barangs', 'kategoris'));
}


    public function store(Request $request)
{
    try {
        //validasi input form
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_sku' => 'required|string|max:255',
            'id_kategori' => 'required|exists:tabel_kategori,id_kategori',
            'deskripsi_barang' => 'nullable|string',
            'foto_barang' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
        ]);

        //cek apakah nama barang atau sku sudah ada di database
        $existing = Barang::where('nama_barang', $request->nama_barang)
            ->orWhere('sku_barang', $request->kode_sku)
            ->first();

        //kalau sudah ada, balikin pesan error suruh update stok lewat barang masuk
        if ($existing) {
            return redirect()->route('management.inventaris-barang.index')
                ->with('swal_error', 'Barang atau SKU sudah ada, untuk mengupdate stok melalui barang masuk');
        }

        //siapin variabel buat nama file (null kalau ga upload)
        $filename = null;

        //kalo ada upload foto, proses dan simpan ke folder public/uploads/barangs
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barangs'), $filename);
        }

        //buat data barang baru di database
        $newBarang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'sku_barang' => $request->kode_sku,
            'id_kategori' => $request->id_kategori,
            'deskripsi_barang' => $request->deskripsi_barang,
            'foto_barang' => $filename,
        ]);

        //generate kode QR dengan format BRG-00001
        $newBarang->kode_qr = 'BRG-' . str_pad($newBarang->id_barang, 5, '0', STR_PAD_LEFT);
        $newBarang->save();

        //jalanin sync service agar data ke TU terupdate
        SyncService::syncTU();

        //balikin pesan sukses
        return redirect()->route('management.inventaris-barang.index')
            ->with('swal_success', 'Barang berhasil ditambahkan!');
    } catch (\Exception $e) {

        //kalo error, balikin pesan error dari exception
        return redirect()->route('management.inventaris-barang.index')
            ->with('swal_error', $e->getMessage());
    }
}



    public function show($id)
    {
        //ambil data barang berdasarkan id + relasi kategori
        $barang = Barang::with('kategori')->find($id);

        //kalo data barang ga ditemukan balikin error 404
        if (!$barang) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        //kalo ada, balikin data barang dalam bentuk json
        return response()->json($barang);
    }


    public function print(Request $request)
{
    try {

        //ambil data barang + relasi kategori
        $query = Barang::with(['kategori']);

        //kalo ada input search, filter nama barang
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        //kalo kategori dipilih, filter id kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        //ambil semua data (tanpa pagination) buat dicetak
        $barangs = $query->get();

        //render view jadi html string
        $html = view('management.print.inventaris-barang', compact('barangs'))->render();

        //buat nama file pdf
        $fileName = 'Inventaris-Barang-' . date('d-m-Y') . '.pdf';

        //tentuin lokasi penyimpanan sementara pdf
        $pdfPath  = storage_path('app/temp/' . $fileName);

        //generate pdf pake Browsershot
        Browsershot::html($html)
            ->format('A4')
            ->margins(5, 5, 5, 5)
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->save($pdfPath);

        //tampilin pdf langsung di browser
        return response()->file($pdfPath, [
            'Content-Disposition' => 'inline; filename="'.$fileName.'"'
        ])->deleteFileAfterSend(true);

    } catch (\Exception $e) {

        //kalo gagal generate pdf, balikin alert error
        return back()->with('swal_error', 'Gagal membuat PDF: ' . $e->getMessage());
    }
}


    public function edit(Barang $barang)
{
    //ambil semua kategori buat ditampilin di form edit
    $kategoris = Kategori::all();

    //balikin data barang + kategori dalam bentuk json
    return response()->json([
        'barang' => $barang,
        'kategoris' => $kategoris,
    ]);
}

public function update(Request $request, Barang $barang)
{
    //validasi input update
    $request->validate([
        'nama_barang' => 'required|string|max:255',
        'deskripsi_barang' => 'nullable|string',
        'foto_barang' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
        'asal_dana' => 'sometimes|string|max:100',
        'harga_satuan' => 'sometimes|integer|min:0',
    ]);

    //cek apakah nama barang sudah dipakai barang lain
    $existing = Barang::where('nama_barang', $request->nama_barang)
        ->where('id_barang', '!=', $barang->id_barang)
        ->first();

    //kalo nama sudah dipakai barang lain, balikin pesan error
    if ($existing) {
        return redirect()->back()->with('swal_error', 'Nama barang sudah digunakan, silakan gunakan nama lain.');
    }

    //siapkan nama foto, defaultnya foto lama
    $imageName = $barang->foto_barang;

    //kalo ada upload foto baru
    if ($request->hasFile('foto_barang')) {

        //kalo foto lama ada, pindahin ke folder trash
        if ($barang->foto_barang && FacadesFile::exists(public_path($barang->foto_barang))) {
            $trashPath = public_path('trash');

            //bikin folder trash kalo belum ada
            if (!FacadesFile::exists($trashPath)) {
                FacadesFile::makeDirectory($trashPath, 0755, true);
            }

            //pindahin file lama ke folder trash
            $oldPath = public_path($barang->foto_barang);
            $newPath = $trashPath . '/' . time() . '_' . basename($barang->foto_barang);
            FacadesFile::move($oldPath, $newPath);
        }

        //upload foto baru ke uploads/barangs
        $image = $request->file('foto_barang');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/barangs'), basename($imageName));
    }

    //update data barang
    $barang->update([
        'nama_barang' => $request->nama_barang,
        'deskripsi_barang' => $request->deskripsi_barang,
        'foto_barang' => $imageName,
        'asal_dana' => $request->asal_dana,
        'harga_satuan' => $request->harga_satuan,
        'diupdate_oleh' => auth()->user()->nama_pengguna,
    ]);

    //balikin ke halaman daftar barang dengan pesan sukses
    return redirect()
        ->route('management.inventaris-barang.index')
        ->with('success', 'Barang berhasil diperbarui!');
}



   public function destroy($id)
{
    //kalo id kosong langsung balik ke daftar barang
    if (!$id) return redirect()->route('management.inventaris-barang.index');

    try {
        //ambil data barang berdasarkan id
        $barang = Barang::findOrFail($id);
        $idBarang = $barang->id_barang;

        //cek apakah barang sedang dipinjam (status peminjaman = aktif)
        $dipinjam = DetailPeminjaman::where('id_barang', $idBarang)
            ->whereHas('peminjaman', fn($q) => $q->where('status_peminjaman', 'aktif'))
            ->exists();

        //kalo sedang dipinjam, batalkan hapus
        if ($dipinjam) {
            return redirect()->route('management.inventaris-barang.index')
                             ->with('swal_error', 'Barang sedang dipinjam');
        }

        //cek apakah barang tercatat di ruangan selain TU
        $inventaris = InventarisRuangan::where('id_barang', $idBarang)
            ->whereHas('ruangan', fn($q) => $q->where('nama_ruangan', '!=', 'TU'))
            ->exists();

        //kalo masih dipakai ruangan lain, batalkan hapus
        if ($inventaris) {
            return redirect()->route('management.inventaris-barang.index')
                             ->with('swal_error', 'Barang sedang digunakan di ruangan lain');
        }

        //kalo aman, hapus barang (barang di TU ga masalah)
        $barang->delete();

        //balikin pesan sukses
        return redirect()->route('management.inventaris-barang.index')
                         ->with('swal_success', 'Barang berhasil dihapus');

    } catch (\Exception $e) {
        //balikin pesan error kalau gagal
        return redirect()->route('management.inventaris-barang.index')
                         ->with('swal_error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

public function getDataByQR($kode)
{
    //ambil data barang berdasarkan kode qr
    $barang = Barang::with('kategori')->where('kode_qr', $kode)->first();

    //kalo ga ada, balikin error json
    if (!$barang) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    //kalo ada, kirim ke view yang auto open modal
    return view('qr.detail-barang-auto-open', ['data' => $barang]);
}

public function laporan(Request $request)
{
    try {
        $query = Barang::with('kategori');

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        $barangs = $query->latest('created_at')->paginate(10);
        $kategoris = Kategori::all();

        return view('management.laporan-inventaris-barang', compact('barangs', 'kategoris'));
    } catch (\Exception $e) {
        return back()->with('swal_error', 'Gagal memuat laporan: ' . $e->getMessage());
    }
}
}
