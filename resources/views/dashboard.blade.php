<x-layouts.admin :title="'Dashboard'">
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-xs border border-gray-100 flex flex-col md:flex-row items-center gap-6 justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Selamat datang, {{ auth()->user()->name }}</h1>
                <p class="text-gray-500 mt-2 text-sm">
                    Anda masuk ke sistem sebagai <span class="font-semibold text-primary px-2.5 py-1 bg-primary/10 rounded-lg">{{ auth()->user()->getRoleNames()->implode(', ') ?: 'Pengelola Lab' }}</span>
                </p>
            </div>
            <div class="hidden md:flex items-center gap-3">
                <div class="text-right">
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Tahun Akademik</span>
                    <span class="text-sm font-bold text-gray-800">
                        {{ \App\Models\TahunAkademik::where('is_aktif', true)->first()?->nama ?? 'Semester Aktif' }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>

        @role('super-admin|operator')
        <!-- Metric Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <span class="block text-xl font-extrabold text-gray-900">{{ \App\Models\JadwalPraktikum::where('status', 'aktif')->count() }}</span>
                    <span class="text-xs text-gray-500 font-medium">Jadwal Aktif</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <span class="block text-xl font-extrabold text-gray-900">{{ \App\Models\Dosen::where('status', 'aktif')->count() }}</span>
                    <span class="text-xs text-gray-500 font-medium">Dosen Pengampu</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <span class="block text-xl font-extrabold text-gray-900">{{ \App\Models\RuangPraktikum::where('status', 'aktif')->count() }}</span>
                    <span class="text-xs text-gray-500 font-medium">Ruang Lab</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    @php $pendingCount = \App\Models\PengajuanPerubahan::where('status', 'menunggu')->count(); @endphp
                    <span class="block text-xl font-extrabold {{ $pendingCount > 0 ? 'text-orange-600' : 'text-gray-900' }}">{{ $pendingCount }}</span>
                    <span class="text-xs text-gray-500 font-medium">Pengajuan Antre</span>
                </div>
            </div>
        </div>

        <!-- Admin/Operator Shortcuts -->
        <div>
            <h2 class="text-base font-bold text-gray-900 mb-3">Akses Cepat Master Data</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="{{ route('jadwal.manage') }}" class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:border-primary hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-primary transition">Kelola Jadwal</span>
                </a>
                <a href="{{ route('pengajuan.index') }}" class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:border-primary hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto rounded-full bg-orange-50 text-orange-600 flex items-center justify-center mb-3 group-hover:bg-orange-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-primary transition">Pengajuan</span>
                </a>
                <a href="{{ route('matakuliah.index') }}" class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:border-primary hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-primary transition">Mata Kuliah</span>
                </a>
                <a href="{{ route('dosen.index') }}" class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:border-primary hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto rounded-full bg-green-50 text-green-600 flex items-center justify-center mb-3 group-hover:bg-green-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-primary transition">Dosen</span>
                </a>
            </div>
        </div>
        @endrole

        @role('super-admin')
        <!-- Super Admin Shortcuts -->
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">Sistem & Log</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('activitylog.index') }}" class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:border-primary hover:shadow-md transition flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-600 flex items-center justify-center group-hover:bg-gray-800 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary transition">Riwayat Aktivitas</h3>
                        <p class="text-sm text-gray-500">Pantau semua perubahan data yang dilakukan oleh pengguna.</p>
                    </div>
                </a>
                <a href="{{ route('sampah.index') }}" class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:border-primary hover:shadow-md transition flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary transition">Tempat Sampah</h3>
                        <p class="text-sm text-gray-500">Pulihkan atau hapus permanen data yang telah dihapus.</p>
                    </div>
                </a>
            </div>
        </div>
        @endrole

        @role('dosen')
        <!-- Dosen Info -->
        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('pengajuan.saya') }}" class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:border-primary hover:shadow-md transition flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary transition">Pengajuan Saya</h3>
                        <p class="text-sm text-gray-500">Lihat status permohonan perubahan jadwal praktikum Anda.</p>
                    </div>
                </a>
                
                @if(auth()->user()->unreadNotifications()->count() > 0)
                <a href="{{ route('notifikasi.saya') }}" class="bg-primary/10 border border-primary/20 p-6 rounded-xl shadow-sm hover:bg-primary/20 transition flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Notifikasi Baru</h3>
                        <p class="text-sm text-gray-700">Anda memiliki {{ auth()->user()->unreadNotifications()->count() }} notifikasi yang belum dibaca.</p>
                    </div>
                </a>
                @endif
            </div>
        </div>
        @endrole
    </div>
</x-layouts.admin>
