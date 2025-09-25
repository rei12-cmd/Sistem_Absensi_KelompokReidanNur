<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruMapelKelas extends Model
{
    protected $table = 'guru_mapel_kelas';
    protected $fillable = ['guru_id', 'mata_pelajaran_id', 'kelas_id'];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }
}
