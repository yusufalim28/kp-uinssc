<x-layouts.guest :title="__('Log in')">
    <div class="flex items-center justify-center min-h-[calc(100vh-140px)]">
        <div class="w-full max-w-md bg-white border border-gray-100 rounded-2xl shadow-xl p-8 relative overflow-hidden">
            <!-- Decorative accent -->
            <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>

            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Log in to your account') }}</h1>
                <p class="text-gray-500 text-sm mt-2">{{ __('Enter your email and password below to log in') }}</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                @csrf

                <!-- Email Address -->
                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                />

                <!-- Password -->
                <div class="relative">
                    <flux:input
                        name="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Password')"
                        viewable
                    />

                    @if (Route::has('password.request'))
                        <flux:link class="absolute top-0 text-sm end-0 text-primary hover:text-primary-dark" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </flux:link>
                    @endif
                </div>

                <!-- Remember Me -->
                <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

                <div class="mt-2">
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-medium py-2.5 rounded-lg transition shadow-md hover:shadow-lg">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>

            <div class="mt-8 text-sm text-center text-gray-500">
                <span>{{ __('Don\'t have an account?') }}</span>
                <a href="{{ route('register') }}" class="text-primary font-medium hover:underline" wire:navigate>{{ __('Sign up') }}</a>
            </div>
        </div>
    </div>
</x-layouts.guest>
