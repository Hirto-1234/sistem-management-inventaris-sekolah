<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table      = 'tabel_barang_keluar';
    protected $primaryKey = 'id_barang_keluar';
    protected $fillable   = [
        'id_pengguna',
        'id_inventaris_ruangan',
        'tanggal_keluar',
        'jumlah_keluar',
        'kategori_keluar',
        'keterangan',
    ];
    public $timestamps = true;

    // Relasi
    public function pengguna() {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
    public function inventarisRuangan() {
        return $this->belongsTo(InventarisRuangan::class, 'id_inventaris_ruangan', 'id_inventaris_ruangan');
    }
}
