<x-app-layout>
    <div class="">
        <div class="mx-auto max-w-5xl">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-semibold">Turmas</h2>
                @if (auth()->user()->role === \App\Enums\Role::Coordenador ||
                    auth()->user()->role === \App\Enums\Role::Professor)
                    <a
                        href="{{ route('school-classes.create') }}"
                        class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-white"
                    >
                        + Nova Turma
                    </a>
                    </button>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 sm:grid-cols-2">
                @forelse ($schoolClasses as $schoolClass)
                    <a
                        href="{{ route('school-classes.show', $schoolClass) }}"
                        class="block rounded-lg bg-white p-4 shadow transition hover:shadow-md"
                    >
                        <div class="font-semibold">{{ $schoolClass->nome }}</div>
                        <div class="mt-1 text-sm text-gray-500">
                            {{ $schoolClass->users_count }} {{
                                Str::plural(
                                    'membro',
                                    $schoolClass->users_count,
                                )
                            }}
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500">Nenhuma turma criada ainda.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
