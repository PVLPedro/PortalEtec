<x-guest-layout>
    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large"></x-theme>

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass ?? '' }}">Verificação cancelada</p>
        <p class="text-center text-sm">{{ $message }}</p>

        <a href="/" class="text-sm underline">Ir para o início</a>
    </section>
</x-guest-layout>
