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
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleGuru  = Role::firstOrCreate(['name' => 'guru']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa']);
        $roleWali  = Role::firstOrCreate(['name' => 'wali']);

        $jurusan1 = Jurusan::firstOrCreate(['nama'=>'PPLG']);
        $jurusanList = [$jurusan1];
        for($i=2;$i<=5;$i++){
            $jurusanList[] = Jurusan::firstOrCreate(['nama'=>"Jurusan $i"]);
        }

        $kelasList = [];
        foreach($jurusanList as $jurusan){
            for($k=1;$k<=6;$k++){
                $kelasList[] = Kelas::firstOrCreate(['nama'=>"Kelas $k {$jurusan->nama}", 'jurusan_id'=>$jurusan->id]);
            }
        }

        $mapelNames = ['Matematika','Bahasa Indonesia','Bahasa Inggris','Fisika','Kimia','Biologi','Sejarah','PKN','Seni Budaya','PJOK','Simulasi Digital','Ekonomi','Geografi'];
        $mapelList = [];
        foreach($mapelNames as $nama){
            $mapelList[] = MataPelajaran::firstOrCreate(['nama'=>$nama]);
        }

        $adminUser = User::firstOrCreate(
            ['email' => 'admin1@gmail.com'],
            ['username' => 'admin1', 'password' => Hash::make('admin1')]
        );
        $adminUser->assignRole($roleAdmin);

        $guruUser1 = User::firstOrCreate(
            ['email' => 'guru1@gmail.com'],
            ['username' => 'guru1', 'password' => Hash::make('guru1')]
        );
        $guruUser1->assignRole($roleGuru);
        $guru1 = Guru::firstOrCreate(
            ['user_id' => $guruUser1->id],
            ['nip' => '19800101', 'nama' => 'Pak Budi', 'telepon' => '08123456789']
        );

        $kelasSiswa1 = $kelasList[0];
        $siswaUser1 = User::firstOrCreate(
            ['email' => 'siswa1@gmail.com'],
            ['username' => 'siswa1', 'password' => Hash::make('siswa1')]
        );
        $siswaUser1->assignRole($roleSiswa);
        $siswa1 = Siswa::firstOrCreate(
            ['user_id' => $siswaUser1->id],
            [
                'kelas_id' => $kelasSiswa1->id,
                'jurusan_id' => $kelasSiswa1->jurusan_id,
                'nis' => '2025001',
                'nama' => 'Andi',
                'tanggal_lahir' => '2010-05-10',
                'alamat' => 'Jl. Merdeka No.1'
            ]
        );

        $waliUser1 = User::firstOrCreate(
            ['email' => 'wali1@gmail.com'],
            ['username' => 'wali1', 'password' => Hash::make('wali1')]
        );
        $waliUser1->assignRole($roleWali);
        $wali1 = Wali::firstOrCreate(
            ['user_id' => $waliUser1->id],
            ['nama' => 'Ayah Andi', 'telepon' => '08987654321', 'alamat' => 'Jl. Merdeka No.1']
        );

        if(!DB::table('wali_siswa')->where('wali_id',$wali1->id)->where('siswa_id',$siswa1->id)->exists()){
            DB::table('wali_siswa')->insert([
                'wali_id'=>$wali1->id,
                'siswa_id'=>$siswa1->id,
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }

        $guruList = [$guru1];
        for($i=2;$i<=30;$i++){
            $user = User::firstOrCreate(
                ['email'=>"guru$i@gmail.com"],
                ['username'=>"guru$i",'password'=>Hash::make("guru$i")]
            );
            $user->assignRole($roleGuru);
            $guruList[] = Guru::firstOrCreate(
                ['user_id'=>$user->id],
                ['nip'=>'NIP'.str_pad($i,5,'0',STR_PAD_LEFT),'nama'=>$faker->name(),'telepon'=>$faker->phoneNumber()]
            );
        }

        $siswaList = [$siswa1];
        $waliList = [$wali1];
        for($i=2;$i<=100;$i++){
            $userSiswa = User::firstOrCreate(
                ['email'=>"siswa$i@gmail.com"],
                ['username'=>"siswa$i",'password'=>Hash::make("siswa$i")]
            );
            $userSiswa->assignRole($roleSiswa);

            $kelas = $faker->randomElement($kelasList);
            $siswa = Siswa::firstOrCreate(
                ['user_id'=>$userSiswa->id],
                [
                    'kelas_id'=>$kelas->id,
                    'jurusan_id'=>$kelas->jurusan_id,
                    'nis'=>'NIS'.str_pad($i,5,'0',STR_PAD_LEFT),
                    'nama'=>$faker->name(),
                    'tanggal_lahir'=>$faker->date('Y-m-d','2012-01-01'),
                    'alamat'=>$faker->address()
                ]
            );
            $siswaList[] = $siswa;

            $userWali = User::firstOrCreate(
                ['email'=>"wali$i@gmail.com"],
                ['username'=>"wali$i",'password'=>Hash::make("wali$i")]
            );
            $userWali->assignRole($roleWali);

            $wali = Wali::firstOrCreate(
                ['user_id'=>$userWali->id],
                ['nama'=>$faker->name('male'),'telepon'=>$faker->phoneNumber(),'alamat'=>$faker->address()]
            );
            $waliList[] = $wali;

            if(!DB::table('wali_siswa')->where('wali_id',$wali->id)->where('siswa_id',$siswa->id)->exists()){
                DB::table('wali_siswa')->insert([
                    'wali_id'=>$wali->id,
                    'siswa_id'=>$siswa->id,
                    'created_at'=>now(),
                    'updated_at'=>now()
                ]);
            }
        }

        $gmkList = [];
        foreach($guruList as $guru){
            $mapelSample = $faker->randomElements($mapelList,rand(2,4));
            $kelasSample = $faker->randomElements($kelasList,rand(2,4));
            foreach($mapelSample as $mapel){
                foreach($kelasSample as $kelas){
                    $gmkList[] = DB::table('guru_mapel_kelas')->insertGetId([
                        'guru_id'=>$guru->id,
                        'mata_pelajaran_id'=>$mapel->id,
                        'kelas_id'=>$kelas->id,
                        'created_at'=>now(),
                        'updated_at'=>now()
                    ]);
                }
            }
        }

        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat'];
        $jadwalList = [];
        foreach($gmkList as $gmkId){
            $jadwalList[] = Jadwal::firstOrCreate([
                'guru_mapel_kelas_id'=>$gmkId,
                'hari'=>$faker->randomElement($hariList),
                'jam_mulai'=>$faker->time('H:i:s','08:00:00'),
                'jam_selesai'=>$faker->time('H:i:s','15:00:00'),
                'ruang'=>'Ruang '.rand(101,150)
            ]);
        }

        $statusList = ['H','I','S','A'];
        foreach($siswaList as $siswa){
            $jadwalSample = $faker->randomElements($jadwalList,rand(3,7));
            foreach($jadwalSample as $jadwal){
                if(!DB::table('absensi')->where('jadwal_id',$jadwal->id)->where('siswa_id',$siswa->id)->exists()){
                    DB::table('absensi')->insert([
                        'jadwal_id'=>$jadwal->id,
                        'siswa_id'=>$siswa->id,
                        'tanggal'=>$faker->dateTimeThisMonth()->format('Y-m-d'),
                        'status'=>$faker->randomElement($statusList),
                        'keterangan'=>null,
                        'created_at'=>now(),
                        'updated_at'=>now()
                    ]);
                }
            }
        }
    }
}
