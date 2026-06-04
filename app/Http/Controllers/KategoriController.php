<?php
namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $queryKategori = Kategori::query();

        if ($request->filled('search')) {
            $queryKategori->where('nama_kategori', 'like', "%{$request->search}%");
        }

        if ($request->filled('status_penggunaan')) {
            if ($request->status_penggunaan === 'digunakan') {
                $queryKategori->has('barang');
            } elseif ($request->status_penggunaan === 'tidak_digunakan') {
                $queryKategori->doesntHave('barang');
            }
        }

        $dataKategori = $queryKategori->withCount('barang')->orderBy('nama_kategori')->paginate(10);

        return view('management.inventaris-kategori', compact('dataKategori'));
    }

    public function store(Request $request)
    {
        $dataValidasi = $request->validate([
            'nama_kategori'      => 'required|string|max:255|unique:tabel_kategori,nama_kategori',
            'deskripsi_kategori' => 'nullable|string|max:1000',
        ]);

        try {
            Kategori::create($dataValidasi);
            return redirect()->route('management.inventaris-kategori.index')->with('swal_success', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $error) {
            return redirect()->back()->withInput()->with('swal_error', 'Gagal menambahkan kategori: ' . $error->getMessage());
        }
    }

    public function update(Request $request, $idKategori)
    {
        $dataValidasi = $request->validate([
            'nama_kategori' => "required|string|max:255|unique:tabel_kategori,nama_kategori,{$idKategori},id_kategori",
            'deskripsi_kategori' => 'nullable|string|max:1000',
        ]);

        try {
            $detailKategori = Kategori::findOrFail($idKategori);
            $detailKategori->update($dataValidasi);
            return redirect()->route('management.inventaris-kategori.index')->with('swal_success', 'Kategori berhasil diperbarui!');
        } catch (\Exception $error) {
            return redirect()->back()->withInput()->with('swal_error', 'Gagal memperbarui kategori: ' . $error->getMessage());
        }
    }

    public function destroy($idKategori)
    {
        try {
            DB::transaction(function () use ($idKategori) {
                $detailKategori = Kategori::findOrFail($idKategori);

                // MENGECEK APAKAH KATEGORI DIPAKAI DI BARANG ATAU TIDAK, JIKA DIPAKAI MAKA TIDAK BISA DIHAPUS 
                if ($detailKategori->barang()->exists()) {
                    throw new \Exception('Kategori tidak bisa dihapus karena masih digunakan oleh ' . $detailKategori->barang()->count() . ' barang!');
                }

                $detailKategori->delete();
            });

            return redirect()->route('management.inventaris-kategori.index')->with('swal_success', 'Kategori berhasil dihapus!');
        } catch (\Exception $error) {
            return redirect()->back()->with('swal_error', $error->getMessage());
        }
    }
}