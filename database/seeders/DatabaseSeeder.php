<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Wali;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Buat Role ---
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleGuru  = Role::firstOrCreate(['name' => 'guru']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa']);
        $roleWali  = Role::firstOrCreate(['name' => 'wali']);

        // --- Admin ---
        $adminUser = User::create([
            'username' => 'admin1',
            'email'    => 'admin1@gmail.com',
            'password' => Hash::make('admin1'),
        ]);
        $adminUser->assignRole($roleAdmin);

        // --- Jurusan ---
        $jurusan = Jurusan::create(['nama' => 'PPLG']);

        // --- Kelas ---
        $kelas = Kelas::create([
            'nama'       => 'X PPLG 1',
            'jurusan_id' => $jurusan->id,
        ]);

        // --- Mata Pelajaran ---
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);

        // --- Guru ---
        $guruUser = User::create([
            'username' => 'guru1',
            'email'    => 'guru1@gmail.com',
            'password' => Hash::make('guru1'),
        ]);
        $guruUser->assignRole($roleGuru);

        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'nip'     => '19800101',
            'nama'    => 'Pak Budi',
            'telepon' => '08123456789',
        ]);

        // --- Siswa ---
        $siswaUser = User::create([
            'username' => 'siswa1',
            'email'    => 'siswa1@gmail.com',
            'password' => Hash::make('siswa1'),
        ]);
        $siswaUser->assignRole($roleSiswa);

        $siswa = Siswa::create([
            'user_id'       => $siswaUser->id,
            'kelas_id'      => $kelas->id,
            'jurusan_id'    => $jurusan->id,
            'nis'           => '2025001',
            'nama'          => 'Andi',
            'tanggal_lahir' => '2010-05-10',
            'alamat'        => 'Jl. Merdeka No.1',
        ]);

        // --- Wali ---
        $waliUser = User::create([
            'username' => 'wali1',
            'email'    => 'wali1@gmail.com',
            'password' => Hash::make('wali1'),
        ]);
        $waliUser->assignRole($roleWali);

        $wali = Wali::create([
            'user_id' => $waliUser->id,
            'nama'    => 'Ayah Andi',
            'telepon' => '08987654321',
            'alamat'  => 'Jl. Merdeka No.1',
        ]);

        // --- Relasi Wali ↔ Siswa ---
        DB::table('wali_siswa')->insert([
            'wali_id'  => $wali->id,
            'siswa_id' => $siswa->id,
        ]);

        // --- Relasi Guru ↔ Mapel ↔ Kelas ---
        $guruMapelKelasId = DB::table('guru_mapel_kelas')->insertGetId([
            'guru_id'           => $guru->id,
            'mata_pelajaran_id' => $mapel->id,
            'kelas_id'          => $kelas->id,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // --- Jadwal ---
        $jadwal = Jadwal::create([
            'guru_mapel_kelas_id' => $guruMapelKelasId,
            'hari'        => 'Senin',
            'jam_mulai'   => '08:00:00',
            'jam_selesai' => '09:30:00',
            'ruang'       => 'Ruang 101',
        ]);

        // --- Absensi ---
        DB::table('absensi')->insert([
            'jadwal_id'  => $jadwal->id,
            'siswa_id'   => $siswa->id,
            'tanggal'    => now()->toDateString(),
            'status'     => 'H',
            'keterangan' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // --- Tambahan contoh data agar laporan tampil lebih lengkap ---
        $mapel2 = MataPelajaran::create(['nama' => 'Bahasa Indonesia']);
        $kelas2 = Kelas::create([
            'nama'       => 'X PPLG 2',
            'jurusan_id' => $jurusan->id,
        ]);

        DB::table('guru_mapel_kelas')->insert([
            'guru_id'           => $guru->id,
            'mata_pelajaran_id' => $mapel2->id,
            'kelas_id'          => $kelas2->id,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Tambahan siswa di kelas lain
        $siswaUser2 = User::create([
            'username' => 'siswa2',
            'email'    => 'siswa2@gmail.com',
            'password' => Hash::make('siswa2'),
        ]);
        $siswaUser2->assignRole($roleSiswa);

        $siswa2 = Siswa::create([
            'user_id'       => $siswaUser2->id,
            'kelas_id'      => $kelas2->id,
            'jurusan_id'    => $jurusan->id,
            'nis'           => '2025002',
            'nama'          => 'Budi',
            'tanggal_lahir' => '2010-07-11',
            'alamat'        => 'Jl. Pelajar No.2',
        ]);
    }
}
