<x-guest-layout>
    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large"></x-theme>

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass ?? '' }}">Primeiro acesso</p>
        <p class="text-center text-sm">Etapa 1 de 2: cadastre o seu e-mail institucional</p>

        @if ($cancelled)
            <p class="text-center text-sm">
                A confirmação do e-mail anterior foi cancelada. Informe o e-mail novamente.
            </p>
        @endif

        @if (session('status'))
            <p class="text-center text-sm">{{ session('status') }}</p>
        @endif

        <form
            method="POST"
            action="{{ route('first-access.email.store') }}"
            class="flex w-full flex-col gap-regular"
        >
            @csrf

            <div x-data='{ email: @js(old('email', '')) }'>
                <x-input-label for="email" value="E-mail institucional" />
                <x-text-input
                    id="email"
                    type="email"
                    name="email"
                    x-model="email"
                    placeholder="seunome@aluno.cps.sp.gov.br"
                    required
                    autofocus
                    autocomplete="email"
                />
                <p class="mt-1 text-xs text-secondary">
                    Nome gerado:
                    <span
                        class="font-mono"
                        x-text="email
                            ? email.trim().split('@')[0].normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase()
                            : '—'"
                    ></span>
                </p>
                <p class="mt-1 text-xs text-secondary">
                    Após confirmar o e-mail, seu nome e identificador são gerados
                    automaticamente. Não é preciso informá-los aqui.
                </p>
                <x-input-error :messages="$errors->get('email')" class="" />
            </div>

            <x-primary-button class="">Enviar código</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm underline">Sair</button>
        </form>
    </section>
</x-guest-layout>
