<?php

use App\Livewire\Admin\SampahIndex;
use App\Livewire\Admin\ActivityLogIndex;
use App\Livewire\Dosen\PengajuanSaya;
use App\Livewire\Dosen\NotifikasiSaya;
use App\Livewire\Admin\PengajuanIndex;
use App\Livewire\Admin\JadwalManage;
use App\Livewire\Admin\MasterData\DosenIndex;
use App\Livewire\Admin\MasterData\FakultasIndex;
use App\Livewire\Admin\MasterData\KelasIndex;
use App\Livewire\Admin\MasterData\MataKuliahIndex;
use App\Livewire\Admin\MasterData\ProgramStudiIndex;
use App\Livewire\Admin\MasterData\RuangPraktikumIndex;
use App\Livewire\Admin\MasterData\TahunAkademikIndex;
use App\Livewire\Public\DaftarJadwal;
use App\Livewire\Public\DetailJadwal;
use App\Livewire\Public\Home;
use App\Livewire\Public\KalenderJadwal;
use App\Http\Controllers\JadwalExportController;
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
Route::get('/jadwal/export/pdf', [JadwalExportController::class, 'pdf'])->name('jadwal.export.pdf');
Route::get('/jadwal/export/excel', [JadwalExportController::class, 'excel'])->name('jadwal.export.excel');

// Route Terproteksi
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Master Data — hanya super-admin & operator
    Route::middleware('role:super-admin|operator')->group(function () {
        Route::get('/master/fakultas', FakultasIndex::class)->name('fakultas.index');
        Route::get('/master/prodi', ProgramStudiIndex::class)->name('prodi.index');
        Route::get('/master/kelas', KelasIndex::class)->name('kelas.index');
        Route::get('/master/mata-kuliah', MataKuliahIndex::class)->name('matakuliah.index');
        Route::get('/master/dosen', DosenIndex::class)->name('dosen.index');
        Route::get('/master/ruang', RuangPraktikumIndex::class)->name('ruang.index');
        Route::get('/master/tahun-akademik', TahunAkademikIndex::class)->name('tahunakademik.index');

        Route::get('/jadwal-manage', JadwalManage::class)->name('jadwal.manage');
        Route::get('/pengajuan', PengajuanIndex::class)->name('pengajuan.index');
    });

    // Sampah & Activity Log — KHUSUS super-admin (lebih ketat dari operator)
    Route::middleware('role:super-admin')->group(function () {
        Route::get('/sampah', SampahIndex::class)->name('sampah.index');
        Route::get('/log-aktivitas', ActivityLogIndex::class)->name('activitylog.index');
    });

    // Menu khusus dosen
    Route::middleware('role:dosen')->group(function () {
        Route::get('/pengajuan-saya', PengajuanSaya::class)->name('pengajuan.saya');
        Route::get('/notifikasi-saya', NotifikasiSaya::class)->name('notifikasi.saya');
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
