<x-guest-layout>
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
            <x-form-text>
                Entrando como Aluno
                <x-lucide-graduation-cap />
            </x-form-text>
        </div>

        <form
            method="POST"
            action="{{ route('login.student') }}"
            class="flex w-full flex-col gap-large"
        >
            @csrf

            <div>
                <x-input-label for="rm" value="RM" />
                <x-text-input
                    id="rm"
                    type="text"
                    inputmode="numeric"
                    name="rm"
                    :value="old('rm')"
                    placeholder="Seu RM"
                    required
                    autofocus
                    autocomplete="username"
                >
                    <x-slot name="icon">
                        <x-lucide-rm class="shrink-0" />
                    </x-slot>
                </x-text-input>
                <x-input-error :messages="$errors->get('rm')" />
            </div>

            <div>
                <x-input-label for="etec_code" value="Código da Etec" />
                <x-text-input
                    id="etec_code"
                    type="text"
                    inputmode="numeric"
                    name="etec_code"
                    :value="old('etec_code')"
                    placeholder="Código da sua unidade"
                    required
                >
                    <x-slot name="icon">
                        <x-lucide-certificate class="shrink-0" />
                    </x-slot>
                </x-text-input>
                <x-input-error :messages="$errors->get('etec_code')" />
            </div>

            <div>
                <x-input-label for="password" value="Senha" />
                <x-password-input placeholder="Sua senha" required />
                <x-input-error :messages="$errors->get('password')" />
            </div>

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
