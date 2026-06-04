<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Notifications\VerifikasiEmailKustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('nama_pengguna', 'password_pengguna');

            $pengguna = Pengguna::where('nama_pengguna', $credentials['nama_pengguna'])->first();
            if ($pengguna && Hash::check($credentials['password_pengguna'], $pengguna->password_pengguna)) {
                // Berhasil login
                auth()->login($pengguna);
                session([
                    'is_logged_in' => true,
                    'nama_pengguna' => $pengguna->nama_pengguna,
                    'role_pengguna' => $pengguna->role_pengguna,
                ]);
                return redirect()->route($pengguna->role_pengguna, ['admin', 'petugas'] ? 'management.dashboard' : 'peminjaman.dashboard')->with('success', 'Berhasil login.');
            } else {
                // Gagal login
                return redirect()->back()->with('error', 'Nama pengguna atau password salah.');

            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat proses login.');
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_pengguna' => 'required|string|max:100',
            'nama_lengkap' => 'required|string|max:100',
            'password_pengguna' => 'required',
            'foto_pengguna' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'email' => 'required|email'
        ]);

        DB::beginTransaction();

        try {
            if (Pengguna::where('nama_pengguna', $request->nama_pengguna)->exists()) {
                return redirect()->route('auth.register')->with('error', 'Nama pengguna sudah digunakan, gunakan nama pengguna yang lain');
            }
            
            $fileName = null;
            
            if ($request->hasFile('foto_pengguna')) {
                $file = $request->file('foto_pengguna');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('pengguna', $fileName, 'public');
            }
            
            $data = $request->only('nama_pengguna', 'password_pengguna', 'role_pengguna', 'nama_lengkap', 'foto_pengguna', 'email');
            $data['password_pengguna'] = Hash::make($data['password_pengguna']);
            $data['foto_pengguna'] = $fileName;
            
            $pengguna = Pengguna::create($data);
            $pengguna->sendEmailVerificationNotification();
            DB::commit();
            session([
                'pending_verification' => [
                    'email' => $pengguna->email,
                    'email_verified_at' => null
                ]
            ]);
            return redirect()
                ->route('verification.notice')
                ->with('message', 'Akun berhasil dibuat! Silakan cek email untuk verifikasi.');
        //    return redirect()->route($pengguna->role_pengguna, ['admin', 'petugas'] ? 'management.dashboard' : 'peminjaman.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('auth.register')->with('error', 'Terjadi kesalahan saat proses pendaftaran.' . $e->getMessage());
            // dd($e->getMessage());
        }
    }

    public function logout()
    {
        auth()->logout();
        session()->flush();
        return redirect()->route('login')->with('swal_success', 'Berhasil logout.');
    }

    public function sendResetLink(Request $request) {
        $request->validate(['email' => 'required|email']);

        // Kirim link reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email kamu!')
            : back()->withErrors(['email' => 'Email tidak ditemukan di sistem kami']);

    }

    public function resetToken($token) {
        if (!$token) {
            abort(404);
        } 
        $email = request()->query('email');
        return view('auth.reset-password', compact('email', 'token'));
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($pengguna, $password) {
                $pengguna->password_pengguna = Hash::make($password);
                $pengguna->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil diubah!')
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function updateProfile(Request $request) 
    {
            $request->validate([
                'nama_pengguna'  => 'sometimes|string|max:255',
                'nama_lengkap'   => 'sometimes|string|max:255',
                'password_baru'  => 'sometimes|nullable|string|max:255',
                'password_lama'  => 'sometimes|nullable|string|max:255',
                'foto_pengguna'  => 'sometimes|file|mimes:png,jpg,jpeg|max:2048',
            ]);

            try {
                $pengguna = Pengguna::find(auth()->user()->id_pengguna);

                // Array untuk menyimpan perubahan data
                $dataUpdate = [];

                // Update nama pengguna jika dikirim
                if ($request->filled('nama_pengguna')) {
                    $dataUpdate['nama_pengguna'] = $request->nama_pengguna;
                }

                // Update nama lengkap jika dikirim
                if ($request->filled('nama_lengkap')) {
                    $dataUpdate['nama_lengkap'] = $request->nama_lengkap;
                }

                // Update password jika dikirim
                if ($request->filled('password_baru')) {
                    if (!Hash::check($request->password_lama, $pengguna->password_pengguna)) {
                        return redirect()->back()->with('swal_error', 'Password Lama salah!');
                    }

                    $dataUpdate['password_pengguna'] = Hash::make($request->password_baru);
                }

            // Update foto jika dikirim
            if ($request->hasFile('foto_pengguna')) {

                // Hapus foto lama jika ada
                if ($pengguna->foto_pengguna && Storage::disk('public')->exists('pengguna/' . $pengguna->foto_pengguna)) {
                    $oldPath = 'pengguna/' . $pengguna->foto_pengguna;
                    $trashPath = 'trash/' . $pengguna->foto_pengguna;
                    Storage::disk('public')->makeDirectory('trash');
                    Storage::disk('public')->move($oldPath, $trashPath);
                }

                // Upload foto baru
                $file = $request->file('foto_pengguna');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('pengguna', $fileName, 'public');

                $dataUpdate['foto_pengguna'] = $fileName;
            }

            // Update data
            if (!empty($dataUpdate)) {
                $pengguna->update($dataUpdate);
            }

            return back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
