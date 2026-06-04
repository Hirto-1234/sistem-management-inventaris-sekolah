<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Ruangan;
use App\Models\InventarisRuangan;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Perbaikan;

class DashboardManagementController extends Controller
{
    public function index()
    {
        $data = [
            'total_barang'            => Barang::count(),
            'total_peminjaman'        => Peminjaman::whereNotNull('tanggal_pinjam')->count(),
            'total_perbaikan'         => Perbaikan::where('status', 'diproses')->count(),
            'total_ruangan'           => Ruangan::distinct('id_ruangan')->count('id_ruangan'),
            'total_kategori'          => Kategori::count(),
            'barang_masuk_bulan_ini'  => BarangMasuk::whereMonth('tanggal_masuk', date('m'))->sum('jumlah_masuk'),
            'barang_keluar_bulan_ini' => BarangKeluar::whereMonth('tanggal_keluar', date('m'))->sum('jumlah_keluar'),
            'stok_inventaris'         => InventarisRuangan::sum('jumlah_barang'),
        ];

        return view('management.dashboard', $data);
    }
}