<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $roles = ['admin', 'petugas', 'peminjam']; // Role yang ada saat ini

        $query = Pengguna::query()
            ->where('id_pengguna', '!=', auth()->id()) // Jangan tampilkan diri sendiri
            ->select('id_pengguna', 'nama_pengguna', 'nama_lengkap', 'role_pengguna', 'foto_pengguna');

        // Pencarian berdasarkan nama pengguna atau nama lengkap
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pengguna', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        // Filter role
        if ($request->filled('role') && in_array($request->role, $roles)) {
            $query->where('role_pengguna', $request->role);
        }

        // Urutkan & paginasi
        $data = $query->orderBy('nama_lengkap')->paginate(10);

        return view('management.pengguna', compact('data', 'roles'))
            ->with('search', $request->search)
            ->with('role', $request->role);
    }

    public function store(Request $request)
    {
        $roles = ['admin', 'petugas', 'peminjam'];

        $request->validate([
            'nama_pengguna'     => 'required|string|max:100|unique:tabel_pengguna,nama_pengguna',
            'nama_lengkap'      => 'required|string|max:100',
            'password_pengguna' => 'required|string|min:6',
            'role_pengguna'     => ['required', Rule::in($roles)],
            'foto_pengguna'     => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'email'    => 'required|email'
        ]);

        try {
            $foto = null;
            //Jika mengirim foto
            if ($request->hasFile('foto_pengguna')) {
                $file = $request->file('foto_pengguna');
                $foto = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('pengguna', $foto, 'public');
            }

            Pengguna::create([
                'nama_pengguna'     => $request->nama_pengguna,
                'nama_lengkap'      => $request->nama_lengkap,
                'password_pengguna' => Hash::make($request->password_pengguna),
                'role_pengguna'     => $request->role_pengguna,
                'foto_pengguna'     => $foto,
                'email'             => $request->email,
                'email_verified_at' => now()
            ]);

            return redirect()->route('management.pengguna.index')
                ->with('swal_success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('management.pengguna.index')
                ->with('swal_error', 'Gagal menambahkan pengguna. Pastikan data sudah benar.');
        }
    }

    public function update(Request $request, $id)
    {
        $roles = ['admin', 'petugas', 'peminjam'];

        $request->validate([
            'nama_pengguna' => 'required|string|max:100',
            'nama_lengkap'  => 'required|string|max:100',
            'role_pengguna' => ['required', Rule::in($roles)],
            'foto_pengguna' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        try {
            $pengguna = Pengguna::findOrFail($id);

            // Jika mengirim file foto
            if ($request->hasFile('foto_pengguna')) {
                // Hapus foto lama, dipindahkan ke folder trash jika ada keperluan bisa direstore
                if ($pengguna->foto_pengguna) {
                    $oldPath = 'pengguna/' . $pengguna->foto_pengguna;
                    $trashPath = 'trash/pengguna/' . $pengguna->foto_pengguna;

                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->move($oldPath, $trashPath);
                    }
                }

                // Masukkan file baru
                $file = $request->file('foto_pengguna');
                $newFoto = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('pengguna', $newFoto, 'public');
                $pengguna->foto_pengguna = $newFoto;
            }

            // Update data
            $pengguna->nama_pengguna = $request->nama_pengguna;
            $pengguna->nama_lengkap  = $request->nama_lengkap;
            $pengguna->role_pengguna = $request->role_pengguna;
            $pengguna->save();

            return redirect()->route('management.pengguna.index')
                ->with('swal_success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('management.pengguna.index')
                ->with('swal_error', 'Gagal memperbarui data pengguna.');
        }
    }

    public function destroy($id)
    {
        try {
            $pengguna = Pengguna::findOrFail($id);

            // Jika pengguna tidak ada
            if (!$pengguna) {
                return redirect()->route('management.pengguna.index')->with('swal_error', 'Data pengguna tidak ada');
            }

            $penggunaMeminjam = Peminjaman::where('id_pengguna', $id)->first();
            
            // Jika pengguna sedang melakukan peminjaman
            if ($penggunaMeminjam) {
                return redirect()->route('management.pengguna.index')->with('swal_error', 'Pengguna ' . $pengguna->nama_pengguna . 'sedang melakukan peminjaman');
            }

            // Pindah foto ke trash
            if ($pengguna->foto_pengguna) {
                $oldPath = 'pengguna/' . $pengguna->foto_pengguna;
                $trashPath = 'trash/pengguna/' . $pengguna->foto_pengguna;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->move($oldPath, $trashPath);
                }
            }

            $pengguna->delete();

            return redirect()->route('management.pengguna.index')
                ->with('swal_success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('management.pengguna.index')
                ->with('swal_error', 'Gagal menghapus pengguna. Data Pengguna terikat dengan data lain!');
        }
    }
}
