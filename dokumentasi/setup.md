# Panduan Setup Sistem Informasi Manajemen

Dokumen ini menjelaskan cara menyiapkan aplikasi secara lokal untuk pengembangan.

## 1. Prasyarat

Pastikan perangkat sudah memiliki:

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- Git
- Ekstensi PHP yang dibutuhkan Laravel, termasuk `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, dan `fileinfo`

Versi dependency utama aplikasi:

- Laravel 13
- Livewire 3
- Vite 8
- Tailwind CSS 4

Periksa instalasi dengan perintah berikut:

```powershell
php --version
composer --version
node --version
npm --version
git --version
```

## 2. Masuk ke folder proyek

Buka PowerShell pada folder proyek:

```powershell
cd "D:\Kerja Praktek PUSTIKOM\kp-sistem-jadwal-praktikum"
```

Jika proyek diperoleh dari repository Git, gunakan:

```powershell
git clone <URL_REPOSITORY>
cd kp-sjp
```

## 3. Instalasi dependency

Instal dependency PHP dan JavaScript:

```powershell
composer install
npm install
```

## 4. Konfigurasi environment

Salin file konfigurasi contoh menjadi `.env`:

```powershell
Copy-Item .env.example .env
```

Buat application key:

```powershell
php artisan key:generate
```

Jangan commit file `.env` karena dapat berisi konfigurasi lokal dan informasi sensitif.

## 5. Konfigurasi database

### Opsi default: SQLite

Aplikasi menggunakan SQLite secara default. Pastikan file database tersedia:

```powershell
New-Item -ItemType File -Path database\database.sqlite -Force
```

Periksa bagian database pada `.env`:

```dotenv
DB_CONNECTION=sqlite
```

Jalankan migrasi:

```powershell
php artisan migrate
```

Jika ingin mengisi data awal dan `DatabaseSeeder` sudah memiliki data seed, jalankan:

```powershell
php artisan db:seed
```

### Opsi MySQL

Jika menggunakan MySQL, ubah konfigurasi database pada `.env` sesuai database lokal:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

Buat database tersebut terlebih dahulu, kemudian jalankan:

```powershell
php artisan migrate
```

## 6. Menyiapkan asset frontend

Untuk development, jalankan Vite:

```powershell
npm run dev
```

Untuk membuat asset production:

```powershell
npm run build
```

## 7. Menjalankan aplikasi

Buka terminal baru, lalu jalankan server Laravel:

```powershell
php artisan serve
```

Akses aplikasi melalui:

```text
http://localhost:8000
```
![alt text](<laravel13.png>)

Saat mengembangkan frontend, biarkan `npm run dev` tetap berjalan di terminal terpisah agar perubahan asset dimuat otomatis.

Alternatifnya, gunakan script development yang tersedia di `composer.json`:

```powershell
composer run dev
```

## 8. Menjalankan test dan pemeriksaan kode

Jalankan test:

```powershell
php artisan test
```

Jalankan formatter PHP:

```powershell
composer run lint
```

Jalankan pemeriksaan tipe:

```powershell
composer run types:check
```

Jalankan seluruh pemeriksaan CI lokal:

```powershell
composer run ci:check
```

## 9. Perintah Laravel yang sering digunakan

```powershell
php artisan route:list
php artisan migrate:status
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan storage:link
```

Gunakan `php artisan optimize:clear` setelah mengubah konfigurasi atau route jika aplikasi masih menggunakan cache lama.

## 10. Troubleshooting

### Application key belum tersedia

Jalankan kembali:

```powershell
php artisan key:generate
```

### Perubahan frontend tidak terlihat

Pastikan Vite sedang berjalan:

```powershell
npm run dev
```

Atau buat asset production:

```powershell
npm run build
```

### Database tidak dapat diakses

Pastikan `DB_CONNECTION` pada `.env` sesuai dengan database yang digunakan. Untuk SQLite, pastikan file `database/database.sqlite` ada. Setelah mengubah `.env`, jalankan:

```powershell
php artisan config:clear
```

### Migrasi perlu diulang dari awal

> Perintah ini menghapus seluruh tabel dan data pada database yang dikonfigurasi. Gunakan hanya pada lingkungan development.

```powershell
php artisan migrate:fresh
```

Dengan seed:

```powershell
php artisan migrate:fresh --seed
```

## 11. Alur setup cepat

Pada instalasi baru, perintah berikut dapat digunakan:

```powershell
composer run setup
npm run dev
```

Script `composer run setup` akan memasang dependency Composer, membuat `.env` jika belum ada, membuat application key, menjalankan migrasi, memasang dependency npm, dan membuat asset frontend production.


![alt text](<registrasi.png>)
![alt text](<login.png>)
![alt text](<dashboard.png>)