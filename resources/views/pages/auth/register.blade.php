<x-layouts.guest :title="__('Register')">
    <div class="flex items-center justify-center min-h-[calc(100vh-140px)]">
        <div class="w-full max-w-md bg-white border border-gray-100 rounded-2xl shadow-xl p-8 relative overflow-hidden">
            <!-- Decorative accent -->
            <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>

            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Create an account') }}</h1>
                <p class="text-gray-500 text-sm mt-2">{{ __('Enter your details below to create your account') }}</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
                @csrf
                <!-- Name -->
                <flux:input
                    name="name"
                    :label="__('Name')"
                    :value="old('name')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    :placeholder="__('Full name')"
                />

                <!-- Email Address -->
                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                />

                <!-- Password -->
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Password')"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                />

                <!-- Confirm Password -->
                <flux:input
                    name="password_confirmation"
                    :label="__('Confirm password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Confirm password')"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                />

                <div class="mt-2">
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-medium py-2.5 rounded-lg transition shadow-md hover:shadow-lg">
                        {{ __('Create account') }}
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                <span>{{ __('Already have an account?') }}</span>
                <a href="{{ route('login') }}" class="text-primary font-medium hover:underline" wire:navigate>{{ __('Log in') }}</a>
            </div>
        </div>
    </div>
</x-layouts.guest>
