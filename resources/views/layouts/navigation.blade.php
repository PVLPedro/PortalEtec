<aside
    id="sidebar"
    class="sticky flex h-full w-54 flex-col gap-large self-start overflow-auto rounded-large border border-border bg-bg-secondary p-regular text-nowrap text-text transition-[width] duration-300"
>
    <button
        id="toggle-sidebar"
        class="flex w-full items-center justify-end gap-regular overflow-hidden rounded-regular border border-border bg-bg-primary p-regular text-start hover:bg-bg-primary-hover"
    >
        <span class="grow text-lg/tight">Menu</span>
        <x-lucide-menu class="shrink-0"></x-lucide-menu>
    </button>
<<<<<<< HEAD
    <div class="flex flex-col items-start gap-smaller">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <x-lucide-layout-dashboard></x-lucide-layout-dashboard>
            </x-slot>
            Visão Geral
        </x-nav-link>

        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('activities')">
            <x-slot name="icon">
                <x-lucide-file-text></x-lucide-file-text>
            </x-slot>
            Atividades
=======
    <div class="flex w-full flex-col items-start gap-smaller">
        {{-- <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"> --}}
        <x-nav-link>
            <x-slot name="icon">
                <x-lucide-layout-dashboard class="shrink-0"></x-lucide-layout-dashboard>
            </x-slot>
            <span class="grow"> Visão Geral </span>
        </x-nav-link>
        {{-- <x-nav-link :href="route('subjects')" :active="request()->routeIs('subjects')"> --}}
        <x-nav-link>
            <x-slot name="icon">
                <x-lucide-book-marked class="shrink-0"></x-lucide-book-marked>
            </x-slot>
            <span class="grow"> Disciplinas </span>
        </x-nav-link>
        {{-- <x-nav-link :href="route('activities')" :active="request()->routeIs('activities')"> --}}
        <x-nav-link>
            <x-slot name="icon">
                <x-lucide-file-text class="shrink-0"></x-lucide-file-text>
            </x-slot>
            <span class="grow"> Atividades </span>
>>>>>>> 3bc010b314046a0db29ca454314eefd60398a442
        </x-nav-link>
    </div>
</aside>

@push ('scripts')
    <script>
        const toggleSidebarButton = getById('toggle-sidebar');

        toggleSidebarButton.addEventListener('click', toggleSidebar);

        document.addEventListener('keydown', function (event) {
            if (event.shiftKey && event.key.toLowerCase() === 's') {
                toggleSidebar();
            }
        });

        function toggleSidebar() {
            let currentSidebar = localStorage.getItem('sidebar');

            switch (currentSidebar) {
                case 'show':
                    localStorage.setItem('sidebar', 'hidden');
                    break;
                case 'hidden':
                    localStorage.setItem('sidebar', 'show');
                    break;
                default:
                    localStorage.setItem('sidebar', 'show');
                    break;
            }
            updateSidebar();
        }

        function updateSidebar() {
            const sidebar = getById('sidebar');

            let mode = localStorage.getItem('sidebar');
            if (mode == 'show') {
                sidebar.classList.add('w-54');
                sidebar.classList.remove('w-18.5');
            }
            if (mode == 'hidden') {
                sidebar.classList.remove('w-54');
                sidebar.classList.add('w-18.5');
            }
        }
    </script>
@endpush
