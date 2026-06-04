<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    //
        public function run()
        {
            if (!auth()->check() || auth()->user()->role_pengguna !== 'admin') {
                abort(403, 'Unauthorized');
            }

            if (request()->has('download')) {
                Artisan::call('backup:run', ['--only-db' => true, '--disable-notifications' => true]);
                $backupPath = storage_path('app/laravel-backups');
                $files = glob($backupPath . '/*/*.zip');
                if (count($files) > 0) {
                    $latestFile = collect($files)->sortByDesc(function ($file) {
                        return filemtime($file);
                    })->first();
                    return response()->download($latestFile)->deleteFileAfterSend(true);
                } else {
                    return redirect()->back()->with('swal_error', 'Tidak ada backup yang tersedia untuk diunduh.');
                }
            } else {
                Artisan::call('backup:run', ['--only-db' => true]);
                return redirect()->back()->with('swal_success', 'Backup database berhasil dijalankan.');
            }
        }
}
