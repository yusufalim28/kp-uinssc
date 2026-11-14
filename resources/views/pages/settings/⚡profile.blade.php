<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Pengaturan Akun'), Layout('components.layouts.admin')] class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->dosen) {
            $user->dosen->update([
                'nama' => $user->name,
                'email' => $user->email,
            ]);
        }

        session()->flash('status', 'Profil dan alamat email berhasil diperbarui.');

        Flux::toast(variant: 'success', text: __('Profile updated.'));
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-pages::settings.layout heading="Profil Pengguna & Email Login" subheading="Perbarui nama lengkap dan alamat Gmail Anda untuk masuk ke sistem jadwal laboratorium.">
        @if (session('status'))
            <div class="mb-5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs rounded-2xl p-4 flex items-center gap-2.5 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form wire:submit="updateProfileInformation" class="max-w-lg space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" wire:model="name" required autofocus autocomplete="name"
                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition">
                @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-zinc-300 mb-1">
                    Alamat Email (Gmail / Akun Login) <span class="text-rose-500">*</span>
                </label>
                <input type="email" wire:model="email" required autocomplete="email"
                       class="w-full rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-xs px-3.5 py-2.5 text-gray-900 dark:text-zinc-100 transition font-mono">
                <p class="text-[11px] text-gray-400 mt-1">Gunakan alamat Gmail aktif Anda. Email ini digunakan saat login ke portal.</p>
                @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-2 flex items-center justify-between">
                <button type="submit" data-test="update-profile-button"
                        class="inline-flex items-center gap-2 bg-[#0F352A] hover:bg-[#134436] dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-xs">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Perubahan</span>
                </button>

                <span wire:loading wire:target="updateProfileInformation" class="text-xs text-gray-500 flex items-center gap-1.5">
                    <svg class="animate-spin h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Menyimpan...</span>
                </span>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <div class="mt-10 pt-6 border-t border-gray-100 dark:border-zinc-800">
                <livewire:pages::settings.delete-user-form />
            </div>
        @endif
    </x-pages::settings.layout>
</section>
