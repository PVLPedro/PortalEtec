<header class="t-0 sticky col-span-2 flex items-center justify-between text-text">
    <figure class="relative h-18 px-regular">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="h-full" />
        </a>
    </figure>
    <ul class="flex items-center justify-center gap-smaller p-regular">
        <x-etec-switcher />
        <x-theme></x-theme>
        <x-dropdown>
            <x-lucide-user />
            <x-slot name="trigger">
                <button class="">
                    <div>{{ Auth::user()->name }}</div>
                </button>
            </x-slot>

            <x-slot name="header">
                Opções
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    <x-lucide-user></x-lucide-user>
                    Perfil
                </x-dropdown-link>

                <x-dropdown-link :href="route('profile.edit')">
                    <x-lucide-bolt></x-lucide-bolt>
                    Configurações
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link
                        :href="route('logout')"
                        onclick="
                            event.preventDefault();
                            this.closest('form').submit();
                        "
                        class="text-red"
                    >
                        <x-lucide-log-out></x-lucide-log-out>
                        <span>Desconectar-se</span>
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </ul>
</header>

@push ('scripts')
    <script></script>
@endpush
