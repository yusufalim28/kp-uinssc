<?php

use App\Http\Controllers\JadwalExportController;
use App\Livewire\Admin\ActivityLogIndex;
use App\Livewire\Admin\JadwalManage;
use App\Livewire\Admin\KelolaPanduan;
use App\Livewire\Admin\MasterData\DosenIndex;
use App\Livewire\Admin\MasterData\FakultasIndex;
use App\Livewire\Admin\MasterData\KelasIndex;
use App\Livewire\Admin\MasterData\MataKuliahIndex;
use App\Livewire\Admin\MasterData\PenanggungJawabLabIndex;
use App\Livewire\Admin\MasterData\ProgramStudiIndex;
use App\Livewire\Admin\MasterData\RuangPraktikumIndex;
use App\Livewire\Admin\MasterData\TahunAkademikIndex;
use App\Livewire\Admin\PanduanInternal;
use App\Livewire\Admin\PengajuanIndex;
use App\Livewire\Admin\SampahIndex;
use App\Livewire\Admin\UserManage;
use App\Livewire\Dosen\JadwalSaya;
use App\Livewire\Dosen\NotifikasiSaya;
use App\Livewire\Dosen\PengajuanSaya;
use App\Livewire\Public\DaftarJadwal;
use App\Livewire\Public\DetailJadwal;
use App\Livewire\Public\Home;
use App\Livewire\Public\JadwalRuangan;
use App\Livewire\Public\KalenderJadwal;
use App\Livewire\Public\PanduanPublik;
use App\Livewire\Public\StrukturLab;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Route Publik (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', Home::class)->name('home');
Route::get('/jadwal', DaftarJadwal::class)->name('jadwal.index');
Route::get('/jadwal/{jadwal}', DetailJadwal::class)->name('jadwal.detail');
Route::get('/kalender', KalenderJadwal::class)->name('jadwal.kalender');
Route::get('/jadwal-ruang', JadwalRuangan::class)->name('jadwal.ruang');
Route::get('/struktur-lab', StrukturLab::class)->name('struktur.lab');
Route::get('/panduan', PanduanPublik::class)->name('panduan.index');
Route::get('/jadwal/export/pdf', [JadwalExportController::class, 'pdf'])->name('jadwal.export.pdf');
Route::get('/jadwal/export/excel', [JadwalExportController::class, 'excel'])->name('jadwal.export.excel');
Route::get('/jadwal/modul/{mataKuliah}/download', function (MataKuliah $mataKuliah) {
    if (! $mataKuliah->file_modul || ! Storage::disk('public')->exists($mataKuliah->file_modul)) {
        abort(404, 'File modul praktikum tidak ditemukan di server.');
    }

    $extension = pathinfo($mataKuliah->file_modul, PATHINFO_EXTENSION) ?: 'pdf';
    $safeName = Str::slug($mataKuliah->nama_mk).'-modul-praktikum.'.$extension;

    return Storage::disk('public')->download($mataKuliah->file_modul, $safeName);
})->name('jadwal.modul.download');

// Route Terproteksi
Route::middleware(['auth', 'verified', 'prevent-back-history'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Master Data Universitas — super-admin, operator, dan demo-viewer (read-only)
    Route::middleware('role:super-admin|operator|demo-viewer')->group(function () {
        Route::get('/master/fakultas', FakultasIndex::class)->name('fakultas.index');
        Route::get('/master/prodi', ProgramStudiIndex::class)->name('prodi.index');
        Route::get('/master/tahun-akademik', TahunAkademikIndex::class)->name('tahunakademik.index');
    });

    // Master Data Operasional Lab, Jadwal & Pengajuan — super-admin, operator, ketua-lab, dan demo-viewer (read-only)
    Route::middleware('role:super-admin|operator|ketua-lab|demo-viewer')->group(function () {
        Route::get('/master/kelas', KelasIndex::class)->name('kelas.index');
        Route::get('/master/mata-kuliah', MataKuliahIndex::class)->name('matakuliah.index');
        Route::get('/master/dosen', DosenIndex::class)->name('dosen.index');
        Route::get('/master/ruang', RuangPraktikumIndex::class)->name('ruang.index');
        Route::get('/master/penanggung-jawab-lab', PenanggungJawabLabIndex::class)->name('pjlab.index');
        Route::get('/jadwal-manage', JadwalManage::class)->name('jadwal.manage');
        Route::get('/pengajuan', PengajuanIndex::class)->name('pengajuan.index');
    });

    // Sampah & Activity Log & User Management — super-admin dan demo-viewer (read-only)
    Route::middleware('role:super-admin|demo-viewer')->group(function () {
        Route::get('/master/pengguna', UserManage::class)->name('users.index');
        Route::get('/sampah', SampahIndex::class)->name('sampah.index');
        Route::get('/log-aktivitas', ActivityLogIndex::class)->name('activitylog.index');
        Route::get('/kelola-panduan', KelolaPanduan::class)->name('panduan.manage');
    });

    // Menu khusus dosen
    Route::middleware('role:dosen')->group(function () {
        Route::get('/jadwal-saya', JadwalSaya::class)->name('jadwal.saya');
        Route::get('/pengajuan-saya', PengajuanSaya::class)->name('pengajuan.saya');
        Route::get('/notifikasi-saya', NotifikasiSaya::class)->name('notifikasi.saya');
    });

    // Panduan & Aturan Internal (Super-admin, Operator, Dosen)
    Route::get('/admin/panduan', PanduanInternal::class)->name('admin.panduan');
});

// require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
