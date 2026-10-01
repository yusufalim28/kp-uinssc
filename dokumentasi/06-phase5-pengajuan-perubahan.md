# Phase 5 — Pengajuan Perubahan Jadwal

## Tujuan
Memungkinkan dosen mengajukan perubahan jadwal praktikum yang diampunya,
dan operator/admin meninjau (setuju/tolak) pengajuan tersebut — fitur
terakhir dari cakupan utama PRD.

## Catatan penting: perubahan stack autentikasi
Diketahui bahwa project ini pakai **Livewire Starter Kit resmi** (Laravel +
Livewire 4 + Flux UI), bukan Laravel Breeze seperti asumsi awal. Setelah
dicek, dampaknya minim karena:
- Starter kit tetap Livewire 4, tanpa `Kernel.php` (pakai `bootstrap/app.php`)
  — sama seperti asumsi Phase 1
- Route `home`, `dashboard`, dan `require __DIR__.'/auth.php'` punya
  struktur yang identik dengan yang sudah dipakai sejak awal
- `route('login')` / `route('logout')` tetap valid dipanggil by name

Yang perlu diperbaiki: starter kit resmi sudah membawa 3 route bawaan
(`settings.profile`, `settings.password`, `settings.appearance`) sejak
sebelum Phase 1. Karena `routes/web.php` ditimpa penuh di beberapa phase
sebelumnya, route bawaan ini kemungkinan sempat hilang. Phase 5 ini
menyertakan `routes/web.php` final yang sudah menggabungkan semuanya.

## Langkah yang dilakukan
1. Migration `add_user_id_to_dosen_table` — menautkan tabel `dosen` ke
   `users` (dibutuhkan karena sekarang dosen login sendiri, bukan cuma
   data referensi)
2. `app/Livewire/Dosen/PengajuanSaya.php` + view — dosen ajukan perubahan
   untuk jadwal yang diampunya, dan lihat riwayat pengajuan sendiri
3. `app/Livewire/Admin/PengajuanIndex.php` + view — operator/admin
   filter per status, approve atau reject dengan catatan wajib
4. Route `/pengajuan-saya` (role dosen) dan `/pengajuan` (role
   super-admin|operator)
5. `routes/web.php` final — menggabungkan ulang route publik, route
   bawaan starter kit, master data, jadwal, dan pengajuan dalam 1 file

## File yang dibuat
```
database/migrations/2026_09_30_032221_add_user_id_to_dosen_table.php
app/Livewire/Dosen/PengajuanSaya.php                  + view
app/Livewire/Admin/PengajuanIndex.php                 + view
routes/web.php                                          (final, gabungan)
```

## Keputusan teknis
- **Approve tidak otomatis mengubah `jadwal_praktikum`.** Field
  `perubahan` di tabel `pengajuan_perubahan` adalah teks bebas (bukan
  field terstruktur per kolom), jadi mem-parsingnya otomatis berisiko
  salah. Approve di sini hanya mengubah status jadi `disetujui`;
  operator menerapkan perubahan sebenarnya secara manual lewat
  `/jadwal-manage`, supaya tetap melalui `JadwalConflictService` dan
  tidak menimbulkan bentrok baru.
- **Reject mewajibkan catatan** (`catatan_penolakan`) — dosen harus tahu
  alasan pengajuannya ditolak, bukan cuma status berubah tanpa konteks.
- **Relasi Dosen ↔ User lewat `user_id` nullable** — nullable karena
  tidak semua data Dosen di Master Data tentu punya akun login (dosen
  luar/tamu misalnya), tapi kalau `null`, dosen itu tidak akan bisa
  mengajukan perubahan sendiri.

## Cara verifikasi
```bash
php artisan migrate
php artisan tinker
>>> $user = App\Models\User::where('email','dosen1@kampus.ac.id')->first();
>>> App\Models\Dosen::where('email','dosen1@kampus.ac.id')->first()->update(['user_id' => $user->id]);
>>> $user->assignRole('dosen');
```
- Login sebagai dosen → `/pengajuan-saya` → ajukan perubahan → status
  "Menunggu"
- Login sebagai admin → `/pengajuan` → tolak dengan catatan → login lagi
  sebagai dosen → catatan penolakan harus tampil
- Ulangi dengan approve → status "Disetujui", lalu cek manual di
  `/jadwal-manage` bahwa perubahan diterapkan tanpa memicu bentrok baru

## Pesan commit yang disarankan
```
feat(pengajuan): fitur pengajuan perubahan jadwal (dosen & approval)

- Tambah migration user_id di tabel dosen (link ke akun login)
- Tambah PengajuanSaya (dosen): ajukan & lihat riwayat pengajuan
- Tambah PengajuanIndex (admin/operator): filter status, approve/reject
  dengan catatan penolakan wajib
- Gabungkan ulang routes/web.php: publik + settings bawaan starter kit
  + master data + jadwal + pengajuan dalam satu file
```
