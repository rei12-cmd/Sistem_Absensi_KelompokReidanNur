# Sistem_Absensi-_KelompokReidanNur
KP_Sistem_Absensi 

## Roadmap Project
1. Migrasi Database dan Seeder - Selesai 
2. Setup User & Role (admin, guru, siswa, wali) – Selesai
3. Penerapan Konsep UI/UX - Selesai
4. CRUD Jurusan - Selesai
5. CRUD Kelas - Selesai
6. CRUD Guru - revisi
7. CRUD Mata Pelajaran - selesai 
8. Relasi Guru–Mapel–Kelas (pivot) - perlu cek lagi
9. CRUD Jadwal - selesai 
10. CRUD Siswa (terhubung ke kelas & jurusan) - revisi
11. CRUD Wali & Relasi Wali–Siswa - revisi
12. Absensi (input guru berdasarkan jadwal)
13. Laporan Absensi (akses sesuai role: admin, guru, siswa, wali) on progress
14. Dashboard
15. Checking All
16. Revisi (Jika diperlukan)
    - revisi fitur bagian wali, ketika tambah data wali datanya tidak tersimpan, fitur editnya dan hapus sudah jalan (admin) -> Clear
    - revisi fitur bagian jadwal ketika edit jamnya error (admin) -> Clear
    - buat profil tiap role -> tidak ada juga tidak apa2, bisa ditambahkan jika waktunya masih ada
    - buat tampilan dashboard -> Clear(Minimalis)
    - harus menambahkan paginate tiap bagian seperti siswa, guru, dan sebagainya -> Clear
    - dan seterusnya
    - fitur guru ketika tambah data harusnya ke nomor terakhir bukan di no awal (tambahkan get) 
    - fitur tambah wali ketika tambah data wali setelah mengisi form dan memencet tombol simpan , datanya tidak tesimpan di laptop saya entah kenapa tidak tersimpan terus. 
    - fitur siswa ketika tambah data harusnya ke nomor terakhir bukan di no awal
    - laporan absen tinggal nambahin presentase kehadiran
    - fitur atur mengajar tambahkan paginate, dirapihkan lagi tombolnya 
    - bagian (guru) fitur absensi kenapa tidak ada data siswa ketika akan mengabsen siswa padahal sesuai dengan jam jadwalnya
    - pengembangan tampilan dashboard dan tampilan all role  
    - seterusnya
17. Selesai

## Catatan
```cmd
Jangan ubah apapun yang sudah jalan
```

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
