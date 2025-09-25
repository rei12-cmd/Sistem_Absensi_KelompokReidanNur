<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $fillable = [
        'guru_mapel_kelas_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruang'
    ];

    public function guruMapelKelas()
    {
        return $this->belongsTo(GuruMapelKelas::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
