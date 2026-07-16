<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="" :status="session('status')" />

    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large"></x-theme>

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass }}">Bem-vindo(a) ao Portal Etec!</p>

        <x-form-link href="{{ route('register') }}">
            {{ __('auth.login.no_account') }}
            <x-slot name="icon">
                <x-lucide-square-arrow-out-up-right
                    class="size-4 stroke-3"
                ></x-lucide-square-arrow-out-up-right>
            </x-slot>
        </x-form-link>

        <form method="POST" action="{{ route('login') }}" class="flex w-full flex-col gap-regular">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('auth.login.label.email')" />
                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    :value="old('email')"
                    :placeholder="__('auth.placeholder.email')"
                    required
                    autofocus
                    autocomplete="username"
                />
                <x-input-error :messages="$errors->get('email')" class="" />
            </div>

            <!-- Password -->
            <div class="">
                <x-input-label for=" password" :value="__('auth.login.label.password')" />

                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    :placeholder="__('auth.placeholder.password')"
                    required
                    autocomplete="current-password"
                />

                <x-input-error :messages="$errors->get('incorrect_password')" class="" />
            </div>

            <!-- Forgot Your Password? -->
            @if (Route::has('password.request'))
                <a
                    class="rounded-md text-sm font-medium text-gray underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                    href="{{ route('password.request') }}"
                >
                    {{ __('auth.login.forgot_password') }}
                </a>
            @endif

            <!-- Remember Me -->
            <div
                class="relative flex items-center justify-between gap-small *:flex *:items-center *:gap-small"
            >
                <div>
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="peer size-5 appearance-none rounded-small border border-border shadow-md checked:bg-accent hover:bg-accent-bg checked:hover:bg-accent-hover"
                        name="remember"
                    />
                    <x-lucide-check
                        class="pointer-events-none absolute top-1/2 left-0 hidden size-5 -translate-y-1/2 stroke-3 text-white peer-checked:block"
                    ></x-lucide-check>
                    <label for="remember_me" class="flex-1 text-sm">
                        {{ __('auth.login.remember_me') }}
                    </label>
                </div>
                <div class="">
                    <x-primary-button class=""> {{ __('auth.login.log_in') }} </x-primary-button>
                </div>
            </div>
        </form>
    </section>
</x-guest-layout>
