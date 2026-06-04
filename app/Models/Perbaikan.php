<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $table      = 'tabel_perbaikan';
    protected $primaryKey = 'id_perbaikan';

    protected $fillable = [
        'id_pengguna',
        'id_inventaris_ruangan',
        'jumlah_barang',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi_kerusakan',
        'deskripsi_perbaikan',
        'foto_sebelum',
        'foto_sesudah',
        'catatan',
        'status',
    ];

    protected $casts = [
        'foto_sebelum'    => 'array',
        'foto_sesudah'    => 'array',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function inventarisRuangan()
    {
        return $this->belongsTo(InventarisRuangan::class, 'id_inventaris_ruangan', 'id_inventaris_ruangan');
    }
}