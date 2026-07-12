<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('auth.email')" />
            <x-text-input
                id="email"
                class="mt-1 block w-full"
                type="email"
                name="email"
                :value="old('email')"
                :placeholder="__('auth.email_placeholder')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth.password')" />

            <x-text-input
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                :placeholder="__('auth.password_placeholder')"
                required
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('incorrect_password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="relative mt-4 flex items-center gap-small">
            <input
                id="remember_me"
                type="checkbox"
                class="peer size-5 appearance-none rounded-small border border-border shadow-md checked:bg-accent hover:bg-accent-bg checked:hover:bg-accent-hover"
                name="remember"
            />
            <x-lucide-check
                class="pointer-events-none absolute top-1/2 left-0 hidden size-5 -translate-y-1/2 stroke-3 text-white peer-checked:block"
            ></x-lucide-check>
            <label for="remember_me" class="flex-1 text-sm"> {{ __('auth.remember_me') }} </label>
        </div>

        <div class="mt-4 flex items-center justify-between">
            @if (Route::has('password.request'))
                <a
                    class="rounded-md text-sm font-medium text-gray underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                    href="{{ route('password.request') }}"
                >
                    {{ __('auth.forgot_password') }}
                </a>
            @endif

            <x-primary-button class="ms-3"> {{ __('auth.log_in') }} </x-primary-button>
        </div>
    </form>
</x-guest-layout>
