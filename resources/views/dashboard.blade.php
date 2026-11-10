@php
    $user = auth()->user();
    if ($user->hasRole('super-admin')) {
        $title = 'Dashboard Super Admin';
    } elseif ($user->hasRole('operator')) {
        $title = 'Dashboard Operator ' . ($user->fakultas?->kode_fakultas ? 'Fakultas ' . $user->fakultas->kode_fakultas : '');
    } elseif ($user->hasRole('dosen')) {
        $title = 'Dashboard Dosen Pengampu';
    } else {
        $title = 'Dashboard';
    }
@endphp

<x-layouts.admin :title="$title">
    @if($user->hasRole('super-admin'))
        @include('dashboard.super-admin')
    @elseif($user->hasRole('operator'))
        @include('dashboard.operator')
    @elseif($user->hasRole('dosen'))
        @include('dashboard.dosen')
    @else
        @include('dashboard.super-admin')
    @endif
</x-layouts.admin>
