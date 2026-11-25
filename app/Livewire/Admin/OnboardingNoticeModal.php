<?php

namespace App\Livewire\Admin;

use App\Models\Pengaturan;
use Livewire\Component;

class OnboardingNoticeModal extends Component
{
    public bool $show = false;

    public string $title = '';

    public string $content = '';

    public function mount(): void
    {
        $user = auth()->user();
        if ($user && $user->shouldSeeOnboardingNotice()) {
            $this->show = true;
            $this->title = Pengaturan::get('onboarding_notice_title', 'Penting: Verifikasi Data Dosen (NIDN) & Kode Mata Kuliah');
            $this->content = Pengaturan::get('onboarding_notice_content', 'Selamat datang di Sistem Jadwal Praktikum. Harap diperhatikan bahwa sebagian NIDN Dosen saat ini masih berupa data sementara (dummy) meskipun nama dosen sudah sesuai jadwal asli. Mohon periksa dan perbarui NIDN dosen serta kode mata kuliah di fakultas Anda.');
        }
    }

    public function dismiss(): void
    {
        $user = auth()->user();
        if ($user) {
            $currentVersion = (int) Pengaturan::get('onboarding_notice_version', 1);
            $user->update([
                'onboarding_notice_version' => $currentVersion,
                'onboarding_notice_dismissed_at' => now(),
            ]);
        }

        $this->show = false;
    }

    public function render()
    {
        return view('livewire.admin.onboarding-notice-modal');
    }
}
