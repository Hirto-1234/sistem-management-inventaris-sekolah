<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    //
    protected $table = 'tabel_barang_masuk';
    protected $primaryKey = 'id_barang_masuk';
    protected $fillable = [
        'id_pengguna',
        'id_barang',
        'tanggal_masuk',
        'jumlah_masuk',
        'sumber',
        'keterangan'
    ];
    protected $casts = [
        'tanggal_masuk' => 'date',
        'jumlah_masuk' => 'integer',
        'harga_satuan' => 'integer',
        'harga_total' => 'integer',
    ];
    public $timestamps = true;

    //Relasi 
    public function pengguna() {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
