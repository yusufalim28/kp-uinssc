<?php

namespace App\Http\Middleware;

use App\Support\DemoReadOnlyGuard;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockDemoViewerWrites
{
    /**
     * Blokir seluruh operasi tulis (Create/Update/Delete/Upload) bagi pengguna role demo-viewer.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isDemoViewer()) {
            return $next($request);
        }

        // Blokir unggah berkas Livewire agar akun demo tidak memenuhi penyimpanan server
        if ($request->is('livewire*/upload-file*') || $request->routeIs('livewire.upload-file')) {
            abort(403, DemoReadOnlyGuard::MESSAGE);
        }

        // Blokir seluruh request mutasi HTTP selain logout dan endpoint interaksi baca Livewire
        if (
            ! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)
            && ! $request->routeIs('logout')
            && ! $request->is('logout')
            && ! $request->routeIs('livewire.update')
            && ! $request->is('livewire*/update')
        ) {
            abort(403, DemoReadOnlyGuard::MESSAGE);
        }

        return DemoReadOnlyGuard::withRequestGuard(fn (): Response => $next($request));
    }
}
