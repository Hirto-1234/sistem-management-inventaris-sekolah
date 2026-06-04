<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarisRuangan extends Model
{
    //
    protected $table = 'tabel_inventaris_ruangan';
    protected $primaryKey = 'id_inventaris_ruangan';
    protected $fillable = [
        'id_ruangan',
        'id_barang',
        'id_pengguna',
        'jumlah_barang'
    ];
    public $timestamps = true;

    //Relasi 
    public function ruangan() {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }
    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
    public function pengguna() {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
    public function pembuangan() {
        return $this->hasMany(BarangKeluar::class, 'id_inventaris_ruangan', 'id_inventaris_ruangan');
    }
}
