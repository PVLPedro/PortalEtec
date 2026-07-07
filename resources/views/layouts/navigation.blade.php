<aside
    id="sidebar"
    class="sticky flex h-full w-54 flex-col gap-large self-start overflow-auto rounded-large border border-border bg-bg-secondary p-regular text-nowrap text-text"
>
    <button
        id="toggle-sidebar"
        class="flex items-center justify-between gap-regular overflow-hidden rounded-regular border border-border bg-bg-primary p-regular text-end hover:bg-bg-primary-hover"
    >
        <span class="text-lg/tight">Menu</span>
        <x-lucide-menu></x-lucide-menu>
    </button>
    <div class="flex flex-col items-start gap-smaller">
        @if (auth()->user()->role === \App\Enums\Role::Aluno)
            <x-nav-link
                :href="route('aluno.dashboard')"
                :active="request()->routeIs('aluno.dashboard')"
            >
                <x-slot name="icon">
                    <x-lucide-layout-dashboard></x-lucide-layout-dashboard>
                </x-slot>
                Visão Geral
            </x-nav-link>
        @elseif (auth()->user()->role === \App\Enums\Role::Professor)
            <x-nav-link
                :href="route('professor.dashboard')"
                :active="request()->routeIs('professor.dashboard')"
            >
                <x-slot name="icon">
                    <x-lucide-layout-dashboard></x-lucide-layout-dashboard>
                </x-slot>
                Visão Geral
            </x-nav-link>
        @elseif (auth()->user()->role === \App\Enums\Role::Coordenador)
            <x-nav-link
                :href="route('coordenador.dashboard')"
                :active="request()->routeIs('coordenador.dashboard')"
            >
                <x-slot name="icon">
                    <x-lucide-layout-dashboard></x-lucide-layout-dashboard>
                </x-slot>
                Visão Geral
            </x-nav-link>
        @endif

        @if (auth()->user()->role === \App\Enums\Role::Aluno)
            <x-nav-link
                :href="route('aluno.dashboard')"
                :active="request()->routeIs('aluno.activities')"
            >
                <x-slot name="icon">
                    <x-lucide-file-text></x-lucide-file-text>
                </x-slot>
                Atividades
            </x-nav-link>
        @elseif (auth()->user()->role === \App\Enums\Role::Professor)
            <x-nav-link
                :href="route('professor.dashboard')"
                :active="request()->routeIs('professor.activities')"
            >
                <x-slot name="icon">
                    <x-lucide-file-text></x-lucide-file-text>
                </x-slot>
                Atividades
            </x-nav-link>
        @endif
    </div>
</aside>

@push ('scripts')
    <script>
        // const toggleSidebarButton = getById('toggle-sidebar');

        // toggleSidebarButton.addEventListener('click', toggleSidebar);

        // document.addEventListener('keydown', function (event) {
        //     if (event.shiftKey && event.key.toLowerCase() === 's') {
        //         toggleSidebar();
        //     }
        // });

        // function toggleSidebar() {
        //     let currentSidebar = localStorage.getItem('sidebar');

        //     switch (currentSidebar) {
        //         case 'show':
        //             localStorage.setItem('sidebar', 'hidden');
        //             break;
        //         case 'hidden':
        //             localStorage.setItem('sidebar', 'show');
        //             break;
        //         default:
        //             localStorage.setItem('sidebar', 'show');
        //             break;
        //     }
        //     updateSidebar();
        // }

        // function updateSidebar() {
        //     const sidebar = getById('sidebar');

        //     let mode = localStorage.getItem('sidebar');
        //     if (mode == 'show') {
        //         sidebar.classList.remove('hidden');
        //     }
        //     if (mode == 'hidden') {
        //         sidebar.classList.add('hidden');
        //     }
        // }
    </script>
@endpush
