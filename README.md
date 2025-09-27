# Sistem_Absensi-_KelompokReidanNur
KP_Sistem_Absensi 

## Roadmap Project
1. Migrasi Database dan Seeder - Selesai 
2. Setup User & Role (admin, guru, siswa, wali) – Selesai
3. Penerapan Konsep UI/UX - Selesai
4. CRUD Jurusan - Selesai
5. CRUD Kelas - Selesai
6. CRUD Guru
7. CRUD Mata Pelajaran 
8. Relasi Guru–Mapel–Kelas (pivot)
9. CRUD Jadwal 
10. CRUD Siswa (terhubung ke kelas & jurusan)
11. CRUD Wali & Relasi Wali–Siswa 
12. Absensi (input guru berdasarkan jadwal)
13. Laporan Absensi (akses sesuai role: admin, guru, siswa, wali)
14. Checking All
15. Revisi (Jika diperlukan)
    - revisi fitur 1
    - revisi fitur 2
    - dan seterusnya
16. Selesai

## Jalankan Program
### Clone
Kalau sudah, bisa dilewat
```cmd
git clone https://github.com/rei12-cmd/Sistem_Absensi_KelompokReidanNur.git
```

### Pull (Wajib, tiap ada perubahan)
```cmd
git pull origin main
```

### Migrasi dan jalankan seeder
Jalankan migrasi setiap sudah melakukan pull
```cmd
php artisan migrate:fresh --seed
```

### Login
```cmd
Admin
email : admin1@gmail.com
password : admin1

-------------
Guru
email : guru1@gmail.com
password : guru1

-------------
Siswa
email : siswa1@gmail.com
password : siswa1

-------------
Wali/Orang Tua Siswa
email : wali1@gmail.com
password : wali1
```

## Simpan perubahan
Jika pengerjaan satu fitur sudah selesai, jangan lupa push ke github
```cmd
1. git add .
2. git commit -m "pesan commit" contoh git commit -m "update fitur jurusan"
3. git push origin main
4. selesai
```