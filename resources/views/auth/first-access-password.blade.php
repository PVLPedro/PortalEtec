<x-guest-layout>
    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large"></x-theme>

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass ?? '' }}">Primeiro acesso</p>
        <p class="text-center text-sm">Etapa 2 de 2: defina a sua nova senha</p>

        <form
            method="POST"
            action="{{ route('first-access.password.store') }}"
            class="flex w-full flex-col gap-regular"
        >
            @csrf

            <div>
                <x-input-label for="password" value="Nova senha" />
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Nova senha"
                    required
                    autofocus
                    autocomplete="new-password"
                />
                <x-input-error :messages="$errors->get('password')" class="" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Confirmar nova senha" />
                <x-text-input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Repita a nova senha"
                    required
                    autocomplete="new-password"
                />
            </div>

            <x-primary-button class="">Concluir</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm underline">Sair</button>
        </form>
    </section>
</x-guest-layout>
