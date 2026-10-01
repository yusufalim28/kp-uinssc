# Cara Pakai — Phase 5 (Pengajuan Perubahan Jadwal)

## 1. Tempatkan file
```
database/migrations/2026_09_30_032221_add_user_id_to_dosen_table.php
app/Livewire/Dosen/PengajuanSaya.php
resources/views/livewire/dosen/pengajuan-saya.blade.php
app/Livewire/Admin/PengajuanIndex.php
resources/views/livewire/admin/pengajuan-index.blade.php
routes/web.php   -> PENTING: baca dulu bagian "Route Bawaan Starter Kit"
                    di dalam file ini sebelum menimpa punya Anda
```

## 2. Jalankan migration baru
```bash
php artisan migrate
```

## 3. Relasi model yang wajib ada
```php
// app/Models/PengajuanPerubahan.php
public function jadwalPraktikum() { return $this->belongsTo(JadwalPraktikum::class); }
public function dosen()           { return $this->belongsTo(Dosen::class); }

// app/Models/Dosen.php — tambahan baru
public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}
```

## 4. Tautkan akun User dosen ke data Dosen

Karena dosen sekarang bisa login sendiri, hubungkan datanya:
```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'dosen1@kampus.ac.id')->first();
>>> $dosen = App\Models\Dosen::where('email', 'dosen1@kampus.ac.id')->first();
>>> $dosen->update(['user_id' => $user->id]);
>>> 
```

## 5. Tes alur lengkap

**Sebagai dosen:**
- Login, buka `/pengajuan-saya`
- Kalau muncul pesan kuning "belum terhubung ke data Dosen" → ulangi$user->assignRole('dosen');
  langkah 4
- Ajukan perubahan untuk salah satu jadwal → status awal "Menunggu"

**Sebagai operator/admin:**
- Buka `/pengajuan` → lihat pengajuan berstatus "Menunggu"
- Klik **Tolak** → wajib isi catatan → status berubah "Ditolak"
- Login lagi sebagai dosen → cek `/pengajuan-saya` → catatan penolakan
  harus muncul di kolom "Catatan Operator"
- Buat pengajuan baru, kali ini di sisi admin klik **Setujui** → status
  "Disetujui" — lalu **manual** buka `/jadwal-manage` dan terapkan
  perubahannya di sana (supaya tetap lewat `JadwalConflictService`)

## Kenapa approve tidak otomatis mengubah jadwal?

`pengajuan_perubahan.perubahan` adalah teks bebas ("pindah ke Selasa
13:00-15:00", "ganti ruang jadi Lab 2", dst), bukan field terstruktur
per-kolom. Mem-parsing teks bebas jadi field jadwal otomatis berisiko
salah interpretasi. Makanya approve di sini sengaja **hanya mengubah
status**, dan operator menerapkan perubahan sebenarnya lewat form
Kelola Jadwal yang sudah tervalidasi bentrok. Kalau nanti Anda mau
membuat form pengajuan jadi field terstruktur (dropdown hari/jam/ruang
baru, bukan teks bebas), kabari saya — bisa dibuatkan approve yang
langsung update jadwal otomatis.
