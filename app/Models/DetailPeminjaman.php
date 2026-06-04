<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'tabel_detail_peminjaman';
    protected $primaryKey = 'id_detail_peminjaman';
    public $timestamps = true;

    protected $fillable = [
        'id_peminjaman',
        'id_barang',
        'jumlah_barang'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
