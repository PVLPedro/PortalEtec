@php
    $activityBlocks = [
        [
            'title' => 'Atividades',
            'items' => $classContent['activities'],
            'empty' => 'Nenhuma atividade com data de entrega.',
        ],
        [
            'title' => 'Materiais de apoio',
            'items' => $classContent['materials'],
            'empty' => 'Nenhum material de apoio publicado.',
        ],
    ];
@endphp

<div x-show="section == 'activitiesSection'" x-cloak class="flex flex-col gap-regular">
    <div class="flex flex-wrap items-center justify-between gap-regular">
        <h3 class="text-lg font-semibold">Atividades e materiais</h3>

        <div class="flex items-center gap-regular">
            <form method="GET" action="{{ route('school-class.show', $schoolClass) }}">
                <input type="hidden" name="secao" value="atividades" />
                <select
                    name="disciplina"
                    onchange="this.form.submit()"
                    class="rounded-small border border-border bg-bg-primary p-small text-sm text-text"
                >
                    <option value="">Todas as disciplinas</option>
                    @foreach ($classContent['classDisciplines'] as $item)
                        <option
                            value="{{ $item->id }}"
                            @selected ($classContent['selectedDiscipline'] === $item->id)
                        >
                            {{ $item->discipline->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if (count($classContent['manageActivityIds']) > 0)
                <a
                    href="{{ route('class.activities.create', $schoolClass) }}"
                    class="rounded-small bg-accent px-regular py-small text-sm font-semibold text-white hover:bg-accent-hover"
                >
                    + Nova atividade ou material
                </a>
            @endif
        </div>
    </div>

    @foreach ($activityBlocks as $block)
        <div class="flex flex-col gap-small">
            <h4 class="font-semibold">{{ $block['title'] }}</h4>

            @forelse ($block['items'] as $item)
                <div class="flex flex-col gap-small rounded-small border border-border p-regular">
                    <div class="flex flex-col">
                        <a
                            href="{{ route('class.activities.show', [$schoolClass, $item]) }}"
                            class="font-semibold underline"
                        >
                            {{ $item->title }}
                        </a>
                        <span class="text-xs">
                            {{ $item->schoolClassDiscipline->discipline->name }}
                            @if ($item->side)
                                &middot; {{ $item->side->name }}
                            @endif
                            @if ($item->deadline)
                                &middot; entrega até {{ $item->deadline->format('d/m/Y H:i') }}
                            @endif
                            &middot; {{ $item->files->count() }} arquivo(s)
                        </span>
                    </div>

                    @if (in_array($item->school_class_discipline_id, $classContent['manageActivityIds'], true))
                        <div class="flex flex-wrap items-start gap-regular">
                            <a
                                href="{{ route('class.activities.edit', [$schoolClass, $item]) }}"
                                class="text-sm underline"
                            >
                                Editar
                            </a>
                            @include ('class-content.partials.delete-form', [
                                'action' => route('class.activities.destroy', [$schoolClass, $item]),
                                'label' => 'Excluir',
                            ])
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-sm">{{ $block['empty'] }}</p>
            @endforelse
        </div>
    @endforeach
</div>
