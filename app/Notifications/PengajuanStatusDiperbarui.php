<?php

namespace App\Notifications;

use App\Models\PengajuanPerubahan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanStatusDiperbarui extends Notification
{
    use Queueable;

    public function __construct(public PengajuanPerubahan $pengajuan)
    {
        // Load relasi di sini supaya tersedia baik untuk channel mail
        // maupun database tanpa query ulang.
        $this->pengajuan->loadMissing('jadwalPraktikum.mataKuliah');
    }

    /**
     * Dua channel sekaligus: 'mail' (email) dan 'database' (muncul di
     * halaman /notifikasi-saya). Kalau SMTP belum disetup, channel
     * database tetap jalan normal — jadi fitur ini tetap bisa didemokan
     * tanpa konfigurasi email asli (lihat CARA_PAKAI.md).
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $namaMk = $this->pengajuan->jadwalPraktikum->mataKuliah->nama_mk;

        $message = (new MailMessage)->subject('Status Pengajuan Perubahan Jadwal Diperbarui');

        if ($this->pengajuan->status === 'disetujui') {
            return $message
                ->greeting('Pengajuan Anda Disetujui')
                ->line("Pengajuan perubahan jadwal untuk mata kuliah \"{$namaMk}\" telah disetujui.")
                ->line("Perubahan yang diajukan: {$this->pengajuan->perubahan}")
                ->line('Operator akan menerapkan perubahan jadwal sebenarnya secara manual dalam waktu dekat.')
                ->action('Lihat Pengajuan Saya', url('/pengajuan-saya'));
        }

        return $message
            ->greeting('Pengajuan Anda Ditolak')
            ->line("Pengajuan perubahan jadwal untuk mata kuliah \"{$namaMk}\" ditolak.")
            ->line("Catatan dari operator: {$this->pengajuan->catatan_penolakan}")
            ->action('Lihat Pengajuan Saya', url('/pengajuan-saya'));
    }

    /**
     * Data yang disimpan untuk channel 'database', ditampilkan di
     * halaman /notifikasi-saya.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pengajuan_id'      => $this->pengajuan->id,
            'status'            => $this->pengajuan->status,
            'nama_mk'           => $this->pengajuan->jadwalPraktikum->mataKuliah->nama_mk,
            'perubahan'         => $this->pengajuan->perubahan,
            'catatan_penolakan' => $this->pengajuan->catatan_penolakan,
        ];
    }
}
