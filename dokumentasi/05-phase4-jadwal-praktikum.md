# Phase 4 — CRUD Jadwal Praktikum + Validasi Bentrok

## Tujuan
Mengimplementasikan fitur inti sistem: membuat, mengubah, dan menghapus
jadwal praktikum, dengan validasi otomatis supaya ruang dan dosen tidak
terjadwal bentrok pada waktu yang sama.

## Langkah yang dilakukan
1. Buat `app/Services/JadwalConflictService.php` — sebelumnya di Phase 3
   Bagian 1 baru berupa contoh kode di dokumentasi, sekarang dijadikan
   file nyata dengan 2 method: `cekBentrokRuang()` dan `cekBentrokDosen()`
2. Buat `app/Livewire/Admin/JadwalManage.php` + view — form dengan 5
   dropdown relasi (Mata Kuliah, Kelas, Dosen, Ruang, Tahun Akademik)
3. Integrasikan `JadwalConflictService` ke method `save()`: validasi field
   dulu → cek bentrok ruang → cek bentrok dosen → baru simpan
4. Tambah route `/jadwal-manage` di grup `role:super-admin|operator`
5. Tulis `tests/Feature/JadwalConflictServiceTest.php` — 4 test case
   mereplikasi skenario di PRD section 21

## File yang dibuat
```
app/Services/JadwalConflictService.php
app/Livewire/Admin/JadwalManage.php
resources/views/livewire/admin/jadwal-manage.blade.php
tests/Feature/JadwalConflictServiceTest.php
routes/web.php                                    (edit — 1 route baru)
```

## Keputusan teknis
- **Service mengembalikan `?JadwalPraktikum`, bukan `bool`** — supaya saat
  bentrok terdeteksi, pesan error bisa menyebutkan jadwal siapa yang
  bentrok ("Ruang sudah dipakai untuk Praktikum Basis Data oleh
  Bpk. Ahmad, 08:00–10:00"), bukan cuma "bentrok" tanpa konteks.
- **Parameter `excludeId`** di kedua method — mencegah jadwal yang sedang
  diedit dianggap bentrok dengan dirinya sendiri saat hanya mengubah
  field non-jam (mis. keterangan).
- **Urutan pengecekan**: validasi field dulu (format jam, field wajib) →
  baru cek bentrok. Ini menghindari query database yang sia-sia kalau
  input dasarnya saja belum valid.
- **`wire:loading` pada tombol Simpan** — menampilkan teks "Mengecek
  bentrok..." saat request berjalan, supaya pengguna tahu sistem sedang
  memproses, bukan macet.

## Ketergantungan tambahan
```php
// app/Models/JadwalPraktikum.php
public function pengajuanPerubahan()
{
    return $this->hasMany(PengajuanPerubahan::class);
}
```
Dipakai untuk mencegah hapus jadwal yang masih punya riwayat pengajuan
perubahan.

## Cara verifikasi
```bash
php artisan test --filter=JadwalConflictServiceTest
```
4 test harus **pass**. Lalu tes manual:
```bash
php artisan serve
```
- Buat jadwal Senin 08:00–10:00 di Ruang A dengan Dosen X
- Buat jadwal ke-2: Ruang A, Senin 09:00–11:00 → harus ditolak dengan
  pesan bentrok ruang
- Ganti ruang ke Ruang B, dosen tetap Dosen X → harus tetap ditolak
  (bentrok dosen, bukan ruang)
- Ganti dosen ke Dosen Y → baru berhasil tersimpan

## Pesan commit yang disarankan
```
feat(jadwal): CRUD jadwal praktikum dengan validasi bentrok otomatis

- Tambah JadwalConflictService (cekBentrokRuang, cekBentrokDosen)
- Tambah JadwalManage Livewire component: form 5 relasi + validasi
  bentrok dipanggil sebelum simpan
- Tambah 4 test otomatis untuk skenario bentrok/tidak bentrok
- Tambah relasi JadwalPraktikum::pengajuanPerubahan() untuk proteksi hapus
```
