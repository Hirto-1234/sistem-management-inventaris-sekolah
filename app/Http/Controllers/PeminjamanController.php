<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // ================== Management: INDEX (SEMUA) ==================
    public function index(Request $request)
{
    //ambil data peminjaman + relasi pengguna dan ruangan
    $query = Peminjaman::with(['pengguna', 'ruangan']);

    //kalo request punya filter status, filter berdasarkan status peminjaman
    if ($request->filled('status')) {
        $query->where('status_peminjaman', $request->status);
    }

    //kalo ada input pencarian, cari di id_peminjaman atau nama pengguna
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('id_peminjaman', 'like', "%{$search}%")
              ->orWhereHas('pengguna', fn($q) =>
                    $q->where('nama_pengguna', 'like', "%{$search}%")
                );
        });
    }

    //urutkan terbaru dan paginasi 10 data
    $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

    //ambil semua pengguna untuk dropdown
    $pengguna = Pengguna::select('id_pengguna', 'nama_pengguna', 'role_pengguna')
        ->orderBy('nama_pengguna')
        ->get();

    //ambil barang yang stoknya masih ada
    $barang  = Barang::where('stok_barang', '>', 0)->get();

    //ambil semua ruangan kecuali TU
    $ruangan = Ruangan::where('nama_ruangan', '!=', 'TU')
              ->orderBy('nama_ruangan')
              ->get();

    //kirim ke view management
    return view('management.transaksi-peminjaman', compact('peminjaman', 'pengguna', 'barang', 'ruangan'));
}

//UNTUK ROLE PEMINJAM
public function indexUser(Request $request)
{
    //ambil data peminjaman milik user login + relasi detail peminjaman, barang, ruangan
    $query = Peminjaman::where('id_pengguna', Auth::id())
        ->with(['detailPeminjaman.barang', 'ruangan']);

    //filter status kalo ada di request
    if ($request->filled('status')) {
        $query->where('status_peminjaman', $request->status);
    }

    //filter pencarian berdasarkan id peminjaman
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where('id_peminjaman', 'like', "%{$search}%");
    }

    //hasil diurutkan terbaru dan paginasi 10 data
    $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

    //barang yang stok masih tersedia
    $barang  = Barang::where('stok_barang', '>', 0)->get();

    //ruangan selain TU
    $ruangan = Ruangan::where('nama_ruangan', '!=', 'TU')
              ->orderBy('nama_ruangan')
              ->get();

    //kirim ke view user
    return view('peminjaman.transaksi-peminjaman', compact('peminjaman', 'barang', 'ruangan'));
}


    // ================== STORE ==================
    public function store(Request $request)
{
    //validasi input wajib dan harus sesuai tabel relasi
    $request->validate([
        'id_pengguna'       => 'required|exists:tabel_pengguna,id_pengguna',
        'id_ruangan'        => 'required|exists:tabel_ruangan,id_ruangan',
        'tanggal_pinjam'    => 'required|date',
        'tanggal_kembali'   => 'required|date|after_or_equal:tanggal_pinjam',
        'items'             => 'required|array',
        'items.*.id_barang' => 'required|exists:tabel_barang,id_barang',
        'items.*.jumlah'    => 'required|integer|min:1',
    ]);

    //kalo bukan admin/petugas -> cuma boleh ajukan buat dirinya sendiri
    if (!in_array(Auth::user()->role_pengguna, ['admin', 'petugas']) && $request->id_pengguna != Auth::id()) {
        return back()->with('swal_error', 'Anda hanya bisa mengajukan untuk diri sendiri.');
    }

    //tentukan status awal berdasarkan role
    $status = in_array(Auth::user()->role_pengguna, ['admin', 'petugas'])
        ? 'aktif'
        : 'pending';

    DB::beginTransaction();
    try {
        //buat data utama peminjaman
        $peminjaman = Peminjaman::create([
            'id_pengguna'       => $request->id_pengguna,
            'id_ruangan'        => $request->id_ruangan,
            'tanggal_pinjam'    => $request->tanggal_pinjam,
            'tanggal_kembali'   => $request->tanggal_kembali,
            'status_peminjaman' => $status, //status default nunggu admin
            'keterangan'        => $request->keterangan,
        ]);

        //loop semua item yg dipinjam
        foreach ($request->items as $item) {
            $barang = Barang::find($item['id_barang']);

            //cek stok cukup atau tidak
            if ($barang->stok_barang < $item['jumlah']) {
                throw new \Exception("Stok {$barang->nama_barang} tidak cukup!");
            }

            // kalo peminjaman langsung aktif (admin/petugas), stok langsung dikurangi
            if ($status === 'aktif') {
                $barang->decrement('stok_barang', $item['jumlah']);
            }

            //simpan detail barang yg dipinjam
            DetailPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_barang'     => $item['id_barang'],
                'jumlah_barang' => $item['jumlah'],
            ]);
        }

        DB::commit();

        //tentuin mau redirect kemana tergantung role
        $route = in_array(Auth::user()->role_pengguna, ['admin', 'petugas'])
            ? 'management.peminjaman.index'
            : 'peminjaman.transaksi.index';

        return redirect()->route($route)->with('swal_success', 'Peminjaman berhasil diajukan! Menunggu persetujuan admin.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('swal_error', $e->getMessage());
    }
}


    public function show($id)
{
    //ambil data peminjaman lengkap beserta relasinya
    $peminjaman = Peminjaman::with(['pengguna', 'ruangan', 'detailPeminjaman.barang'])
                            ->findOrFail($id);

    //kalo user biasa -> cuma boleh lihat punya dia sendiri
    if (!in_array(Auth::user()->role_pengguna, ['admin', 'petugas']) && $peminjaman->id_pengguna != Auth::id()) {
        abort(403, 'Akses ditolak.');
    }

    //bedain tampilan admin/petugas sama user biasa
    $view = in_array(Auth::user()->role_pengguna, ['admin', 'petugas'])
        ? 'management.peminjaman.detail'
        : 'peminjaman.detail';

    return view($view, compact('peminjaman'));
}


    //AKTIFKAN peminjaman (khusus admin/petugas)
public function aktifkan($id)
{
    //cek role, user biasa gaboleh
    if (!in_array(Auth::user()->role_pengguna, ['admin', 'petugas'])) {
        return back()->with('swal_error', 'Akses ditolak.');
    }

    //ambil data peminjaman + detail + barangnya
    $peminjaman = Peminjaman::with('detailPeminjaman.barang')->findOrFail($id);

    //cuma yang status pending yang bisa diaktifkan
    if ($peminjaman->status_peminjaman !== 'pending') {
        return back()->with('swal_error', 'Hanya peminjaman dengan status pending yang dapat diaktifkan.');
    }

    DB::beginTransaction();
    try {
        //cek stok tiap barang & kurangi stok
        foreach ($peminjaman->detailPeminjaman as $detail) {
            $barang = $detail->barang;
            if ($barang->stok_barang < $detail->jumlah_barang) {
                throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi saat ini.");
            }
            $barang->decrement('stok_barang', $detail->jumlah_barang);
        }

        //ubah status jadi aktif
        $peminjaman->update(['status_peminjaman' => 'aktif']);

        DB::commit();
        return back()->with('swal_success', 'Peminjaman berhasil diaktifkan dan stok telah dikurangi.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('swal_error', $e->getMessage());
    }
}



    //KEMBALIKAN peminjaman (khusus admin/petugas)
public function kembali($id)
{
    //cek role, user biasa gabole
    if (!in_array(Auth::user()->role_pengguna, ['admin', 'petugas'])) {
        return back()->with('swal_error', 'Akses ditolak.');
    }

    //ambil data peminjaman + detail barang
    $peminjaman = Peminjaman::with('detailPeminjaman.barang')->findOrFail($id);

    //cek status, cuma yg aktif yg bisa dikembalikan
    if ($peminjaman->status_peminjaman !== 'aktif') {
        return back()->with('swal_error', 'Hanya peminjaman aktif yang dapat dikembalikan.');
    }

    DB::beginTransaction();
    try {
        //balikin stok barang sesuai jumlah yg dipinjam
        foreach ($peminjaman->detailPeminjaman as $detail) {
            $detail->barang->increment('stok_barang', $detail->jumlah_barang);
        }

        //ubah status jadi selesai
        $peminjaman->update(['status_peminjaman' => 'selesai']);

        DB::commit();
        return back()->with('swal_success', 'Barang berhasil dikembalikan dan stok telah ditambah kembali.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('swal_error', 'Gagal mengembalikan: ' . $e->getMessage());
    }
}

    //TOLAK peminjaman (khusus admin/petugas)
public function tolak(Request $request, $id)
{
    //cek role, user biasa gabole
    if (!in_array(Auth::user()->role_pengguna, ['admin', 'petugas'])) {
        return back()->with('swal_error', 'Akses ditolak.');
    }

    //validasi alasan tolak wajib ada
    $request->validate([
        'keterangan_tolak' => 'required|string|max:500',
    ]);

    //ambil data peminjaman
    $peminjaman = Peminjaman::findOrFail($id);

    //cek status, cuma yg pending yg bisa ditolak
    if ($peminjaman->status_peminjaman !== 'pending') {
        return back()->with('swal_error', 'Hanya peminjaman berstatus pending yang dapat ditolak.');
    }

    DB::beginTransaction();
    try {
        //ubah status ke ditolak + simpen alasan
        $peminjaman->update([
            'status_peminjaman' => 'ditolak',
            'keterangan'        => $request->keterangan_tolak,
        ]);

        DB::commit();
        return back()->with('swal_success', 'Peminjaman berhasil ditolak.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('swal_error', 'Gagal menolak peminjaman: ' . $e->getMessage());
    }
}


   //DASHBOARD USER (menampilkan 5 peminjaman terbaru user)
public function dashboardUser()
{
    //ambil 5 peminjaman terakhir milik user yg login
    $peminjamanTerakhir = Peminjaman::where('id_pengguna', Auth::id())
        ->with(['detailPeminjaman.barang', 'ruangan'])
        ->latest()      //urutin dari yg paling baru
        ->take(5)       //ambil 5 aja
        ->get();

    //lempar ke view dashboard user
    return view('peminjaman.dashboard', compact('peminjamanTerakhir'));
}

    //DELETE (khusus admin/petugas, cuma bisa hapus yg ditolak/selesai)
public function destroy($id)
{
    //cek role (non admin/petugas ditolak)
    if (!in_array(Auth::user()->role_pengguna, ['admin', 'petugas'])) {
        return back()->with('swal_error', 'Akses ditolak.');
    }

    //ambil data peminjaman
    $peminjaman = Peminjaman::findOrFail($id);

    //hanya status ditolak/selesai yg boleh dihapus
    if (!in_array($peminjaman->status_peminjaman, ['ditolak', 'selesai'])) {
        return back()->with('swal_error', 'Hanya peminjaman yang ditolak atau selesai yang dapat dihapus.');
    }

    DB::beginTransaction();
    try {
        //hapus detail peminjaman dulu
        $peminjaman->detailPeminjaman()->delete();

        //hapus data utama
        $peminjaman->delete();

        DB::commit();
        return back()->with('swal_success', 'Peminjaman berhasil dihapus.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('swal_error', 'Gagal menghapus peminjaman: ' . $e->getMessage());
    }
}


}
