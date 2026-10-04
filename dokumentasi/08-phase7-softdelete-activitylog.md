# Phase 7 — Soft Delete + Activity Log

## Tujuan
Menambah dua lapis keandalan data yang sebelumnya tidak ada: (1) hapus
data tidak lagi permanen secara langsung — bisa dipulihkan lewat halaman
Sampah, dan (2) setiap perubahan data (create/update/delete/restore)
tercatat otomatis — siapa, kapan, field apa yang berubah.

## Langkah yang dilakukan
1. Install `spatie/laravel-activitylog`, publish & jalankan migration
   bawaannya (tabel `activity_log`)
2. Migration tambahan: kolom `deleted_at` di 8 tabel (7 master data +
   `jadwal_praktikum`)
3. Tambahkan trait `SoftDeletes` ke 8 model — **tanpa mengubah kode CRUD
   manapun**, karena `->delete()` yang sudah ada otomatis jadi soft delete
4. Tambahkan trait `LogsActivity` ke 9 model (8 di atas + 
   `PengajuanPerubahan`) dengan `logOnly([...])` field spesifik per model
5. `app/Livewire/Admin/SampahIndex.php` + view — halaman gabungan yang
   menampilkan data ter-soft-delete dari 8 jenis tabel sekaligus, dengan
   aksi Pulihkan dan Hapus Permanen
6. `app/Livewire/Admin/ActivityLogIndex.php` + view — daftar riwayat
   aktivitas dengan detail field lama vs baru
7. Route `/sampah` dan `/log-aktivitas`, keduanya **khusus
   `role:super-admin`** (lebih ketat dari operator, karena data sensitif)

## File yang dibuat
```
database/migrations/2026_09_30_040000_add_soft_deletes_to_master_tables.php
app/Livewire/Admin/SampahIndex.php             + view
app/Livewire/Admin/ActivityLogIndex.php        + view
routes/web.php                                   (edit — 2 route baru)
resources/views/components/layouts/admin.blade.php (edit — 2 link sidebar)
9 model                                          (edit — tambah 2 trait)
```

## Keputusan teknis
- **Pakai package `spatie/laravel-activitylog`, bukan bikin sistem log
  sendiri** — package ini sudah matang (dipakai luas di ekosistem
  Laravel), otomatis mendeteksi `causer` (user yang login) tanpa setup
  tambahan, dan mencatat properti lama vs baru dalam format JSON yang
  gampang ditampilkan.
- **`SampahIndex` menggabungkan 8 jenis tabel dalam satu halaman**,
  bukan 8 halaman terpisah — pagination dilakukan manual di level
  `Collection` (gabungan query dari 8 model, diurutkan berdasarkan
  `deleted_at`, baru dipaginasi) karena Eloquent tidak bisa
  mem-paginate gabungan beberapa tabel berbeda secara native.
- **Route Sampah & Activity Log cuma `role:super-admin`**, bukan
  `super-admin|operator` seperti route Master Data lainnya — restore
  data terhapus dan melihat riwayat aktivitas adalah operasi sensitif
  yang sebaiknya tidak diberikan ke semua operator.
- **`wire:confirm` dipakai untuk Hapus Permanen** (bukan modal custom
  seperti di CRUD lain) — karena ini aksi sekunder yang jarang dipakai,
  dialog konfirmasi bawaan browser sudah cukup, tidak perlu komponen
  modal penuh.

## Cara verifikasi
- Hapus 1 data master → cek muncul di `/sampah` → Pulihkan → cek kembali
  normal di halaman asalnya
- Hapus lagi → Hapus Permanen → data hilang dari `/sampah`, tidak bisa
  dikembalikan
- Ubah data apa pun → cek `/log-aktivitas` → baris baru muncul dengan
  detail field lama vs baru dan nama user yang melakukan

## Pesan commit yang disarankan
```
feat(audit): soft delete + activity log untuk seluruh master data & jadwal

- Install spatie/laravel-activitylog
- Tambah kolom deleted_at di 8 tabel (migration)
- Tambah trait SoftDeletes + LogsActivity ke 9 model
- Halaman Sampah: restore & hapus permanen data ter-soft-delete
- Halaman Riwayat Aktivitas: audit trail siapa-ubah-apa-kapan
- Route /sampah & /log-aktivitas dibatasi role:super-admin
```
