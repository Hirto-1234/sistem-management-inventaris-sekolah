<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'tabel_barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = true;
    protected $keyType = 'int';

    // TAMBAHKAN stok_barang!
    protected $fillable = [
        'nama_barang',
        'sku_barang',
        'id_kategori',
        'kondisi_barang',
        'stok_barang',
        'jumlah_barang',
        'deskripsi_barang',
        'foto_barang',
        'diupdate_oleh',
        'kode_qr'
    ];

    public $timestamps = true;

    //GANTI integer → decimal untuk harga
    protected $casts = [
        'jumlah_barang' => 'integer',
        'stok_barang'   => 'integer',
    ];

    // === RELASI ===
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang', 'id_barang');
    }

    public function inventarisRuangan()
    {
        return $this->hasMany(InventarisRuangan::class, 'id_barang', 'id_barang');
    }
}
