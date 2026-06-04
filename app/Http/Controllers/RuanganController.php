<?php
namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::query();

        if ($request->filled('search')) {
            $query->where('nama_ruangan', 'like', "%{$request->search}%");
        }

        if ($request->filled('status_penggunaan')) {
            if ($request->status_penggunaan === 'digunakan') {
                $query->has('inventarisRuangan');
            } elseif ($request->status_penggunaan === 'tidak_digunakan') {
                $query->doesntHave('inventarisRuangan');
            }
        }

        $data = $query->withCount('inventarisRuangan')->orderBy('nama_ruangan')->paginate(10);

        return view('management.inventaris-ruangan', compact('data'));
    }

    public function store(Request $request)
    {
        $dataValidasi = $request->validate([
            'nama_ruangan'      => 'required|string|max:255|unique:tabel_ruangan,nama_ruangan',
            'deskripsi_ruangan' => 'nullable|string|max:1000',
        ]);

        try {
            Ruangan::create($dataValidasi);
            return redirect()->route('management.inventaris-ruangan.index')->with('swal_success', 'Ruangan berhasil ditambahkan!');
        } catch (\Exception $error) {
            return redirect()->back()->withInput()->with('swal_error', 'Gagal menambahkan ruangan: ' . $error->getMessage());
        }
    }

    public function update(Request $request, $idRuangan)
    {
        $dataValidasi = $request->validate([
            'nama_ruangan' => "required|string|max:255|unique:tabel_ruangan,nama_ruangan,{$idRuangan},id_ruangan",
            'deskripsi_ruangan' => 'nullable|string|max:1000',
        ]);

        try {
            $detailRuangan = Ruangan::findOrFail($idRuangan);
            $detailRuangan->update($dataValidasi);
            return redirect()->route('management.inventaris-ruangan.index')->with('swal_success', 'Ruangan berhasil diperbarui!');
        } catch (\Exception $error) {
            return redirect()->back()->withInput()->with('swal_error', 'Gagal memperbarui ruangan: ' . $error->getMessage());
        }
    }

    public function destroy($idRuangan)
    {
        try {
            DB::transaction(function () use ($idRuangan) {
                $detailRuangan = Ruangan::findOrFail($idRuangan);

                // MENGECEK APAKAH RUANGAN DIPAKAI DI INVENTARIS RUANGAN ATAU TIDAK, JIKA DIPAKAI MAKA TIDAK BISA DIHAPUS
                if ($detailRuangan->inventarisRuangan()->exists()) {
                    throw new \Exception('Ruangan tidak bisa dihapus karena masih digunakan oleh ' . $detailRuangan->inventarisRuangan()->count() . ' inventaris!');
                }

                $detailRuangan->delete();
            });

            return redirect()->route('management.inventaris-ruangan.index')->with('swal_success', 'Ruangan berhasil dihapus!');
        } catch (\Exception $error) {
            return redirect()->back()->with('swal_error', $error->getMessage());
        }
    }
}