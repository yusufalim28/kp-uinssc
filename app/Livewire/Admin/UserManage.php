<?php

namespace App\Livewire\Admin;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
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

    public bool $showModal = false;

    public ?int $confirmDeleteId = null;

    public string $search = '';

    public string $filterRole = '';

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
    }

    public function openCreate(): void
    {
        $this->reset(['editId', 'name', 'email', 'password', 'fakultas_id', 'program_studi_id']);
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
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$this->editId,
            'role' => 'required|in:super-admin,operator,dosen',
            'fakultas_id' => 'nullable|exists:fakultas,id',
            'program_studi_id' => 'nullable|exists:program_studi,id',
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
            'fakultas_id' => $this->role === 'operator' ? $this->fakultas_id : null,
            'program_studi_id' => $this->role === 'operator' ? $this->program_studi_id : null,
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
        abort_if(! auth()->user()->hasRole('super-admin'), 403);

        $query = User::with(['fakultas', 'programStudi', 'roles']);

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

        return view('livewire.admin.user-manage', [
            'users' => $users,
            'fakultasList' => $fakultasList,
            'prodiList' => $prodiList,
            'roles' => Role::all(),
        ])->layout('components.layouts.admin', ['title' => 'Kelola Pengguna & Operator']);
    }
}
