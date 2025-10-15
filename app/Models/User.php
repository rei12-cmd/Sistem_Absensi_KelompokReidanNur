<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // -----------------------------------------------------------------
    // Relasi ke model lain (ditambahkan agar $user->guru / siswa / wali bekerja)
    // -----------------------------------------------------------------

    /**
     * Relasi one-to-one ke tabel guru.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function guru()
    {
        return $this->hasOne(\App\Models\Guru::class, 'user_id');
    }

    /**
     * Relasi one-to-one ke tabel siswa.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function siswa()
    {
        return $this->hasOne(\App\Models\Siswa::class, 'user_id');
    }

    /**
     * Relasi one-to-one ke tabel wali.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wali()
    {
        return $this->hasOne(\App\Models\Wali::class, 'user_id');
    }
}
