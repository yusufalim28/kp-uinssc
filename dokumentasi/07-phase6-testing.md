# Phase 6 — Testing Otomatis Semua Modul

## Tujuan
Menutup kesenjangan testing: sebelumnya hanya `JadwalConflictService` yang
punya test otomatis (Phase 4). Phase ini menambahkan test untuk seluruh
modul yang sudah dibangun — Authorization, 7 Master Data, Jadwal, dan
Pengajuan Perubahan — supaya ada bukti nyata kualitas sistem, bukan cuma
"kelihatan jalan" saat dites manual di browser.

## Langkah yang dilakukan
1. Tambah 8 Factory (`Fakultas`, `ProgramStudi`, `Kelas`, `MataKuliah`,
   `Dosen`, `RuangPraktikum`, `TahunAkademik`, `JadwalPraktikum`) — dasar
   untuk semua test berikutnya
2. Tambah helper global `loginAsRole()` di `tests/Pest.php` — hindari
   duplikasi kode "buat user, assign role, login" di tiap file test
3. `RoleMiddlewareTest` — 7 test memastikan middleware `role:` benar-benar
   memblokir akses sesuai role (bukan cuma menyembunyikan menu di sidebar)
4. 7 file test Master Data (`FakultasTest`, dst) — masing-masing menguji:
   create sukses, validasi gagal (field unik/required/range), dan
   delete-guard (tidak bisa hapus data yang masih punya relasi anak)
5. `JadwalManageTest` — 4 test: create tanpa bentrok, bentrok ruang
   ditolak dengan pesan error, edit tanpa menganggap diri sendiri
   bentrok, delete-guard terhadap riwayat pengajuan
6. `PengajuanSayaTest` & `PengajuanIndexTest` — 8 test total: alur
   dosen mengajukan, validasi, isolasi data antar-dosen, dan alur
   operator approve/reject termasuk validasi catatan penolakan wajib

## File yang dibuat
```
database/factories/*.php                          (8 file)
tests/Feature/Authorization/RoleMiddlewareTest.php
tests/Feature/MasterData/*.php                     (7 file)
tests/Feature/Jadwal/JadwalManageTest.php
tests/Feature/Pengajuan/PengajuanSayaTest.php
tests/Feature/Pengajuan/PengajuanIndexTest.php
tests/Pest.php                                     (edit — tambah helper)
```

## Keputusan teknis
- **Test lewat `Livewire::test()`, bukan HTTP request biasa** — karena
  mayoritas fitur ada di Livewire component, test lewat Livewire test
  helper bisa langsung memanggil method (`->call('save')`) dan assert
  state komponen (`->assertSet(...)`), lebih presisi daripada cuma
  assert status HTTP.
- **Delete-guard diuji secara eksplisit di tiap modul** — bukan cuma
  dipercaya "pasti jalan karena pola yang sama", supaya kalau ada
  yang lupa menambahkan relasi `hasMany` saat replikasi modul baru,
  test yang akan gagal duluan, bukan ditemukan user saat produksi.
- **`RoleMiddlewareTest` menguji level HTTP (`$this->get(...)`)**, bukan
  level komponen — karena yang diuji di sini adalah middleware di
  routing, bukan logika di dalam komponennya.
- **SQLite in-memory untuk testing** (lihat `CARA_PAKAI.md`) — supaya
  tiap test run dari kondisi kosong tanpa menyentuh database development.

## Cara verifikasi
```bash
php artisan test
```
Semua ~38 test (4 dari Phase 4 + 34 baru) harus **pass**. Kalau ada yang
gagal, baca pesan errornya dulu — kemungkinan besar penyebabnya salah satu
dari dua hal: trait `HasFactory` belum ditambahkan ke model, atau relasi
`hasMany` untuk delete-guard belum ada (lihat `RELASI_MODEL_WAJIB_ADA.md`
dari Phase 3 Lanjutan).

## Pesan commit yang disarankan
```
test: tambah test otomatis untuk Authorization, Master Data, Jadwal, Pengajuan

- 8 Factory untuk seluruh model
- RoleMiddlewareTest: pastikan middleware role memblokir sesuai role
- 7 test CRUD Master Data: create, validasi, delete-guard
- JadwalManageTest: create, bentrok ruang, edit non-bentrok, delete-guard
- PengajuanSayaTest + PengajuanIndexTest: alur dosen & approval operator
- Tambah helper loginAsRole() di tests/Pest.php
```
