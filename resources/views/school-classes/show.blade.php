<x-app-layout>
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
        }"
        class="flex w-full flex-col items-center gap-regular *:w-full"
    >
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
                                <x-lucide-trash-2 />
                                Remover selecionados
                            </button>
                        </div>
                    </x-form-modal>
                </x-backdrop>
            </div>
        @endif
        <div class="grid grid-cols-[auto_1fr] gap-x-large">
            <div
                class="row-span-3 flex size-32 items-center justify-center rounded-large p-regular"
                style="background-color: var(--color-{{ $schoolClass->color->code }}-bg); color: var(--color-{{ $schoolClass->color->code }})"
            >
                <x-dynamic-component
                    :component="'lucide-' . $schoolClass->icon->code"
                    class="size-16"
                />
            </div>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold">{{ $schoolClass->name }}</h1>

                @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                    <div class="flex items-center gap-small justify-self-end">
                        <x-primary-button
                            @click="editingModal = !editingModal"
                            class="bg-accent text-text-white hover:bg-accent-hover"
                        >
                            <x-lucide-pencil-line />
                            Editar
                        </x-primary-button>

                        <x-primary-button
                            @click="confirmDeleteModal = !confirmDeleteModal"
                            class="bg-danger text-text-white hover:bg-danger-hover"
                        >
                            <x-lucide-trash-2 />
                            Excluir turma
                        </x-primary-button>
                    </div>
                @endif
            </div>
            <div class="flex items-center">
                <h2 class="text-xl font-medium text-secondary">{{ $schoolClass->etec->name }}</h2>
            </div>
            <div
                class="flex items-center gap-small *:relative *:flex *:h-full *:items-center *:justify-center *:p-small *:text-secondary *:hover:text-accent"
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
        <div class="flex flex-col items-center gap-small rounded-small *:w-full">
            @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                <div class="flex items-center justify-start gap-smaller">
                    <x-primary-button
                        type="button"
                        class="flex items-center gap-small rounded-small p-small"
                        x-bind:class="
                            selectionMode
                                ? 'bg-accent text-text-white hover:bg-accent-hover'
                                : 'bg-bg-primary text-text hover:bg-bg-primary-hover'
                        "
                        @click="
                            selectionMode = !selectionMode;
                            selected = [];
                        "
                    >
                        <x-lucide-copy x-show="!selectionMode" class="text-inherit" />
                        <x-lucide-copy-x x-show="selectionMode" x-cloak />
                        <span x-show="!selectionMode">Selecionar usuários</span>
                        <span x-show="selectionMode" x-cloak>Cancelar seleção</span>
                    </x-primary-button>

                    <div
                        x-show="selectionMode"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="flex flex-1 items-center gap-smaller"
                    >
                        <x-primary-button
                            type="button"
                            class="group/tooltip relative rounded-small bg-bg-primary p-small hover:bg-bg-primary-hover"
                            {{-- @click="selected = @json($schoolClass->users->where('role', '!=', \App\Enums\Role::Coordenador)->pluck('id')->values())" --}}
                        >
                            <x-icons.select-all />
                            <x-tooltip> Selecionar todos </x-tooltip>
                        </x-primary-button>
                        <x-primary-button
                            type="button"
                            class="group/tooltip relative rounded-small bg-bg-primary p-small hover:bg-bg-primary-hover"
                            @click="selected = []"
                        >
                            <x-icons.select-remove />
                            <x-tooltip> Limpar seleção </x-tooltip>
                        </x-primary-button>
                        <x-primary-button
                            type="button"
                            class="group/tooltip relative rounded-small bg-bg-primary p-small hover:bg-bg-primary-hover"
                            {{-- @click="selected = @json($schoolClass->users->where('role', '!=', \App\Enums\Role::Coordenador)->pluck('id')->values()).filter(id => !selected.includes(id))" --}}
                        >
                            <x-icons.select-invert />
                            <x-tooltip> Inverter seleção </x-tooltip>
                        </x-primary-button>
                    </div>

                    <x-primary-button
                        type="button"
                        class="items-center gap-small rounded-small p-small disabled:cursor-not-allowed"
                        x-bind:class="[
                            selectionMode ? 'flex' : 'hidden',
                            selected.length > 0
                                ? 'bg-danger text-text-white hover:bg-danger-hover'
                                : 'bg-bg-primary-disabled text-text-disabled',
                        ]"
                        x-bind:disabled="selected.length == 0"
                        @click="if (selected.length > 0) removeSelectedModal = true;"
                    >
                        <x-lucide-trash-2 />
                        Remover selecionados
                    </x-primary-button>
                </div>
            @endif
            <div
                class="relative grid size-full grid-cols-[auto_repeat(2,minmax(0,1fr))_repeat(2,auto)] rounded-regular border border-border"
            >
                <div class="col-span-full grid grid-cols-subgrid bg-accent-bg">
                    <span class="col-span-2 flex flex-col justify-center p-large">
                        <x-card-text>
                            <x-slot name="primary">
                                Nome
                            </x-slot>
                            <x-slot name="secondary">
                                Email
                            </x-slot>
                        </x-card-text>
                    </span>
                    <span class="flex items-center p-large font-semibold">Cargo</span>
                    @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                        <span
                            class="col-span-2 flex items-center justify-center p-large font-semibold"
                            >Ações</span
                        >
                    @endif
                </div>

                @forelse ($schoolClass->users as $usuario)
                    <div class="col-span-full grid grid-cols-subgrid border-t border-t-border">
                        <label
                            for="{{ "user-checkbox" . $usuario->id }}"
                            class="relative flex items-center justify-center overflow-hidden"
                            :class="selectionMode
                                ? 'w-auto opacity-100 p-large'
                                : 'w-0 opacity-0 p-0'"
                        >
                            @if ($usuario->role !== \App\Enums\Role::Coordenador)
                                <input
                                    type="checkbox"
                                    id="{{ "user-checkbox" . $usuario->id }}"
                                    class="peer size-6 appearance-none rounded-small border border-border shadow-md checked:bg-accent hover:bg-accent-bg checked:hover:bg-accent-hover"
                                    value="{{ $usuario->id }}"
                                    :disabled="!selectionMode"
                                    x-model="selected"
                                />
                                <x-lucide-check
                                    class="pointer-events-none absolute top-1/2 left-1/2 hidden size-4 -translate-1/2 stroke-3 peer-checked:block peer-checked:text-text-white peer-hover:block peer-hover:text-text peer-hover:opacity-50 peer-checked:peer-hover:text-text-white peer-checked:peer-hover:opacity-100"
                                />
                            @endif
                        </label>
                        <div class="group contents">
                            <label
                                for="{{ "user-checkbox" . $usuario->id }}"
                                class="flex flex-col justify-center p-large"
                                @if ($usuario->role->value != 'coordenador')
                                    :class="selectionMode &&
                                    'group-hover:bg-bg-secondary-hover hover:cursor-pointer'"
                                @endif
                            >
                                <x-card-text>
                                    <x-slot name="primary">
                                        {{ $usuario->name }}
                                    </x-slot>
                                    <x-slot name="secondary">
                                        {{ $usuario->email }}
                                    </x-slot>
                                </x-card-text>
                            </label>
                            <label
                                for="{{ "user-checkbox" . $usuario->id }}"
                                class="flex items-center p-large capitalize"
                                @if ($usuario->role->value != 'coordenador')
                                    :class="selectionMode &&
                                    'group-hover:bg-bg-secondary-hover hover:cursor-pointer'"
                                @endif
                                >{{ $usuario->role->value }}
                            </label>
                        </div>
                        @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                            <a
                                href="{{ route('users.edit', $usuario) }}"
                                class="group/tooltip relative flex items-center justify-center p-large font-semibold hover:bg-bg-secondary-hover"
                            >
                                <x-lucide-square-pen />
                                <x-tooltip> Editar </x-tooltip>
                            </a>
                            @if ($usuario->role->value != 'coordenador')
                                <span
                                    class="group/tooltip relative flex items-center justify-center p-large font-semibold text-danger hover:bg-bg-secondary-hover"
                                    @click="userToRemove = {{ $usuario->id }}; userNameToRemove = '{{ $usuario->name }}'; confirmUserRemove = true"
                                >
                                    <x-lucide-trash-2 />
                                    <x-tooltip> Remover </x-tooltip>
                                </span>
                            @endif
                        @endif
                    </div>
                @empty
                    <div class="col-span-full grid grid-cols-subgrid border-t border-t-border">
                        <span class="col-span-full font-semibold">Nenhum usuário na turma</span>
                    </div>
                @endforelse
            </div>
        </div>
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
