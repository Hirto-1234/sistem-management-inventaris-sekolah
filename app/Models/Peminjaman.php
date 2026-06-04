<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table      = 'tabel_peminjaman';
    protected $primaryKey = 'id_peminjaman';
    public $incrementing  = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pengguna',
        'id_ruangan',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status_peminjaman',
        'keterangan',        // digunakan untuk alasan jika terjadi penolakan
    ];

    /**
     * Cast attribute
     */
    protected $casts = [
        'tanggal_pinjam'  => 'datetime',
        'tanggal_kembali' => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    // Relasi
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }


    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    // ==================================================================
    // ACCESSORS & MUTATORS
    // ==================================================================

    // Badge status (bisa langsung dipanggil {{ $peminjaman->status_badge }} di Blade)
    public function getStatusBadgeAttribute()
    {
        return match ($this->status_peminjaman) {
            'pending' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'aktif'   => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Aktif</span>',
            'selesai' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Selesai</span>',
            'ditolak' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Ditolak</span>',
            default   => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    // Teks status biasa (tanpa HTML)
    public function getStatusTextAttribute()
    {
        return match ($this->status_peminjaman) {
            'pending' => 'Pending',
            'aktif'   => 'Aktif',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default   => 'Unknown',
        };
    }

    // Format tanggal Indonesia
    public function getTanggalPinjamFormattedAttribute()
    {
        return $this->tanggal_pinjam?->format('d/m/Y');
    }

    public function getTanggalKembaliFormattedAttribute()
    {
        return $this->tanggal_kembali?->format('d/m/Y');
    }

    // Tanggal + jam lengkap
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at?->format('d/m/Y H:i');
    }

    // Cek apakah sudah lewat tanggal kembali (untuk warning di dashboard)
    public function getIsTelatAttribute(): bool
    {
        if ($this->status_peminjaman !== 'aktif') {
            return false;
        }

        return Carbon::now()->greaterThan($this->tanggal_kembali);
    }

    // Hari telat (jika telat)
    public function getHariTelatAttribute(): int
    {
        if (! $this->is_telat) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->tanggal_kembali);
    }

    // Scope untuk filter status (bisa dipakai di controller)
    public function scopePending($query)
    {
        return $query->where('status_peminjaman', 'pending');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_peminjaman', 'aktif');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status_peminjaman', 'selesai');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_peminjaman', 'ditolak');
    }
}
