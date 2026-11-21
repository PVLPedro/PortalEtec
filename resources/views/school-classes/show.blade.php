<x-app-layout>
    <style>
        :root {
            --color-school-class: var(--color-{{ $schoolClass->color->code }});
            --color-school-class-bg: var(--color-{{ $schoolClass->color->code }}-bg);
        }
    </style>
    @php
        $sections = [
            'disciplinas' => 'disciplinesSection',
            'atividades' => 'activitiesSection',
            'comunicados' => 'announcementsSection',
            'membros' => 'membersSection',
        ];
        $initialSection = $sections[request('secao')] ?? 'disciplinesSection';
        $sectionSlugs = array_flip($sections);

        $availableStudentsData = ($availableStudents ?? collect())->map(function ($student) use ($schoolClass) {
            $studentClass = $student->student?->schoolClass;
            return [
                'id' => $student->id,
                'name' => $student->name,
                'rm' => $student->etecs->first()?->pivot->rm ?? $student->student?->rm ?? '—',
                'grade_id' => $studentClass?->grade_id,
                'grade_name' => $studentClass?->grade?->name ?? 'Sem turma',
                'class_name' => $studentClass?->name ?? 'Sem turma',
                'already_in_class' => $student->student?->school_class_id === $schoolClass->id,
            ];
        })->values()->all();
    @endphp
    <div
        x-data="{
            editingModal: false,
            confirmDeleteModal: false,
            userToRemove: null,
            userNameToRemove: null,
            confirmUserRemove: false,
            selectionMode: false,
            hoveredRow: null,
            selected: [],
            removeSelectedModal: false,
            userName: 'name',
            userRole: 'role',
            section: '{{ $initialSection }}',
            slugToId: @js($sections),
            idToSlug: @js($sectionSlugs),
            addMembersModal: false,
            availableStudents: @js($availableStudentsData),
            addSelected: [],
            modalSearch: '',
            modalGradeFilter: '',
            modalOnlyWithoutClass: false,
            get filteredAvailableStudents() {
                let list = this.availableStudents.filter(s => !s.already_in_class);
                if (this.modalOnlyWithoutClass) {
                    list = list.filter(s => s.class_name === 'Sem turma');
                }
                if (this.modalGradeFilter) {
                    list = list.filter(s => String(s.grade_id) === String(this.modalGradeFilter));
                }
                if (this.modalSearch.trim()) {
                    const q = this.modalSearch.toLowerCase().trim();
                    list = list.filter(s => 
                        s.name.toLowerCase().includes(q) ||
                        String(s.rm).toLowerCase().includes(q) ||
                        s.class_name.toLowerCase().includes(q)
                    );
                }
                return list;
            },
            toggleSelectAllAvailable() {
                const visibleIds = this.filteredAvailableStudents.map(s => s.id);
                const allSelected = visibleIds.length > 0 && visibleIds.every(id => this.addSelected.includes(id));
                if (allSelected) {
                    this.addSelected = this.addSelected.filter(id => !visibleIds.includes(id));
                } else {
                    this.addSelected = Array.from(new Set([...this.addSelected, ...visibleIds]));
                }
            },
        }"
        x-init="
            $watch('section', (id) => {
                const url = new URL(window.location.href);
                url.searchParams.set('secao', idToSlug[id]);
                history.replaceState(null, '', url);
                $dispatch('section-changed', idToSlug[id]);
            })
        "
        x-on:set-section.window="section = slugToId[$event.detail] ?? section"
        class="grid size-full grid-rows-[auto_auto_1fr] gap-regular *:w-full"
    >
        <div class="flex items-center gap-regular">
            <x-back-link />
            <h2 class="flex-1 text-xl font-semibold">Turma</h2>
        </div>
        @if (session('status'))
            <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
        @endif

        @error ('password')
            <div class="mb-4 rounded bg-red-100 p-3 text-red-800">{{ $message }}</div>
        @enderror

        @if (auth()->user()->role === \App\Enums\Role::Coordenador)
            <div class="contents" id="modals">
                <x-app-modal
                    name="edit-class"
                    title="Edição da Turma"
                    :action="route('school-classes.update', $schoolClass)"
                >
                    @csrf
                    @method ('PUT')

                    <div class="space-y-regular">
                        <div>
                            <x-input-label for="course_id"> Curso </x-input-label>
                            <select
                                name="course_id"
                                id="course_id"
                                required
                                class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            >
                                @foreach ($courses as $course)
                                    <option
                                        value="{{ $course->id }}"
                                        @selected ($course->id === $schoolClass->course_id)
                                    >
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-secondary-text>
                                Atualmente: {{ $schoolClass->course->name }}
                            </x-secondary-text>
                        </div>
                        <div>
                            </select>
                            <x-input-label for="grade_id"> Série </x-input-label>
                            <select
                                name="grade_id"
                                id="grade_id"
                                required
                                class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            >
                                @foreach ($grades as $grade)
                                    <option
                                        value="{{ $grade->id }}"
                                        @selected ($grade->id === $schoolClass->grade_id)
                                    >
                                        {{ $grade->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-secondary-text>
                                Atualmente: {{ $schoolClass->grade->name }}
                            </x-secondary-text>
                        </div>
                        <div>
                            <x-input-label for="shift_id"> Turno </x-input-label>
                            <select
                                name="shift_id"
                                id="shift_id"
                                required
                                class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            >
                                @foreach ($shifts as $shift)
                                    <option
                                        value="{{ $shift->id }}"
                                        @selected ($shift->id === $schoolClass->shift_id)
                                    >
                                        {{ $shift->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-secondary-text>
                                Atualmente: {{ $schoolClass->shift->name }}
                            </x-secondary-text>
                        </div>
                        <div>
                            <x-input-label for="color_id" value="Cor" class="" />
                            <select
                                id="color_id"
                                name="color_id"
                                required
                                class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            >
                                <option value="">Selecione uma cor</option>
                                @foreach ($colors as $color)
                                    <option
                                        value="{{ $color->id }}"
                                        @selected ($color->id === $schoolClass->color_id)
                                    >
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('color_id')" class="" />
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <x-primary-button
                            type="button"
                            @click="open = false"
                            class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                        >
                            <x-lucide-x />
                            Cancelar
                        </x-primary-button>
                        <x-primary-button
                            type="button"
                            @click="
                                open = false;
                                $dispatch('open-modal', { name: 'delete-class' });
                            "
                            class="h-auto bg-danger text-text-white hover:bg-danger-hover"
                        >
                            <x-lucide-trash-2 />
                            Excluir
                        </x-primary-button>
                        <x-primary-button
                            type="submit"
                            class="bg-accent text-text-white hover:bg-accent-hover"
                        >
                            <x-lucide-check />
                            Salvar
                        </x-primary-button>
                    </div>
                </x-app-modal>

                <x-app-modal
                    name="delete-class"
                    title="Exclusão"
                    :action="route('school-classes.destroy', $schoolClass)"
                >
                    @method ('DELETE')

                    <label for="bulk_password" class="text-sm font-medium text-secondary">
                        Confirme sua senha para excluir a Turma {{ $schoolClass->name }}
                    </label>

                    <input
                        id="bulk_password"
                        type="password"
                        name="password"
                        class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                        placeholder="Sua senha"
                    />

                    <div class="flex justify-between">
                        <x-primary-button
                            type="button"
                            @click="open = false"
                            class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                        >
                            <x-lucide-x />
                            Cancelar
                        </x-primary-button>
                        <x-primary-button
                            type="submit"
                            class="bg-danger text-text-white hover:bg-danger-hover"
                        >
                            <x-lucide-trash-2 />
                            Excluir
                        </x-primary-button>
                    </div>
                </x-app-modal>

                <x-backdrop
                    x-show="confirmUserRemove"
                    x-cloak
                    @keydown.escape.window="confirmUserRemove = false"
                >
                    @php
                        $removeUserBaseAction = route('school-classes.remove-user', [$schoolClass, '__USER_ID__']);
                    @endphp
                    <x-form-modal
                        method="POST"
                        x-bind:action="'{{ $removeUserBaseAction }}'.replace('__USER_ID__', userToRemove)"
                        @click.outside="confirmUserRemove = false"
                    >
                        @csrf
                        @method ('DELETE')

                        <x-close-button @click="confirmUserRemove = false" />

                        <h3 class="py-smaller text-center font-semibold">Remover usuário</h3>

                        <label for="bulk_password2" class="text-sm font-medium text-secondary">
                            Confirme sua senha para remover o usuário
                            <span x-text="userNameToRemove" class="font-semibold capitalize"></span>
                            <span class="font-semibold"> da Turma {{ $schoolClass->name }} </span>
                        </label>

                        <input
                            id="bulk_password2"
                            type="password"
                            name="password"
                            class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            placeholder="Sua senha"
                        />

                        <div class="flex justify-between">
                            <x-primary-button
                                type="submit"
                                class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                            >
                                Confirmar
                            </x-primary-button>
                            <x-primary-button
                                type="button"
                                @click="confirmUserRemove = false"
                                class="bg-danger text-text-white hover:bg-danger-hover"
                            >
                                Cancelar
                            </x-primary-button>
                        </div>
                    </x-form-modal>
                </x-backdrop>

                <x-backdrop
                    x-show="removeSelectedModal"
                    x-cloak
                    class=""
                    @keydown.escape.window="removeSelectedModal = false"
                >
                    <x-form-modal
                        method="POST"
                        {{-- action="{{ route('') }}" --}}
                        {{--
                            Insira a rota em action para remover múltiplos.
                            Utilize um método semelhante ao de user.destroyMultiples, da UserManangementController,
                            que usa um array x-data de ids de usuários, presente na div global desta página.
                            A estrutura está quando pronta. Lembre-se de trocar o termo "destroy" por "remove".
                            Passe intruções após finalizar para eventuais ajustes.
                        --}}
                        @click.outside="removeSelectedModal = false"
                        onsubmit="return confirm('Remover os usuários selecionados?');"
                    >
                        @csrf
                        @method ('DELETE')

                        <x-close-button @click="removeSelectedModal = false" />

                        <h3 class="py-smaller text-center font-semibold">Remoção de Usuários</h3>

                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id" />
                        </template>

                        <label for="bulk-password" class="text-sm font-medium text-secondary">
                            Confirme sua senha para remover
                            <span
                                x-text="selected.length + ' usuário(s) selecionado(s)'"
                                class="font-semibold"
                            ></span>
                            <span class="font-semibold">da Turma {{ $schoolClass->name }}</span>
                        </label>
                        <input
                            id="bulk-password"
                            type="password"
                            name="password"
                            class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            placeholder="Sua senha"
                        />

                        <div class="flex justify-between">
                            <button
                                type="button"
                                @click="removeSelectedModal = false"
                                class="flex items-center gap-smaller rounded-small bg-bg-primary p-small text-text hover:bg-bg-primary-hover"
                            >
                                <x-lucide-x />
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="flex items-center gap-small rounded-small bg-danger p-small text-text-white hover:bg-danger-hover"
                            >
                                <x-lucide-user-minus />
                                Remover selecionados
                            </button>
                        </div>
                    </x-form-modal>
                </x-backdrop>

                <x-backdrop
                    x-show="addMembersModal"
                    x-cloak
                    @keydown.escape.window="addMembersModal = false"
                >
                    <div
                        class="relative flex w-full max-w-2xl max-h-[90vh] flex-col gap-regular rounded-large bg-bg-secondary p-large shadow-xl border border-border overflow-hidden"
                        @click.outside="addMembersModal = false"
                    >
                        <x-close-button @click="addMembersModal = false" />

                        <div class="flex flex-col gap-smaller">
                            <h3 class="text-lg font-semibold flex items-center gap-small">
                                <x-lucide-user-plus class="size-5 text-accent" />
                                Adicionar Alunos à Turma
                            </h3>
                            <p class="text-xs text-secondary">
                                Selecione os alunos da Etec que você deseja vincular à turma <strong>{{ $schoolClass->name }}</strong>.
                            </p>
                        </div>

                        <!-- Filtros do Modal -->
                        <div class="flex flex-col sm:flex-row gap-small">
                            <div class="flex-1 flex items-center gap-small rounded-small border border-border bg-bg-primary px-small py-smaller text-sm">
                                <x-lucide-user-search class="size-4 text-secondary shrink-0" />
                                <input
                                    type="text"
                                    x-model="modalSearch"
                                    placeholder="Pesquisar por nome ou RM..."
                                    class="w-full bg-transparent text-text outline-none text-sm placeholder:text-secondary"
                                />
                                <button
                                    type="button"
                                    x-show="modalSearch"
                                    @click="modalSearch = ''"
                                    class="text-secondary hover:text-text"
                                >
                                    <x-lucide-x class="size-4" />
                                </button>
                            </div>

                            <select
                                x-model="modalGradeFilter"
                                class="rounded-small border border-border bg-bg-primary p-smaller text-sm text-text"
                            >
                                <option value="">Todas as séries / anos</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-between text-xs text-secondary px-1">
                            <label class="flex items-center gap-smaller cursor-pointer hover:text-text">
                                <input
                                    type="checkbox"
                                    x-model="modalOnlyWithoutClass"
                                    class="size-4 rounded border-border"
                                />
                                <span>Apenas alunos sem turma</span>
                            </label>

                            <button
                                type="button"
                                @click="toggleSelectAllAvailable()"
                                class="text-accent hover:underline font-medium"
                            >
                                <span x-text="filteredAvailableStudents.length > 0 && filteredAvailableStudents.every(s => addSelected.includes(s.id)) ? 'Desmarcar visíveis' : 'Selecionar visíveis'"></span>
                            </button>
                        </div>

                        <!-- Lista de Alunos Disponíveis -->
                        <form
                            method="POST"
                            action="{{ route('users.add-to-classMultiple') }}"
                            class="flex flex-1 flex-col gap-regular overflow-hidden min-h-0"
                        >
                            @csrf
                            <input type="hidden" name="school_class_id" value="{{ $schoolClass->id }}" />

                            <div class="flex-1 overflow-y-auto divide-y divide-border rounded-small border border-border bg-bg-primary max-h-80">
                                <template x-for="student in filteredAvailableStudents" :key="student.id">
                                    <label class="flex items-center gap-small p-small hover:bg-bg-secondary-hover cursor-pointer transition-colors">
                                        <input
                                            type="checkbox"
                                            name="usuarios[]"
                                            :value="student.id"
                                            x-model="addSelected"
                                            class="size-5 rounded border-border checked:bg-accent hover:bg-accent-bg"
                                        />
                                        <div class="flex flex-1 flex-col sm:flex-row sm:items-center justify-between gap-1">
                                            <div>
                                                <span class="font-medium text-sm text-text block" x-text="student.name"></span>
                                                <span class="text-xs text-secondary" x-text="'RM: ' + student.rm"></span>
                                            </div>
                                            <div class="text-right">
                                                <span
                                                    class="text-xs px-2 py-0.5 rounded-full font-medium"
                                                    :class="student.class_name === 'Sem turma' ? 'bg-secondary/15 text-secondary' : 'bg-accent/15 text-accent'"
                                                    x-text="student.class_name"
                                                ></span>
                                            </div>
                                        </div>
                                    </label>
                                </template>

                                <div x-show="filteredAvailableStudents.length === 0" class="p-regular text-center text-sm text-secondary">
                                    Nenhum aluno disponível encontrado com os filtros aplicados.
                                </div>
                            </div>

                            <!-- Rodapé do Modal -->
                            <div class="flex items-center justify-between pt-small border-t border-border">
                                <div class="flex items-center gap-small">
                                    <span class="text-xs font-medium text-secondary">
                                        <strong class="text-text" x-text="addSelected.length"></strong> selecionado(s)
                                    </span>
                                    <a
                                        href="{{ route('users.index') }}"
                                        class="text-xs text-secondary hover:text-accent hover:underline flex items-center gap-1 ml-2"
                                    >
                                        <span>Ir para Usuários</span>
                                        <x-lucide-square-arrow-out-up-right class="size-3" />
                                    </a>
                                </div>

                                <div class="flex items-center gap-small">
                                    <x-primary-button
                                        type="button"
                                        @click="addMembersModal = false"
                                        class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                                    >
                                        Cancelar
                                    </x-primary-button>

                                    <x-primary-button
                                        type="submit"
                                        x-bind:disabled="addSelected.length === 0"
                                        class="bg-accent text-text-white hover:bg-accent-hover disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <x-lucide-user-plus class="size-4" />
                                        <span>Adicionar</span>
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </x-backdrop>
            </div>
        @endif
        <x-card class="flex flex-col gap-regular">
            <div class="flex gap-regular">
                <div class="flex items-center">
                    <div
                        class="flex size-16 items-center justify-center rounded-small bg-(--color-school-class-bg) p-regular text-(--color-school-class)"
                    >
                        <x-dynamic-component
                            :component="'lucide-' . $schoolClass->icon->code"
                            class="size-8"
                        />
                    </div>
                </div>
                <div class="flex flex-1 flex-col">
                    <h2 class="text-2xl font-semibold">{{ $schoolClass->name }}</h2>
                    <h2 class="flex-1 text-xl font-medium text-secondary">
                        {{ $schoolClass->etec->name }}
                    </h2>
                </div>
                @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                    <div>
                        <x-primary-button
                            type="button"
                            @click="$dispatch('open-modal', { name: 'edit-class' })"
                            class="h-auto bg-bg-primary text-text hover:bg-bg-primary-hover"
                        >
                            <x-lucide-settings />
                            Opções
                        </x-primary-button>
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-small">
                <button
                    @click="section = 'disciplinesSection'"
                    class="flex items-center gap-smaller text-left text-sm/tight font-medium text-secondary hover:text-(--color-school-class)"
                >
                    <x-lucide-book-marked class="size-4" />
                </button>
                <span class="size-1 rounded-full bg-secondary"></span>
                <button
                    @click="section = 'membersSection'"
                    class="flex items-center gap-smaller text-left text-sm/tight font-medium text-secondary hover:text-(--color-school-class)"
                >
                    <x-lucide-users class="size-4" />
                </button>
            </div>
        </x-card>
        <x-card class="flex flex-col gap-regular">
            <div
                class="flex gap-regular *:flex *:flex-1 *:gap-small *:rounded-t-regular *:border-b-4 *:p-regular *:text-center *:font-semibold *:uppercase *:hover:bg-bg-tertiary-hover"
            >
                <button
                    @click="section = 'disciplinesSection'"
                    :class="section == 'disciplinesSection'
                        ? 'border-b-(--color-school-class) text-text'
                        : 'border-b-border text-secondary'"
                    class="transition-colors duration-200"
                >
                    <x-lucide-book-marked
                        x-bind:class="
                            section == 'disciplinesSection'
                                ? 'text-(--color-school-class)'
                                : 'text-secondary'
                        "
                        class="transition-colors duration-200"
                    />
                    Disciplinas
                </button>
                <button
                    @click="section = 'activitiesSection'"
                    :class="section == 'activitiesSection'
                        ? 'border-b-(--color-school-class) text-text'
                        : 'border-b-border text-secondary'"
                    class="transition-colors duration-200"
                >
                    <x-lucide-notebook
                        x-bind:class="
                            section == 'activitiesSection'
                                ? 'text-(--color-school-class)'
                                : 'text-secondary'
                        "
                        class="transition-colors duration-200"
                    />
                    Atividades
                </button>
                <button
                    @click="section = 'announcementsSection'"
                    :class="section == 'announcementsSection'
                        ? 'border-b-(--color-school-class) text-text'
                        : 'border-b-border text-secondary'"
                    class="transition-colors duration-200"
                >
                    <x-lucide-message-square-text
                        x-bind:class="
                            section == 'announcementsSection'
                                ? 'text-(--color-school-class)'
                                : 'text-secondary'
                        "
                        class="transition-colors duration-200"
                    />
                    Comunicados
                </button>
                <button
                    @click="section = 'membersSection'"
                    :class="section == 'membersSection'
                        ? 'border-b-(--color-school-class) text-text'
                        : 'border-b-border text-secondary'"
                    class="transition-colors duration-200"
                >
                    <x-lucide-users
                        x-bind:class="
                            section == 'membersSection'
                                ? 'text-(--color-school-class)'
                                : 'text-secondary'
                        "
                        class="transition-colors duration-200"
                    />
                    Membros
                </button>
            </div>

            @include ('class-content.sections.disciplines')
            @include ('class-content.sections.activities')
            @include ('class-content.sections.announcements')
            @include ('school-classes.partials.show-members')
        </x-card>
    </div>

    {{-- @forelse ($schoolClass->users as $usuario)
        <tr class="border-b">
            <td class="p-3">{{ $usuario->name }}</td>
            <td class="p-3">{{ $usuario->role->value }}</td>
        </tr>
    @empty
        <tr>
            <td class="p-3 text-gray-500" colspan="3">
                Nenhum membro nesta turma ainda.
            </td>
        </tr>
    @endforelse --}}
</x-app-layout>
