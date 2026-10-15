<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class PustikomBridgeAuthService
{
    /**
     * Cache hasil auth selama 1 HTTP request (mencegah double call di pipeline Fortify).
     */
    protected array $memoized = [];

    /**
     * Authenticate user against Pustikom Bridge API v2 or local database.
     * Enforces university policy: Mahasiswa is blocked from login.
     *
     * @throws ValidationException
     */
    public function authenticate(string $username, string $password): ?User
    {
        $cleanUsername = trim($username);
        $cacheKey = md5($cleanUsername.'|'.$password);

        if (array_key_exists($cacheKey, $this->memoized)) {
            return $this->memoized[$cacheKey];
        }

        // 1. Kebijakan Kampus: Deteksi format NIM mahasiswa (hanya angka 7-15 digit)
        if (preg_match('/^\d{7,15}$/', $cleanUsername)) {
            // Pastikan bukan NIDN/NIP yang kebetulan sama dengan cek apakah terdaftar sebagai Dosen lokal
            $isLocalDosen = Dosen::where('nidn', $cleanUsername)->exists();
            if (! $isLocalDosen && ! config('services.pustikom_bridge.enabled')) {
                throw ValidationException::withMessages([
                    'email' => 'Akses ditolak: Akun Mahasiswa tidak memiliki akses ke portal pengelola. Mahasiswa dapat melihat jadwal langsung di menu publik tanpa perlu login.',
                ]);
            }
        }

        // 2. Coba autentikasi via Bridge API v2 Pustikom jika diaktifkan
        if (config('services.pustikom_bridge.enabled')) {
            $bridgeUser = $this->attemptBridgeLogin($cleanUsername, $password);
            if ($bridgeUser) {
                return $this->memoized[$cacheKey] = $bridgeUser;
            }
        }

        // 3. Fallback: Autentikasi akun lokal (Super Admin / Operator Lab / Akun Uji)
        $localUser = $this->attemptLocalLogin($cleanUsername, $password);

        return $this->memoized[$cacheKey] = $localUser;
    }

    /**
     * Call Bridge API v2 eCampuz / Pustikom.
     *
     * @throws ValidationException
     */
    protected function attemptBridgeLogin(string $username, string $password): ?User
    {
        $baseUrl = rtrim((string) config('services.pustikom_bridge.base_url', 'https://bridge.ecampuz.com'), '/');
        $timeout = (int) config('services.pustikom_bridge.timeout', 4);

        try {
            $loginResponse = Http::timeout($timeout)
                ->asForm()
                ->acceptJson()
                ->post("{$baseUrl}/api/v2/portal/login", [
                    'username' => $username,
                    'password' => $password,
                ]);

            if (! $loginResponse->successful()) {
                return null;
            }

            $token = $loginResponse->json('data.token') ?? $loginResponse->json('token');
            if (! $token) {
                return null;
            }

            // Ambil profil pengguna dari API
            $profilResponse = Http::timeout($timeout)
                ->withToken($token)
                ->acceptJson()
                ->get("{$baseUrl}/api/v2/profil");

            if (! $profilResponse->successful()) {
                return null;
            }

            $profile = $profilResponse->json('data') ?? $profilResponse->json();
            $role = strtolower((string) ($profile['role'] ?? ''));
            $nim = $profile['nim'] ?? null;
            $nip = $profile['nip'] ?? $profile['nidn'] ?? null;

            // Blokir jika akun adalah Mahasiswa
            if ($role === 'mahasiswa' || $role === 'student' || (! empty($nim) && empty($nip))) {
                throw ValidationException::withMessages([
                    'email' => 'Akses ditolak: Akun Mahasiswa tidak memiliki izin untuk masuk ke sistem pengelola. Silakan akses jadwal melalui menu publik tanpa login.',
                ]);
            }

            // Sinkronisasi data User ke database lokal
            $email = $profile['email'] ?? ($username.'@uinssc.ac.id');
            $name = $profile['nama'] ?? $profile['name'] ?? $username;

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            // Berikan role dosen jika belum memiliki role
            Role::findOrCreate('dosen', 'web');
            if (! $user->hasAnyRole(['dosen', 'operator', 'super-admin'])) {
                $user->assignRole('dosen');
            }

            // Hubungkan dengan master Dosen
            $dosenIdentifier = $nip ?? $profile['nidn'] ?? $username;
            $dosen = Dosen::firstOrCreate(
                ['nidn' => $dosenIdentifier],
                [
                    'nama' => $user->name,
                    'email' => $user->email,
                    'status' => 'aktif',
                    'user_id' => $user->id,
                ]
            );

            if ($dosen->user_id !== $user->id) {
                $dosen->update(['user_id' => $user->id]);
            }

            return $user;
        } catch (ConnectionException $e) {
            Log::warning('Pustikom Bridge API tidak dapat dijangkau, beralih ke auth lokal: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Fallback to local database authentication.
     */
    protected function attemptLocalLogin(string $username, string $password): ?User
    {
        // Cari berdasarkan email atau nidn dosen
        $user = User::where('email', $username)->first();

        if (! $user) {
            $dosen = Dosen::where('nidn', $username)->first();
            if ($dosen && $dosen->user) {
                $user = $dosen->user;
            }
        }

        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }
}
