<x-guest-layout>
    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large"></x-theme>

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass ?? '' }}">{{ $title }}</p>

        @if ($step)
            <p class="text-center text-sm">{{ $step }}</p>
        @endif

        @if (session('status'))
            <p class="text-center text-sm">{{ session('status') }}</p>
        @endif

        @if ($state === 'active')
            <p class="text-center text-sm">{{ $intro }}</p>

            <form
                method="POST"
                action="{{ route($confirmRoute) }}"
                class="flex w-full flex-col gap-regular"
            >
                @csrf

                <div>
                    <x-input-label for="code" value="Código de 6 dígitos" />
                    <x-text-input
                        id="code"
                        type="text"
                        name="code"
                        inputmode="numeric"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        autocomplete="one-time-code"
                        placeholder="000000"
                        required
                        autofocus
                    />
                    <x-input-error :messages="$errors->get('code')" class="" />
                </div>

                <x-primary-button class="">Confirmar</x-primary-button>
            </form>

            <form
                method="POST"
                action="{{ route($sendRoute) }}"
                x-data="{ left: {{ (int) $secondsLeft }} }"
                x-init="setInterval(() => { if (left > 0) left-- }, 1000)"
                class="flex w-full flex-col items-center gap-small"
            >
                @csrf
                <button
                    type="submit"
                    class="text-sm underline disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="left > 0"
                >
                    <span x-show="left > 0" x-text="'Reenviar código em ' + left + 's'"></span>
                    <span x-show="left <= 0">Reenviar código</span>
                </button>
            </form>
        @elseif ($state === 'none')
            <p class="text-center text-sm">{{ $introNone }}</p>

            <form method="POST" action="{{ route($sendRoute) }}" class="flex w-full flex-col gap-regular">
                @csrf
                <x-input-error :messages="$errors->get('code')" class="" />
                <x-primary-button class="">Enviar código</x-primary-button>
            </form>
        @else
            <p class="text-center text-sm">
                Esta tentativa foi cancelada pelo titular do e-mail. Saia e faça login novamente para
                receber um novo código.
            </p>
        @endif

        @if ($changeRoute)
            <form method="POST" action="{{ route($changeRoute) }}">
                @csrf
                <button type="submit" class="text-sm underline">Usar outro e-mail</button>
            </form>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm underline">Sair</button>
        </form>
    </section>
</x-guest-layout>
