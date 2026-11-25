<?php

namespace App\Models;

use Database\Factories\UserFactory;
// use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'fakultas_id', 'program_studi_id', 'ruang_praktikum_id', 'onboarding_notice_version', 'onboarding_notice_dismissed_at'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    public function shouldSeeOnboardingNotice(): bool
    {
        $isActive = (bool) Pengaturan::get('onboarding_notice_active', '1');
        if (! $isActive) {
            return false;
        }

        $targetRoles = array_filter(array_map('trim', explode(',', (string) Pengaturan::get('onboarding_notice_target_roles', 'operator,ketua-lab'))));
        if (! $this->hasAnyRole($targetRoles)) {
            return false;
        }

        $currentVersion = (int) Pengaturan::get('onboarding_notice_version', 1);

        return (int) $this->onboarding_notice_version < $currentVersion;
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function ruangPraktikum()
    {
        return $this->belongsTo(RuangPraktikum::class);
    }

    public function isKetuaLab(): bool
    {
        return $this->hasRole('ketua-lab');
    }

    public function isDemoViewer(): bool
    {
        return $this->hasRole('demo-viewer');
    }

    public function hasSuperAdminView(): bool
    {
        return $this->hasAnyRole(['super-admin', 'demo-viewer']);
    }

    public function dosen()
    {
        return $this->hasOne(Dosen::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
