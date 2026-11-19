<x-guest-layout>
    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large text-text" />

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass }}">Bem-vindo(a) ao Portal Etec!</p>

        <div class="flex w-full flex-col gap-regular *:flex-1">
            <x-form-text>Como deseja entrar?</x-form-text>
        </div>

        <div class="flex w-full flex-col gap-regular text-text">
            <a
                href="{{ route('login.student') }}"
                class="grid grid-cols-[auto_1fr] grid-rows-2 gap-x-regular rounded-regular border-2 border-border bg-bg-primary p-regular shadow-md hover:bg-bg-primary-hover"
            >
                <x-lucide-graduation-cap
                    class="row-span-2 size-8 self-center justify-self-center"
                />
                <span class="font-medium">Entrar como Aluno</span>
                <span class="text-sm font-medium text-secondary"
                    >Use seu RM, o código da Etec e sua senha</span
                >
            </a>
            <a
                href="{{ route('login') }}"
                class="grid grid-cols-[auto_1fr] grid-rows-2 gap-x-regular rounded-regular border-2 border-border bg-bg-primary p-regular shadow-md hover:bg-bg-primary-hover"
            >
                <x-lucide-id-card class="row-span-2 size-8 self-center justify-self-center" />
                <span class="font-medium">Entrar como Professor ou Coordenador</span>
                <span class="text-sm font-medium text-secondary"
                    >Use seu e-mail institucional e senha</span
                >
            </a>
        </div>
    </section>
</x-guest-layout>
