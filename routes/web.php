<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\DashboardManagementController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\InventariBarangRuanganController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\RuanganController;
use App\Models\Pengguna;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;


// ================= PROFILE =====================
Route::prefix('profile')->middleware('authpenggunamiddleware:peminjam,petugas,admin')->group(function () {
    Route::view('/', 'auth.edit-profil')->name('profile');
    Route::patch('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// ================= AUTH ROUTES =================
Route::middleware('authenticatedpengguna')->group(function () {
    Route::view('/', 'auth.login')->name('login');
    Route::view('/login', 'auth.login')->middleware('noback')->name('login');
    Route::view('/register', 'auth.register')->middleware('noback')->name('auth.register');
    Route::view('/lupa-password', 'auth.lupa-password')->middleware('noback')->name('auth.lupa-password');
    Route::get('/reset/password/{token}', [AuthController::class, 'resetToken'])->name('reset-token');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
});

Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/lupa-password', [AuthController::class, 'sendResetLink'])->name('auth.lupa-password.post');

// ================= USER ROUTES =================
Route::prefix('peminjaman')->middleware(['authpenggunamiddleware:peminjam', 'verified'])->name('peminjaman.')->group(function () {
    Route::get('/', fn() => redirect()->route('peminjaman.dashboard'));
    Route::get('/dashboard', [PeminjamanController::class, 'dashboardUser'])->name('dashboard');
    Route::view('/inventaris', 'user.inventaris')->name('inventaris');
    Route::get('/riwayat', [PeminjamanController::class, 'indexUser'])->name('riwayat');
    Route::get('/barang', [BarangController::class, 'indexForPeminjam'])->name('barang');

    Route::prefix('transaksi-peminjaman')->name('transaksi.')->group(function () {
        Route::get('/', [PeminjamanController::class, 'indexUser'])->name('index');
        Route::get('/create', function () {
            $barang = \App\Models\Barang::where('stok_barang', '>', 0)->get();
            $ruangan = \App\Models\Ruangan::orderBy('nama_ruangan')->get();
            return view('peminjaman.transaksi-peminjaman', compact('barang', 'ruangan'))->with('createMode', true);
        })->name('create');
        Route::post('/', [PeminjamanController::class, 'store'])->name('store');
        Route::get('/{id}', [PeminjamanController::class, 'show'])->name('show');
    });
});

// ================= MANAGEMENT ROUTES =================
Route::prefix('management')->middleware(['authpenggunamiddleware:admin,petugas', 'verified'])->name('management.')->group(function () {

    Route::get('/', fn() => redirect()->route('management.dashboard'));
    Route::get('/dashboard', [DashboardManagementController::class, 'index'])->name('dashboard');

    // === INVENTARIS KATEGORI ===
    Route::prefix('inventaris-kategori')->name('inventaris-kategori.')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::post('/', [KategoriController::class, 'store'])->name('store');
        Route::put('/{id}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('destroy');
    });

    // === INVENTARIS BARANG ===
    Route::prefix('inventaris-barang')->name('inventaris-barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::get('/print', [BarangController::class, 'print'])->name('print');
        Route::get('/{barang}', [BarangController::class, 'show'])->name('show');
        Route::get('/{barang}/edit', [BarangController::class, 'edit'])->name('edit');
        Route::patch('/{barang}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{barang}', [BarangController::class, 'destroy'])->name('destroy');
        Route::post('/import', [BarangController::class, 'import'])->name('import');
        Route::get('/export', [BarangController::class, 'export'])->name('export');

    });

    // === INVENTARIS RUANGAN ===
    Route::prefix('inventaris-ruangan')->name('inventaris-ruangan.')->group(function () {
        Route::get('/', [RuanganController::class, 'index'])->name('index');
        Route::post('/', [RuanganController::class, 'store'])->name('store');
        Route::put('/{id}', [RuanganController::class, 'update'])->name('update');
        Route::delete('/{id}', [RuanganController::class, 'destroy'])->name('destroy');

    });

    // === INVENTARIS BARANG RUANGAN ===
    Route::prefix('inventaris-barang-ruangan')->name('inventaris-barang-ruangan.')->group(function () {
        Route::get('/', [InventariBarangRuanganController::class, 'index'])->name('index');
        Route::get('/{id}', [InventariBarangRuanganController::class, 'detail'])->name('detail');
        Route::post('/', [InventariBarangRuanganController::class, 'store'])->name('store');
        Route::patch('/{id}', [InventariBarangRuanganController::class, 'update'])->name('update');
        Route::delete('/{id}', [InventariBarangRuanganController::class, 'delete'])->name('delete');
    });

    // === PENGGUNA ===
    Route::prefix('pengguna')->middleware('authpenggunamiddleware:admin')->name('pengguna.')->group(function () {
        Route::get('/', [PenggunaController::class, 'index'])->name('index');
        Route::post('/', [PenggunaController::class, 'store'])->name('store');
        Route::patch('/{id}', [PenggunaController::class, 'update'])->name('update');
        Route::delete('/{id}', [PenggunaController::class, 'destroy'])->name('destroy');
    });

    Route::post('/backup', [BackupController::class, 'run'])->middleware('authpenggunamiddleware:admin')->name('backup.run');

    // === TRANSAKSI PEMINJAMAN (INI YANG DIPERBAIKI) ===
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('index');
        Route::post('/', [PeminjamanController::class, 'store'])->name('store');
        Route::get('/{id}', [PeminjamanController::class, 'show'])->name('show');
        Route::get('/export', [PeminjamanController::class, 'export'])->name('export');
        Route::delete('/{id}', [PeminjamanController::class, 'destroy'])->name('destroy');


        // Aksi khusus
        Route::post('/{id}/aktifkan', [PeminjamanController::class, 'aktifkan'])
            ->name('aktifkan');

        Route::post('/{id}/kembali', [PeminjamanController::class, 'kembali'])
            ->name('kembali');

        // INI YANG BARU DITAMBAH → TOLAK
        Route::post('/{id}/tolak', [PeminjamanController::class, 'tolak'])
            ->name('tolak');
    });

    // === TRANSAKSI PERBAIKAN ===
    Route::prefix('transaksi-perbaikan')->name('transaksi-perbaikan.')->group(function () {
        Route::get('/', [PerbaikanController::class, 'index'])->name('index');
        Route::post('/', [PerbaikanController::class, 'store'])->name('store');
        Route::put('/{id}', [PerbaikanController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [PerbaikanController::class, 'destroy'])->name('delete');
    });

    // === RIWAYAT BARANG MASUK ===
    Route::prefix('barang-masuk')->name('barang-masuk.')->group(function () {
        Route::get('/', [BarangMasukController::class, 'index'])->name('index');
        Route::get('/print', [BarangMasukController::class, 'print'])->name('print');
        Route::post('/', [BarangMasukController::class, 'store'])->name('store');
         Route::delete('/{id}', [BarangMasukController::class, 'destroy'])->name('delete');
    });

    // === RIWAYAT BARANG KELUAR ===
    Route::prefix('barang-keluar')->name('barang-keluar.')->group(function () {
        Route::get('/', [BarangKeluarController::class, 'index'])->name('index');
        Route::post('/', [BarangKeluarController::class, 'store'])->name('store');
        Route::get('/{id}', [BarangKeluarController::class, 'show'])->name('show');
        Route::delete('/{id}', [BarangKeluarController::class, 'destroy'])->name('destroy');
    });
    // === LAPORAN ===
Route::prefix('laporan')->name('laporan.')->group(function () {

    // LAPORAN BARANG MASUK
    Route::get('/barang-masuk', [BarangMasukController::class, 'laporan'])
        ->name('barang-masuk');

    // Export PDF
    Route::get('/barang-masuk/print', [BarangMasukController::class, 'print'])
        ->name('barang-masuk.print');


    // LAPORAN BARANG KELUAR
    Route::get('/barang-keluar', [BarangKeluarController::class, 'laporan'])
        ->name('barang-keluar');

    // Export PDF
    Route::get('/barang-keluar/print', [BarangKeluarController::class, 'print'])
        ->name('barang-keluar.print');
    
    // LAPORAN INVENTARIS BARANG
    Route::get('/inventaris-barang', [BarangController::class, 'laporan'])
        ->name('inventaris-barang');

    // Export PDF
    Route::get('/inventaris-barang/print', [BarangController::class, 'print'])
        ->name('inventaris-barang.print');
});


    // === EDIT PROFIL (kalau memang butuh, bisa diperbaiki nanti) ===
    // Route::prefix('edit-profil')->name('edit-profil.')->group(function () { ... });
});

// QR Code Handle
Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
    Route::get('/{kode}', [BarangController::class, 'getDataByQR'])->name('qr-barang');
});

// ================= EMAIL VERIFICATION =================
// View: halaman "silakan cek email Anda"
Route::get('/email/verify', function () {
    return view('auth.verifikasi-email');
})->middleware('onlyunverified', 'noback')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {

    $user = Pengguna::findOrFail($id);

    // Cegah link palsu — hash harus cocok
    if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Link verifikasi tidak valid');
    }

    // Tandai sebagai terverifikasi
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    session()->forget('pending_verification');

    return redirect()->route('login')
        ->with('success', 'Email berhasil diverifikasi! Silakan login.');
})->middleware('signed')->name('verification.verify');

// Resend verification link
Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
