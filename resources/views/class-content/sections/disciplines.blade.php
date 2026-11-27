<div x-show="section == 'disciplinesSection'" x-cloak class="flex flex-col gap-regular">
    <div class="flex items-center justify-between gap-regular">
        <h3 class="text-lg font-semibold">Disciplinas</h3>

        @if ($classContent['canManageDisciplines'])
            <a
                href="{{ route('class.disciplines.create', $schoolClass) }}"
                class="rounded-small bg-accent px-regular py-small text-sm font-semibold text-white hover:bg-accent-hover"
            >
                + Adicionar disciplina
            </a>
        @endif
    </div>

    @forelse ($classContent['classDisciplines'] as $item)
        @php
            $itemTeachers = $classContent['disciplineTeachers'][$item->id];
        @endphp
        <div class="flex flex-col gap-small rounded-small border border-border p-regular">
            <div class="flex items-center justify-between gap-regular">
                <div class="flex flex-col">
                    <span class="font-semibold">{{ $item->discipline->name }}</span>
                    <span class="text-xs">
                        Cor: {{ $item->color->name }}
                        @if ($item->isCourseDiscipline())
                            &middot; disciplina de curso
                        @endif
                    </span>
                </div>

                <a
                    href="{{ route('school-class.show', [$schoolClass, 'secao' => 'atividades', 'disciplina' => $item->id]) }}"
                    class="text-sm underline"
                >
                    Ver atividades
                </a>
            </div>

            <div class="text-sm">
                <span class="font-semibold">Professores ({{ $itemTeachers->count() }}/3):</span>
                @forelse ($itemTeachers as $teacher)
                    <span>
                        {{ $teacher->name }}
                        @if ($teacher->side_id)
                            ({{ $classContent['sideNames'][$teacher->side_id] ?? '' }})
                        @endif
                        @unless ($loop->last)
                            ,
                        @endunless
                    </span>
                @empty
                    <span>nenhum professor vinculado</span>
                @endforelse
            </div>

            @if ($classContent['canManageDisciplines'])
                <div class="flex flex-wrap items-start gap-regular">
                    <a
                        href="{{ route('class.disciplines.edit', [$schoolClass, $item]) }}"
                        class="text-sm underline"
                    >
                        Editar
                    </a>
                    @include ('class-content.partials.delete-form', [
                        'action' => route('class.disciplines.destroy', [$schoolClass, $item]),
                        'label' => 'Excluir disciplina',
                    ])
                </div>
            @endif
        </div>
    @empty
        <p class="text-sm">Nenhuma disciplina cadastrada nesta turma ainda.</p>
    @endforelse
</div>
