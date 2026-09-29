# Phase 1 — Setup Project

## Tujuan
Menyiapkan fondasi project: framework, styling, autentikasi dasar, dan
version control, sebagai dasar seluruh pengembangan berikutnya.

## Stack yang digunakan
| Komponen | Versi | Catatan |
|---|---|---|
| PHP | 8.4+ | minimum requirement Laravel 13 |
| Laravel | 13.x | rilis Maret 2026 |
| Livewire | 4.x | gaya komponen: **MFC** (class + view terpisah), bukan SFC default |
| Tailwind CSS | 4.x | via plugin resmi `@tailwindcss/vite`, bukan `tailwind.config.js` lama |
| Spatie Laravel Permission | terbaru | role & permission |
| Database | MySQL |

## Langkah yang dilakukan
1. https://laravel.com/framework/docs `composer global require laravel/installer`
2. laravel new example-app
3. cd example-app
   npm install && npm run build
   composer run dev
4. Buat database `nama_db`, set `.env`, jalankan `php artisan migrate` (tes koneksi)
5. `composer require livewire/livewire:^4.0` — tambahkan `@livewireStyles` /
   `@livewireScripts` ke layout
6. `composer require spatie/laravel-permission` — publish migration & config,
   tambahkan trait `HasRoles` ke model `User`
7. menggunakan starter-kit livewire 'composer require livewire/livewire'
   untuk scaffolding login/register
8. `git init`, commit awal, push ke repository

## Keputusan teknis
- **Livewire MFC dipilih (bukan SFC default v4)** karena lebih mudah dijelaskan
  di bab metodologi laporan KP — pemisahan class (logic) dan view (tampilan)
  sudah familiar dari pola MVC yang diajarkan di kuliah.
- **Palet warna**: Primary `#F4D000` (kuning), Dark `#1F1F1F`, didefinisikan
  sebagai token `@theme` di Tailwind 4 (`--color-primary`, `--color-dark`, dst)
  supaya konsisten dipakai lewat utility class (`bg-primary`, `text-dark`).

## Cara verifikasi
```bash
php artisan serve
```
Buka `http://localhost:8000` — halaman default Laravel/Breeze harus tampil
tanpa error, dan `php artisan migrate:status` menunjukkan semua migration
bawaan Laravel + Livewire + Spatie berstatus `Ran`.
1. halaman / setup
![alt text](<laravel13.png>)
2. login /login setup
![alt text](<login.png>)
3. Register /register
![alt text](<registrasi.png>)
4. dashboard /dasboard 
![alt text](<dashboard.png>)