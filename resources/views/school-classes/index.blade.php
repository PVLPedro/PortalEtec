<x-app-layout>
    <div class="space-y-larger">
        <div class="flex items-center gap-regular">
            <h2 class="flex-1 text-xl font-semibold">Turmas</h2>
            @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                <x-primary-link
                    href="{{ route('school-classes.create') }}"
                    class="bg-accent text-text-white hover:bg-accent-hover"
                >
                    <x-lucide-plus />
                    <span>Criar Turma</span>
                </x-primary-link>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-large">
            @forelse ($schoolClasses as $schoolClass)
                <a href="{{ route('school-classes.show', $schoolClass) }}" class="">
                    <x-card
                        class="grid grid-cols-[auto_1fr] gap-regular hover:bg-bg-tertiary-hover"
                    >
                        <span
                            class="flex size-16 items-center justify-center rounded-small p-regular"
                            style="background-color: var(--color-{{ $schoolClass->color->code }}-bg); color: var(--color-{{ $schoolClass->color->code }})"
                        >
                            <x-dynamic-component
                                :component="'lucide-' . $schoolClass->icon->code"
                                class="size-8"
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
                    </x-card>
                </a>
            @empty
                <div class="flex items-center gap-regular">
                    <p class="text-secondary">Nenhuma turma criada ainda.</p>
                    @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                        <x-form-link href="{{ route('school-classes.create') }}">
                            Criar Turma
                            <x-slot name="icon">
                                <x-lucide-square-arrow-out-up-right
                                    class="size-4 stroke-3"
                                ></x-lucide-square-arrow-out-up-right>
                            </x-slot>
                        </x-form-link>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
