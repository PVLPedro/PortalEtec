<x-app-layout>
    <div class="flex max-w-3xl flex-col gap-regular">
        @include ('class-content.partials.back', ['section' => 'atividades'])

        <div class="flex flex-col gap-smaller">
            <span class="text-xs font-semibold uppercase">
                {{ $activity->isMaterial() ? 'Material de apoio' : 'Atividade' }}
            </span>
            <h1 class="text-lg font-semibold">{{ $activity->title }}</h1>
            <span class="text-sm">
                {{ $activity->schoolClassDiscipline->discipline->name }}
                &middot; {{ $activity->author?->name ?? 'Autor removido' }}
                &middot; {{ $activity->created_at->format('d/m/Y H:i') }}
                @if ($activity->side)
                    &middot; {{ $activity->side->name }}
                @endif
            </span>
            @if ($activity->deadline)
                <span class="text-sm font-semibold">
                    Entrega até {{ $activity->deadline->format('d/m/Y H:i') }}
                </span>
            @endif
        </div>

        @if ($activity->description)
            <p class="text-sm whitespace-pre-line">{{ $activity->description }}</p>
        @endif

        <section class="flex flex-col gap-small">
            <h2 class="font-semibold">Arquivos</h2>
            @forelse ($activity->files as $file)
                <a
                    href="{{ route('class.activities.files.download', $file) }}"
                    class="w-fit text-sm underline"
                >
                    {{ $file->original_name }} ({{ number_format($file->size / 1024, 0, ',', '.') }} KB)
                </a>
            @empty
                <p class="text-sm">Nenhum arquivo anexado.</p>
            @endforelse
        </section>

        <section class="flex flex-col gap-small">
            <h2 class="font-semibold">Comentários</h2>
            <p class="text-sm">
                {{ $activity->comments_enabled ? 'Comentários habilitados. A área de comentários será liberada em breve.' : 'Os comentários estão desabilitados nesta atividade.' }}
            </p>
        </section>

        @if ($canManage)
            <div class="flex flex-wrap items-start gap-regular">
                <a
                    href="{{ route('class.activities.edit', [$schoolClass, $activity]) }}"
                    class="text-sm underline"
                >
                    Editar
                </a>
                @include ('class-content.partials.delete-form', [
                    'action' => route('class.activities.destroy', [$schoolClass, $activity]),
                    'label' => 'Excluir',
                ])
            </div>
        @endif
    </div>
</x-app-layout>
