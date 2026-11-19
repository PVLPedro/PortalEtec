<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" />

    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large text-text" />

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <div class="flex w-full flex-col gap-regular *:flex-1">
            <x-form-link href="{{ url('/') }}">
                <x-lucide-chevron-left />
                Voltar para a seleção
            </x-form-link>
            <x-form-link href="{{ route('register') }}">
                Não possui uma conta? Crie uma
                <x-lucide-square-arrow-out-up-right class="size-4 stroke-3" />
            </x-form-link>
            <x-form-text>
                Entrando como Professor ou Coordenador
                <x-lucide-id-card />
            </x-form-text>
        </div>

        <form method="POST" action="{{ route('login') }}" class="flex w-full flex-col gap-large">
            @csrf

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
                >
                    <x-slot name="icon">
                        <x-lucide-mail class="shrink-0" />
                    </x-slot>
                </x-text-input>
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="password" :value="__('auth.login.label.password')" />
                <x-password-input :placeholder="__('auth.placeholder.password')" required />
                <x-input-error :messages="$errors->get('incorrect_password')" />
            </div>

            @if (Route::has('password.request'))
                <a
                    class="rounded-small text-sm font-medium text-secondary underline hover:text-text focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                    href="{{ route('password.request') }}"
                >
                    {{ __('auth.login.forgot_password') }}
                </a>
            @endif

            <x-remember-me />

            <div class="w-full *:w-full">
                <x-primary-button class="bg-accent text-white hover:bg-accent-hover">
                    <x-lucide-log-in />
                    Entrar
                </x-primary-button>
            </div>
        </form>
    </section>
</x-guest-layout>
