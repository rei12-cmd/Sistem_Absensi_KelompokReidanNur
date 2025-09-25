<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $fillable = [
        'user_id', 'kelas_id', 'jurusan_id',
        'nis', 'nama', 'tanggal_lahir', 'alamat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function wali()
    {
        return $this->belongsToMany(Wali::class, 'wali_siswa');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
