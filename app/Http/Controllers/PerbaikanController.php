<?php
namespace App\Http\Controllers;

use App\Models\InventarisRuangan;
use App\Models\Perbaikan;
use App\Services\SyncService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PerbaikanController extends Controller
{
    const PENDING      = 'pending';
    const DIPROSES     = 'diproses';
    const BERHASIL     = 'berhasil';
    const GAGAL        = 'gagal';
    const DIBATALKAN   = 'dibatalkan';
    const STATUS_FINAL = [self::BERHASIL, self::GAGAL, self::DIBATALKAN];

    public function index(Request $request)
    {
        $perbaikan = Perbaikan::with(['pengguna', 'inventarisRuangan.barang', 'inventarisRuangan.ruangan'])
            ->when($request->search, fn($q) => $q->where('deskripsi_kerusakan', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest('tanggal_mulai')
            ->paginate(5);

        $inventaris = InventarisRuangan::with(['ruangan', 'barang'])
            ->whereHas('barang')
            ->where('jumlah_barang', '>', 0)
            ->get()
            ->sortBy('ruangan.nama_ruangan');

        return view('management.transaksi-perbaikan', compact('perbaikan', 'inventaris'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateStore($request);

        try {
            DB::transaction(function () use ($request, $validated) {
                $this->kurangiStok($validated['id_inventaris_ruangan'], $validated['jumlah_barang']);

                $status = Carbon::parse($validated['tanggal_mulai'])->isPast() ? self::DIPROSES : self::PENDING;

                Perbaikan::create([
                    'id_pengguna'           => Auth::id(),
                    'id_inventaris_ruangan' => $validated['id_inventaris_ruangan'],
                    'jumlah_barang'         => $validated['jumlah_barang'],
                    'deskripsi_kerusakan'   => $validated['deskripsi_kerusakan'],
                    'tanggal_mulai'         => $validated['tanggal_mulai'],
                    'foto_sebelum'          => $this->uploadFoto($request->file('foto_sebelum')),
                    'status'                => $status,
                ]);
            });

            return redirect()->route('management.transaksi-perbaikan.index')
                ->with('swal_success', 'Data perbaikan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('swal_error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $this->validateUpdateStatus($request);

        try {
            DB::transaction(function () use ($request, $validated, $id) {
                $perbaikan = Perbaikan::with('inventarisRuangan.ruangan', 'inventarisRuangan.barang')->findOrFail($id);

                // JIKA STATUS PERBAIKAN FINAL MAKA STATUS SUDAH TIDAK DAPAT DIUBAH LAGI
                if (in_array($perbaikan->status, self::STATUS_FINAL)) {
                    throw new \Exception("Status '{$perbaikan->status}' tidak dapat diubah.");
                }

                $statusBaru = $validated['status'];

                $dataUpdate = [
                    'status'  => $statusBaru,
                    'catatan' => $request->filled('catatan') ? $request->catatan : $perbaikan->catatan,
                ];

                // MISALKAN STATUS SUDAH FINAL ANTARA BERHASIL, GAGAL DAN DITOLAK MAKA OTOMATIS MENGEMBALIKAN STOK
                $perluRefund = in_array($statusBaru, self::STATUS_FINAL) && ! in_array($perbaikan->status, self::STATUS_FINAL);
                if ($perluRefund) {
                    $this->kembalikanStok($perbaikan);
                }

                // JIKA STATUS FINAL BERHASIL DAN GAGAL MAKA AKAN UPDATE DESKRIPSI PERBAIKAN TANGGAL SELESAI DAN FOTO SESUDAH
                if (in_array($statusBaru, [self::BERHASIL, self::GAGAL])) {
                    $dataUpdate['deskripsi_perbaikan'] = $request->has('deskripsi_perbaikan')
                        ? ($request->deskripsi_perbaikan ?? '')
                        : $perbaikan->deskripsi_perbaikan;
                    $dataUpdate['tanggal_selesai'] = $validated['tanggal_selesai'] ?? now();

                    if ($request->hasFile('foto_sesudah')) {
                        $this->hapusFoto($perbaikan->foto_sesudah);
                        $dataUpdate['foto_sesudah'] = $this->uploadFoto($request->file('foto_sesudah'));
                    }
                }

                $perbaikan->update($dataUpdate);
            });

            return redirect()->route('management.transaksi-perbaikan.index')->with('swal_success', 'Status perbaikan berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->withInput()->with('swal_error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $perbaikan = Perbaikan::with('inventarisRuangan.ruangan', 'inventarisRuangan.barang')->findOrFail($id);

                // HANYA BISA DIHAPUS JIKA STATUS SUDAH FINAL
                if (! in_array($perbaikan->status, self::STATUS_FINAL)) {
                    throw new \Exception("Data perbaikan hanya dapat dihapus jika status sudah final (berhasil/gagal/dibatalkan).");
                }

                $this->hapusFoto($perbaikan->foto_sebelum);
                $this->hapusFoto($perbaikan->foto_sesudah);
                $perbaikan->delete();
            });

            return redirect()->route('management.transaksi-perbaikan.index')->with('swal_success', 'Data perbaikan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('swal_error', $e->getMessage());
        }
    }

    private function validateStore(Request $request)
    {
        return $request->validate([
            'id_inventaris_ruangan' => 'required|exists:tabel_inventaris_ruangan,id_inventaris_ruangan',
            'jumlah_barang'         => 'required|integer|min:1',
            'deskripsi_kerusakan'   => 'required|string|max:1000',
            'tanggal_mulai'         => 'required|date_format:Y-m-d\TH:i',
            'foto_sebelum'          => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);
    }

    private function validateUpdateStatus(Request $request)
    {
        return $request->validate([
            'status'              => 'required|in:diproses,berhasil,gagal,dibatalkan',
            'catatan'             => 'nullable|string|max:1000',
            'deskripsi_perbaikan' => 'nullable|string|max:1000',
            'tanggal_selesai'     => 'nullable|date_format:Y-m-d\TH:i',
            'foto_sesudah'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
    }

    private function uploadFoto($file)
    {
        if (! $file) {
            return null;
        }

        $folder = public_path('uploads/foto_perbaikan');
        File::ensureDirectoryExists($folder);

        $nama = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($folder, $nama);

        return 'uploads/foto_perbaikan/' . $nama;
    }

    private function hapusFoto($path)
    {
        if (! $path || ! File::exists(public_path($path))) {
            return;
        }

        $trash = public_path('trash');
        File::ensureDirectoryExists($trash);
        File::move(public_path($path), $trash . '/' . time() . '_' . basename($path));
    }

    private function kurangiStok($idInventaris, $jumlah)
    {
        $inventaris = InventarisRuangan::with('ruangan', 'barang')->findOrFail($idInventaris);
        $isTU       = strtoupper($inventaris->ruangan->nama_ruangan) === 'TU';

        // JIKA RUANGAN TU MAKA AKAN MENGURANGI STOK BARANG LANGSUNG, JIKA BUKAN MAKA AKAN MENGURANGI STOK DI INVENTARIS RUANGAN
        if ($isTU) {
            $barang = $inventaris->barang;
            if ($barang->stok_barang < $jumlah) {
                throw new \Exception("Stok {$barang->nama_barang} tidak cukup. Tersedia: {$barang->stok_barang}");
            }
            $barang->decrement('stok_barang', $jumlah);
            SyncService::syncTU();
        } else {
            if ($inventaris->jumlah_barang < $jumlah) {
                throw new \Exception("Stok {$inventaris->barang->nama_barang} di {$inventaris->ruangan->nama_ruangan} tidak cukup. Tersedia: {$inventaris->jumlah_barang}");
            }
            $inventaris->decrement('jumlah_barang', $jumlah);
        }
    }

    private function kembalikanStok($perbaikan)
    {
        $inventaris = $perbaikan->inventarisRuangan;
        $isTU       = strtoupper($inventaris->ruangan->nama_ruangan) === 'TU';

        // JIKA RUANGAN TU MAKA AKAN MENGEMBALIKAN DARI STOK BARANG LANGSUNG, JIKA BUKAN MAKA MENGEMBALIKAN DI STOK INVENTARIS RUANGAN
        if ($isTU) {
            $inventaris->barang->increment('stok_barang', $perbaikan->jumlah_barang);
            SyncService::syncTU();
        } else {
            $inventaris->increment('jumlah_barang', $perbaikan->jumlah_barang);
        }
    }
};