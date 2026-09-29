# Phase 3 (Bagian 1) — Backend Core

Bagian ini dikerjakan **sebelum** Frontend (Phase 2), karena UI membutuhkan
struktur data dan logika bisnis yang sudah stabil dan teruji.

## Tujuan
Membangun struktur database, model dengan relasi, data seeder, role/permission,
dan service validasi bentrok jadwal — seluruhnya diverifikasi lewat automated
test sebelum disentuh dari sisi UI.

## Struktur tabel (sesuai ERD)
```
fakultas ─┬─< program_studi ─┬─< kelas
          │                  └─< mata_kuliah ─┐
dosen ────┼──────────────────────────────────┤
ruang_praktikum ──────────────────────────────┼─< jadwal_praktikum ─< pengajuan_perubahan
tahun_akademik ───────────────────────────────┘
```
Urutan migration mengikuti dependensi FK: tabel tanpa FK dulu → tabel
berjenjang → tabel transaksi (`jadwal_praktikum`, `pengajuan_perubahan`).

## Langkah yang dilakukan
1. Migration: `fakultas`, `dosen`, `ruang_praktikum`, `tahun_akademik`
2. Migration: `program_studi` (FK `fakultas`), `kelas` & `mata_kuliah` (FK `program_studi`)
3. Migration: `jadwal_praktikum` (5 FK) & `pengajuan_perubahan` (FK `jadwal_praktikum`, `dosen`)
4. Model + relasi Eloquent untuk seluruh tabel di atas
5. `RolePermissionSeeder` — role: `super-admin`, `operator`, `dosen`; permission
   per modul (`manage-master-data`, `manage-jadwal`, `approve-pengajuan`,
   `submit-pengajuan`, `view-jadwal`)
6. Seeder dummy data master (fakultas, prodi, kelas, MK, dosen, ruang, tahun akademik)
7. `App\Services\JadwalConflictService` — `cekBentrokRuang()` dan `cekBentrokDosen()`
8. Pest/PHPUnit test untuk kasus bentrok & tidak bentrok

## Keputusan teknis
- **SKS dan Semester disimpan di `mata_kuliah`**, bukan di `jadwal_praktikum` —
  jadwal cukup mereferensikan `mata_kuliah_id`, nilai SKS/semester ikut otomatis
  lewat relasi (menghindari duplikasi data).
- **Logika bentrok dipisah ke Service class**, bukan ditulis langsung di
  controller/Livewire component, supaya bisa di-unit-test independen dan mudah
  dipakai ulang di form Create & Edit jadwal nanti.
- **Data ruang praktikum masih dummy** — menunggu konfirmasi data riil dari
  kampus/pembimbing (item TBD di PRD section 30).

## Cara verifikasi
```bash
php artisan migrate:fresh --seed
php artisan test        # atau: vendor/bin/pest
```
Semua test bentrok (overlap & non-overlap) harus **pass**. Cek juga lewat
`php artisan tinker`:
```php
App\Models\JadwalPraktikum::with('mataKuliah','dosen')->first();
```
harus mengembalikan data lengkap dengan relasi terisi (bukan `null`).
