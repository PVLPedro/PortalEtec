<x-app-layout>
    <style>
        :root {
            --color-school-class: var(--color-{{ $schoolClass->color->code }});
            --color-school-class-bg: var(--color-{{ $schoolClass->color->code }}-bg);
        }
    </style>
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
            section: 'membersSection',
        }"
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
                <x-backdrop
                    x-show="editingModal"
                    x-cloak
                    class=""
                    @keydown.escape.window="editingModal = false"
                >
                    <x-form-modal
                        method="POST"
                        action="{{ route('school-classes.update', $schoolClass) }}"
                        @click.outside="editingModal = false"
                    >
                        @csrf
                        @method ('PUT')

                        <x-close-button @click="editingModal = false" />

                        <h3 class="py-smaller text-center font-semibold">Editar Turma</h3>

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
                        </div>

                        <div class="flex justify-between">
                            <x-primary-button
                                type="button"
                                @click="editingModal = false"
                                class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                            >
                                <x-lucide-x />
                                Cancelar
                            </x-primary-button>
                            <x-primary-button
                                type="submit"
                                class="bg-accent text-text-white hover:bg-accent-hover"
                            >
                                <x-lucide-check />
                                Salvar
                            </x-primary-button>
                        </div>
                    </x-form-modal>
                </x-backdrop>

                <x-backdrop
                    x-show="confirmDeleteModal"
                    x-cloak
                    class=""
                    @keydown.escape.window="confirmDeleteModal = false"
                >
                    <x-form-modal
                        method="POST"
                        action="{{ route('school-classes.destroy', $schoolClass) }}"
                        x-data=""
                        @submit="
                            if (!confirmDeleteModal) {
                                $event.preventDefault();
                                confirmDeleteModal = true;
                            }
                        "
                        @click.outside="confirmDeleteModal = false"
                    >
                        @csrf
                        @method ('DELETE')

                        <x-close-button @click="confirmDeleteModal = false" />

                        <h3 class="py-smaller text-center font-semibold">Exclusão</h3>

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
                                @click="confirmDeleteModal = false"
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
                    </x-form-modal>
                </x-backdrop>

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
                    <div
                        class="hidden gap-small *:relative *:flex *:items-center *:justify-center *:p-small *:text-secondary *:hover:text-accent"
                    >
                        <span class="group/tooltip">
                            <x-lucide-graduation-cap />
                            <x-tooltip> Curso: {{ $schoolClass->course->name }} </x-tooltip>
                        </span>
                        <span class="group/tooltip">
                            <x-lucide-alarm-clock />
                            <x-tooltip> Período: {{ $schoolClass->shift->name }} </x-tooltip>
                        </span>
                        <span class="group/tooltip">
                            <x-lucide-calendar-fold />
                            <x-tooltip> {{ $schoolClass->grade->name }} </x-tooltip>
                        </span>
                        <span class="group/tooltip">
                            <x-lucide-university />
                            <x-tooltip> {{ $schoolClass->etec->name }} </x-tooltip>
                        </span>
                    </div>
                </div>
                @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                    <div class="flex items-start gap-small">
                        <x-primary-link
                            href="{{ route('school-classes.edit', $schoolClass) }}"
                            class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                        >
                            <x-lucide-settings />
                            Opções
                        </x-primary-link>
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-small">
                @php
                    $disciplinesCount = 0;
                    // foreach ($schoolClass->disciplines as $disciplina) {
                    //     $disciplinesCount++;
                    // }
                    $usersCount = 0;
                    foreach ($schoolClass->users as $usuario) {
                        $usersCount++;
                    }
                @endphp
                <button
                    @click="section = 'disciplinesSection'"
                    class="flex items-center gap-smaller text-left text-sm/tight font-medium text-secondary hover:text-accent"
                >
                    <x-lucide-book-marked class="size-4" />
                    Disciplinas {{ $disciplinesCount }}
                </button>
                <span class="size-1 rounded-full bg-secondary"></span>
                <button
                    @click="section = 'membersSection'"
                    class="flex items-center gap-smaller text-left text-sm/tight font-medium text-secondary hover:text-accent"
                >
                    <x-lucide-users class="size-4" />
                    Membros {{ $usersCount }}
                </button>
            </div>
        </x-card>
        <x-card class="flex flex-col gap-regular">
            <div
                class="flex gap-regular border-b border-b-border pb-regular *:flex *:flex-1 *:gap-small *:rounded-regular *:p-regular *:text-center *:font-semibold *:uppercase"
            >
                <button
                    @click="section = 'disciplinesSection'"
                    :class="section == 'disciplinesSection'
                        ? 'bg-(--color-school-class) text-text-white'
                        : 'bg-bg-primary text-text hover:bg-bg-primary-hover'"
                >
                    <x-lucide-book-marked />
                    Disciplinas
                </button>
                <button
                    @click="section = 'membersSection'"
                    :class="section == 'membersSection'
                        ? 'bg-(--color-school-class) text-text-white'
                        : 'bg-bg-primary text-text hover:bg-bg-primary-hover'"
                >
                    <x-lucide-users />
                    Membros
                </button>
                <button
                    @click="section = 'announcementSection'"
                    :class="section == 'announcementSection'
                        ? 'bg-(--color-school-class) text-text-white'
                        : 'bg-bg-primary text-text hover:bg-bg-primary-hover'"
                >
                    <x-lucide-message-square-text />
                    Comunicados
                </button>
            </div>
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
