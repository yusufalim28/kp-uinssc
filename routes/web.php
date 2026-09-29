<?php

use App\Livewire\Public\Home;
use App\Livewire\Public\DaftarJadwal;
use App\Livewire\Public\DetailJadwal;
use App\Livewire\Public\KalenderJadwal;
use App\Livewire\Admin\MasterData\FakultasIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Publik (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', Home::class)->name('home');
Route::get('/jadwal', DaftarJadwal::class)->name('jadwal.index');
Route::get('/jadwal/{jadwal}', DetailJadwal::class)->name('jadwal.detail');
Route::get('/kalender', KalenderJadwal::class)->name('jadwal.kalender');



// Route Terproteksi
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Master Data — hanya super-admin & operator
    Route::middleware('role:super-admin|operator')->group(function () {
        Route::get('/master/fakultas', FakultasIndex::class)->name('fakultas.index');

        // Pola yang sama persis dipakai untuk 6 master data lain,
        // tinggal buat komponennya mengikuti pola FakultasIndex:
        // Route::get('/master/prodi', ProdiIndex::class)->name('prodi.index');
        // Route::get('/master/kelas', KelasIndex::class)->name('kelas.index');
        // Route::get('/master/mata-kuliah', MataKuliahIndex::class)->name('matakuliah.index');
        // Route::get('/master/dosen', DosenIndex::class)->name('dosen.index');
        // Route::get('/master/ruang', RuangIndex::class)->name('ruang.index');
        // Route::get('/master/tahun-akademik', TahunAkademikIndex::class)->name('tahunakademik.index');

        // Route::get('/jadwal-manage', JadwalManage::class)->name('jadwal.manage');
        // Route::get('/pengajuan', PengajuanIndex::class)->name('pengajuan.index');
    });

    // Menu khusus dosen
    Route::middleware('role:dosen')->group(function () {
        // Route::get('/pengajuan-saya', PengajuanSaya::class)->name('pengajuan.saya');
    });

    // Khusus super admin
    Route::middleware('auth', 'role:super-admin|operator')->group(function () {
        // route CRUD master data
    });
});
/*  
|--------------------------------------------------------------------------
| Route Terproteksi (Phase 3 lanjutan — Authorization & CRUD)
|--------------------------------------------------------------------------
| Isi grup ini nanti setelah Authorization & CRUD Master Data dibuat.
| Contoh pola yang akan dipakai:
|
| Route::middleware(['auth', 'verified'])->group(function () {
|     Route::get('/dashboard', Dashboard::class)->name('dashboard');
|
|     Route::middleware('role:super-admin|operator')->group(function () {
|         Route::get('/master/fakultas', FakultasIndex::class)->name('fakultas.index');
|         // ...CRUD master data lainnya
|     });
|
|     Route::middleware('role:dosen')->group(function () {
|         Route::get('/pengajuan', PengajuanIndex::class)->name('pengajuan.index');
|     });
| });
*/

// require __DIR__.'/auth.php';
