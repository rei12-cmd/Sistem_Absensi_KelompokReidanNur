# Sistem_Absensi-_KelompokReidanNur
KP_Sistem_Absensi 

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