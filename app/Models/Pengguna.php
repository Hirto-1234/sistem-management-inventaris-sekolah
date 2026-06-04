<?php

namespace App\Models;

use App\Notifications\ResetPasswordKustom;
use App\Notifications\VerifikasiEmailKustom;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable implements MustVerifyEmail
{
    //
    use Notifiable;

    protected $table = 'tabel_pengguna';
    protected $primaryKey = 'id_pengguna';
    protected $fillable = [
        'nama_pengguna', 
        'email',
        'password_pengguna', 
        'role_pengguna', 
        'nama_lengkap', 
        'foto_pengguna',
        'email_verified_at'
    ];
    public $timestamps = true;

    //Relasi
    public function peminjaman() {
        return $this->hasMany(Peminjaman::class, 'id_pengguna', 'id_pengguna');
    }
    public function inventarisRuangan() {
        return $this->hasMany(InventarisRuangan::class, 'id_pengguna', 'id_pengguna');
    }
    public function perbaikan() {
        return $this->hasMany(Perbaikan::class, 'id_pengguna', 'id_pengguna');
    }
    public function getNamaAttribute()
    {
        return $this->nama_pengguna;
    }
    public function getAuthPassword()
    {
        return $this->password_pengguna;
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifikasiEmailKustom);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordKustom($token));
    }

}
