<x-guest-layout>
    <x-auth-session-status class="" :status="session('status')" />

    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large"></x-theme>

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass }}">Entrar como Aluno</p>

        <x-form-link href="{{ url('/') }}">
            Voltar para a seleção
            <x-slot name="icon">
                <x-lucide-square-arrow-out-up-right
                    class="size-4 stroke-3"
                ></x-lucide-square-arrow-out-up-right>
            </x-slot>
        </x-form-link>

        <form
            method="POST"
            action="{{ route('login.student') }}"
            class="flex w-full flex-col gap-regular"
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
                />
                <x-input-error :messages="$errors->get('rm')" class="" />
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
                />
                <x-input-error :messages="$errors->get('etec_code')" class="" />
            </div>

            <div>
                <x-input-label for="password" value="Senha" />
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Sua senha"
                    required
                    autocomplete="current-password"
                />
                <x-input-error :messages="$errors->get('password')" class="" />
            </div>

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
                    <label for="remember_me" class="flex-1 text-sm">Lembrar de mim</label>
                </div>
                <div>
                    <x-primary-button class="">Entrar</x-primary-button>
                </div>
            </div>
        </form>
    </section>
</x-guest-layout>
