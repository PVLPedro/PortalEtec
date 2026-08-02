<x-app-layout>
    <div class="space-y-larger">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">Turmas</h2>
            @if (auth()->user()->role === \App\Enums\Role::Coordenador ||
                auth()->user()->role === \App\Enums\Role::Professor)
                <a href="{{ route('school-classes.create') }}">
                    <x-primary-button class="bg-accent text-text-white hover:bg-accent-hover">
                        <x-lucide-plus />
                        <span>Criar Turma</span>
                    </x-primary-button>
                </a>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-large">
            @forelse ($schoolClasses as $schoolClass)
                <a
                    href="{{ route('school-classes.show', $schoolClass) }}"
                    class="grid grid-cols-[54px_1fr] gap-regular rounded-regular border border-border p-regular shadow-md hover:bg-bg-secondary-hover"
                >
                    <span
                        class="flex size-16 items-center justify-center rounded-small p-regular"
                        style="background-color: var(--color-{{ $schoolClass->color->code }}-bg); color: var(--color-{{ $schoolClass->color->code }})"
                    >
                        <x-dynamic-component
                            :component="'lucide-' . $schoolClass->icon->code"
                            class="size-full"
                        />
                    </span>
                    <span class="flex flex-col items-start gap-small">
                        <x-card-text>
                            <x-slot name="primary">
                                {{ $schoolClass->name }}
                            </x-slot>
                            <x-slot name="secondary">
                                {{ $schoolClass->etec->name }}
                            </x-slot>
                        </x-card-text>
                    </span>
                </a>
            @empty
                <p class="text-gray-500">Nenhuma turma criada ainda.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
