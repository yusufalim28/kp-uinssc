<?php

namespace App\Support;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

class DemoReadOnlyGuard
{
    public const MESSAGE = 'Mode Demo (Read-Only): Aksi tambah, ubah, atau hapus data tidak diizinkan.';

    private static bool $active = false;

    private static ?int $registeredAppId = null;

    /**
     * Metode Livewire yang aman (hanya baca / navigasi UI) untuk role demo-viewer.
     *
     * @var list<string>
     */
    private const ALLOWED_LIVEWIRE_METHODS = [
        'setTab',
        'toggleSection',
        'resetFilters',
        'openCreate',
        'openEdit',
        'openNoticeModal',
        'openCleanModal',
        'openApprove',
        'openReject',
        'confirmDelete',
        'gotoPage',
        'nextPage',
        'previousPage',
        'setPage',
        'resetPage',
        '__dispatch',
        '$set',
        '$toggle',
        '$refresh',
        '$commit',
        '$sync',
    ];

    /**
     * Daftarkan hook global Livewire & Database untuk proteksi read-only role demo-viewer.
     */
    public static function register(): void
    {
        $appId = spl_object_id(app());

        if (self::$registeredAppId === $appId) {
            return;
        }

        self::$registeredAppId = $appId;

        DB::beforeExecuting(function (string $query, array $bindings, Connection $connection): void {
            if (! self::$active) {
                return;
            }

            self::assertQueryAllowed($query);
        });

        Livewire::listen('call', function (object $component, string $method): void {
            $user = auth()->hasUser() ? auth()->user() : null;

            if (! $user || ! $user->isDemoViewer()) {
                return;
            }

            if (self::isAllowedLivewireMethod($method)) {
                return;
            }

            throw new AuthorizationException(self::MESSAGE);
        });
    }

    /**
     * Jalankan callback dengan proteksi query tulis basis data aktif selama request berlangsung.
     */
    public static function withRequestGuard(Closure $callback): mixed
    {
        $previous = self::$active;
        self::$active = true;

        try {
            return $callback();
        } finally {
            self::$active = $previous;
        }
    }

    /**
     * Pastikan metode Livewire yang dipanggil termasuk operasi baca / navigasi UI.
     */
    public static function isAllowedLivewireMethod(string $method): bool
    {
        if (in_array($method, self::ALLOWED_LIVEWIRE_METHODS, true)) {
            return true;
        }

        return str_starts_with($method, 'updating')
            || str_starts_with($method, 'updated');
    }

    /**
     * Tolak seluruh query mutasi SQL kecuali tabel infrastruktur (session/cache) atau logout.
     *
     * @throws AuthorizationException
     */
    public static function assertQueryAllowed(string $query): void
    {
        if (! preg_match('/^\s*(insert|update|delete|replace|truncate|alter|drop)\b/i', $query)) {
            return;
        }

        // Izinkan tabel infrastruktur sesi, cache, dan antrean sistem
        if (preg_match('/\b(?:into|update|from)\s+[`"\[]?(?:sessions|cache|cache_locks|jobs|failed_jobs)[`"\]]?(?:\s|$|\()/i', $query)) {
            return;
        }

        // Izinkan pembersihan remember_token milik pengguna saat proses logout
        $request = app()->bound('request') ? request() : null;
        if (
            $request
            && ($request->routeIs('logout') || $request->is('logout'))
            && preg_match('/^\s*update\s+[`"\[]?users[`"\]]?\s+set\s+.*remember_token/i', $query)
        ) {
            return;
        }

        throw new AuthorizationException(self::MESSAGE);
    }
}
