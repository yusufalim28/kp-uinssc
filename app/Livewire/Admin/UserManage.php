<?php

namespace App\Livewire\Admin;

use App\Models\Fakultas;
use App\Models\Pengaturan;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Title('Kelola Pengguna & Operator')]
class UserManage extends Component
{
    use WithPagination;

    public ?int $editId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'operator';

    public ?int $fakultas_id = null;

    public ?int $program_studi_id = null;

    public ?int $ruang_praktikum_id = null;

    public bool $showModal = false;

    public ?int $confirmDeleteId = null;

    public string $search = '';

    public string $filterRole = '';

    // Pengaturan Peringatan Onboarding Verifikasi Data
    public bool $showNoticeModal = false;

    public string $noticeTitle = '';

    public string $noticeContent = '';

    public bool $noticeActive = true;

    public int $noticeVersion = 1;

    public function openNoticeModal(): void
    {
        $this->noticeTitle = Pengaturan::get('onboarding_notice_title', 'Penting: Verifikasi Data Dosen (NIDN) & Kode Mata Kuliah');
        $this->noticeContent = Pengaturan::get('onboarding_notice_content', 'Selamat datang di Sistem Jadwal Praktikum. Harap diperhatikan bahwa sebagian NIDN Dosen saat ini masih berupa data sementara (dummy) meskipun nama dosen sudah sesuai jadwal asli. Mohon periksa dan perbarui NIDN dosen serta kode mata kuliah di fakultas Anda.');
        $this->noticeActive = (bool) ((int) Pengaturan::get('onboarding_notice_active', '1'));
        $this->noticeVersion = (int) Pengaturan::get('onboarding_notice_version', 1);
        $this->showNoticeModal = true;
    }

    public function saveNoticeSettings(): void
    {
        Pengaturan::set('onboarding_notice_title', $this->noticeTitle);
        Pengaturan::set('onboarding_notice_content', $this->noticeContent);
        Pengaturan::set('onboarding_notice_active', $this->noticeActive ? '1' : '0');

        $this->showNoticeModal = false;
        session()->flash('success', 'Pengaturan teks pemberitahuan verifikasi data berhasil disimpan.');
    }

    public function broadcastNoticeAgain(): void
    {
        $newVersion = ((int) Pengaturan::get('onboarding_notice_version', 1)) + 1;
        Pengaturan::set('onboarding_notice_version', $newVersion);
        Pengaturan::set('onboarding_notice_active', '1');

        $affectedCount = User::role(['operator', 'ketua-lab'])->update([
            'onboarding_notice_version' => 0,
            'onboarding_notice_dismissed_at' => null,
        ]);

        $this->showNoticeModal = false;
        session()->flash('success', "Pemberitahuan verifikasi berhasil diaktifkan ulang (Versi {$newVersion}). {$affectedCount} akun Operator & Ketua Lab akan melihat overlay 1x saat login berikutnya.");
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRole(): void
    {
        $this->resetPage();
    }

    public function updatedFakultasId(): void
    {
        $this->program_studi_id = null;
        $this->ruang_praktikum_id = null;
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'name', 'email', 'password', 'fakultas_id', 'program_studi_id', 'ruang_praktikum_id']);
        $this->role = 'operator';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $u = User::findOrFail($id);

        $this->editId = $u->id;
        $this->name = $u->name;
        $this->email = $u->email;
        $this->password = '';
        $this->role = $u->roles->first()?->name ?? 'operator';
        $this->fakultas_id = $u->fakultas_id;
        $this->program_studi_id = $u->program_studi_id;
        $this->ruang_praktikum_id = $u->ruang_praktikum_id;
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$this->editId,
            'role' => 'required|in:super-admin,operator,ketua-lab,dosen,demo-viewer',
            'fakultas_id' => 'nullable|exists:fakultas,id',
            'program_studi_id' => 'nullable|exists:program_studi,id',
            'ruang_praktikum_id' => 'nullable|exists:ruang_praktikum,id',
        ];

        if (! $this->editId) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'fakultas_id' => in_array($this->role, ['operator', 'ketua-lab']) ? $this->fakultas_id : null,
            'program_studi_id' => $this->role === 'operator' ? $this->program_studi_id : null,
            'ruang_praktikum_id' => $this->role === 'ketua-lab' ? $this->ruang_praktikum_id : null,
        ];

        if (! empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editId) {
            $user = User::findOrFail($this->editId);
            $user->update($data);
        } else {
            $data['email_verified_at'] = now();
            $user = User::create($data);
        }

        // Sync Role
        $user->syncRoles([$this->role]);

        $this->showModal = false;
        session()->flash('success', $this->editId ? "Akun {$user->name} berhasil diperbarui." : "Akun {$user->name} berhasil didaftarkan.");
    }

    public function confirmDelete(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');

            return;
        }

        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        if ($this->confirmDeleteId === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            $this->confirmDeleteId = null;

            return;
        }

        $user = User::find($this->confirmDeleteId);
        if ($user) {
            $user->delete();
            session()->flash('success', "Pengguna {$user->name} berhasil dihapus.");
        }

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        abort_if(! auth()->user()->hasSuperAdminView(), 403);

        $query = User::with(['fakultas', 'programStudi', 'ruangPraktikum', 'roles']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterRole) {
            $query->role($this->filterRole);
        }

        $users = $query->orderBy('name')->paginate(10);

        $fakultasList = Fakultas::orderBy('nama_fakultas')->get();
        $prodiList = $this->fakultas_id
            ? ProgramStudi::where('fakultas_id', $this->fakultas_id)->orderBy('nama_program_studi')->get()
            : collect();
        $ruangList = $this->fakultas_id
            ? RuangPraktikum::where('fakultas_id', $this->fakultas_id)->orderBy('nama_ruang')->get()
            : RuangPraktikum::orderBy('nama_ruang')->get();

        return view('livewire.admin.user-manage', [
            'users' => $users,
            'fakultasList' => $fakultasList,
            'prodiList' => $prodiList,
            'ruangList' => $ruangList,
            'roles' => Role::all(),
        ])->layout('components.layouts.admin', ['title' => 'Kelola Pengguna & Operator']);
    }
}
