<div x-show="section == 'announcementsSection'" x-cloak class="flex flex-col gap-regular">
    <div class="flex items-center justify-between gap-regular">
        <h3 class="text-lg font-semibold">Comunicados</h3>

        @if ($classContent['canManageAnnouncements'])
            <a
                href="{{ route('class.announcements.create', $schoolClass) }}"
                class="rounded-small bg-accent px-regular py-small text-sm font-semibold text-white hover:bg-accent-hover"
            >
                + Novo comunicado
            </a>
        @endif
    </div>

    @forelse ($classContent['announcements'] as $announcement)
        <article class="flex flex-col gap-small rounded-small border border-border p-regular">
            <div class="flex flex-col">
                <h4 class="font-semibold">{{ $announcement->title }}</h4>
                <span class="text-xs">
                    {{ $announcement->author?->name ?? 'Autor removido' }}
                    &middot; {{ $announcement->created_at->format('d/m/Y H:i') }}
                </span>
            </div>

            <p class="text-sm whitespace-pre-line">{{ $announcement->body }}</p>

            @if ($classContent['canManageAnnouncements'])
                <div class="flex flex-wrap items-start gap-regular">
                    <a
                        href="{{ route('class.announcements.edit', [$schoolClass, $announcement]) }}"
                        class="text-sm underline"
                    >
                        Editar
                    </a>
                    @include ('class-content.partials.delete-form', [
                        'action' => route('class.announcements.destroy', [$schoolClass, $announcement]),
                        'label' => 'Excluir comunicado',
                    ])
                </div>
            @endif
        </article>
    @empty
        <p class="text-sm">Nenhum comunicado publicado ainda.</p>
    @endforelse
</div>
