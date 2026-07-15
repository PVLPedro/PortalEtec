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

    <div class="flex w-full flex-col items-start gap-smaller">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <x-lucide-layout-dashboard class="shrink-0"></x-lucide-layout-dashboard>
            </x-slot>
            <span class="grow"> Visão Geral </span>
        </x-nav-link>

        {{-- <x-nav-link :href="route('subjects')" :active="request()->routeIs('subjects')"> --}}
        <x-nav-link
            :href="route('school-classes.index')"
            :active="request()->routeIs('school-classes.index')"
        >
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
        </x-nav-link>

        @if (auth()->user()->role === \App\Enums\Role::Coordenador)
            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                <x-slot name="icon">
                    <x-lucide-file-text class="shrink-0"></x-lucide-file-text>
                </x-slot>
                <span class="grow"> Usuários </span>
            </x-nav-link>
        @endif
    </div>
</aside>

@push ('scripts')
    <script></script>
@endpush
