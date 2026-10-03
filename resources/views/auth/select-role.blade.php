<x-guest-layout>
    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large"></x-theme>

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass }}">Bem-vindo(a) ao Portal Etec!</p>
        <p class="text-center text-sm {{ $generalTextClass }}">Como você quer entrar?</p>

        <div class="flex w-full flex-col gap-regular">
            @php
                $cardClass =
                    'flex w-full flex-col gap-1 rounded-regular border border-border p-regular shadow-md hover:bg-accent-bg focus:ring-2 focus:ring-accent focus:outline-none';
            @endphp

            <a href="{{ route('login.student') }}" class="{{ $cardClass }}">
                <span class="font-medium">Entrar como Aluno</span>
                <span class="text-sm">Use seu RM, o código da Etec e sua senha</span>
            </a>

            <a href="{{ route('login') }}" class="{{ $cardClass }}">
                <span class="font-medium">Entrar como Professor</span>
                <span class="text-sm">Use seu e-mail institucional e senha</span>
            </a>

            <a href="{{ route('login') }}" class="{{ $cardClass }}">
                <span class="font-medium">Entrar como Coordenador</span>
                <span class="text-sm">Use seu e-mail institucional e senha</span>
            </a>
        </div>
    </section>
</x-guest-layout>
