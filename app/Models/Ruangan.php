<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    //
    protected $table = 'tabel_ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $fillable = ['nama_ruangan', 'deskripsi_ruangan'];
    public $timestamps = true;

    //Relasi
    public function inventarisRuangan() {
        return $this->hasMany(InventarisRuangan::class, 'id_ruangan', 'id_ruangan');
    }
}
