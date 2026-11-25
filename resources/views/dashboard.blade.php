@php
    $user = auth()->user();
    if ($user->hasRole('super-admin')) {
        $title = 'Dashboard Super Admin';
    } elseif ($user->hasRole('demo-viewer')) {
        $title = 'Dashboard Mode Demo (Read-Only)';
    } elseif ($user->hasRole('operator')) {
        $title = 'Dashboard Operator ' . ($user->fakultas?->kode_fakultas ? 'Fakultas ' . $user->fakultas->kode_fakultas : '');
    } elseif ($user->hasRole('ketua-lab')) {
        $title = 'Dashboard Ketua Laboratorium ' . ($user->ruangPraktikum?->nama_ruang ? '— ' . $user->ruangPraktikum->nama_ruang : ($user->fakultas?->kode_fakultas ? 'Fakultas ' . $user->fakultas->kode_fakultas : ''));
    } elseif ($user->hasRole('dosen')) {
        $title = 'Dashboard Dosen Pengampu';
    } else {
        $title = 'Dashboard';
    }
@endphp

<x-layouts.admin :title="$title">
    @if($user->hasSuperAdminView())
        @include('dashboard.super-admin')
    @elseif($user->hasRole('operator'))
        @include('dashboard.operator')
    @elseif($user->hasRole('ketua-lab'))
        @include('dashboard.ketua-lab')
    @elseif($user->hasRole('dosen'))
        @include('dashboard.dosen')
    @else
        @include('dashboard.super-admin')
    @endif
</x-layouts.admin>
