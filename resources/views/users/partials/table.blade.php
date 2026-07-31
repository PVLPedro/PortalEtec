<div
    x-data="{
        selectionMode: false,
        hoveredRow: null,
        selected: [],
        schoolClassModal: false,
        newSchoolClass: false,
        deleteModal: false,
        deleteSelectedModal: false,
        idToDelete: 0,
        username: 'name',
        userRole: 'role',
    }"
    class="flex flex-col items-center gap-small rounded-small *:w-full"
>
    <div class="flex items-center justify-start gap-smaller">
        <button
            type="button"
            class="flex items-center gap-small rounded-small p-small"
            :class="selectionMode
                ? 'bg-accent text-text-white hover:bg-accent-hover'
                : 'bg-bg-primary text-text hover:bg-bg-primary-hover'"
            @click="
                selectionMode = !selectionMode;
                selected = [];
            "
        >
            <x-lucide-copy x-show="!selectionMode" class="text-inherit" />
            <x-lucide-copy-x x-show="selectionMode" x-cloak />
            <span x-show="!selectionMode">Selecionar usuários</span>
            <span x-show="selectionMode" x-cloak>Cancelar seleção</span>
        </button>

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
            <button
                type="button"
                class="group/tooltip relative rounded-small bg-bg-primary p-small hover:bg-bg-primary-hover"
                @click="selected = @json($usuarios->where('role', '!=', \App\Enums\Role::Coordenador)->pluck('id')->values())"
            >
                <x-icons.select-all />
                <x-tooltip> Selecionar todos </x-tooltip>
            </button>
            <button
                type="button"
                class="group/tooltip relative rounded-small bg-bg-primary p-small hover:bg-bg-primary-hover"
                @click="selected = []"
            >
                <x-icons.select-remove />
                <x-tooltip> Limpar seleção </x-tooltip>
            </button>
            <button
                type="button"
                class="group/tooltip relative rounded-small bg-bg-primary p-small hover:bg-bg-primary-hover"
                @click="selected = @json($usuarios->where('role', '!=', \App\Enums\Role::Coordenador)->pluck('id')->values()).filter(id => !selected.includes(id))"
            >
                <x-icons.select-invert />
                <x-tooltip> Inverter seleção </x-tooltip>
            </button>
        </div>

        <button
            type="button"
            class="items-center gap-small rounded-small p-small disabled:cursor-not-allowed"
            :class="[
                selectionMode ? 'flex' : 'hidden',
                selected.length > 0
                    ? 'bg-accent text-text-white hover:bg-accent-hover'
                    : 'bg-bg-primary-disabled text-text-disabled',
            ]"
            :disabled="selected.length == 0"
            @click="if (selected.length > 0) schoolClassModal = true;"
        >
            <x-lucide-book-plus />
            Adicionar à Turma
        </button>
        <x-backdrop
            x-show="schoolClassModal"
            x-cloak
            class=""
            @keydown.escape.window="schoolClassModal = false"
        >
            <form
                method="POST"
                action="{{ route('users.add-to-class') }}"
                class="[&>*:not(.keep-auto)]:w-full relative flex w-full max-w-130 flex-col items-center gap-regular rounded-large bg-bg-secondary p-large shadow-md"
                @click.outside="schoolClassModal = false"
            >
                @csrf

                <x-close-button @click="schoolClassModal = false" />

                <h3 class="py-smaller text-center font-semibold">Adicionar à Turma</h3>

                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="usuarios[]" :value="id" />
                </template>

                <x-input-label for="school_class_id"> Turma existente </x-input-label>
                <select
                    name="school_class_id"
                    id="school_class_id"
                    class="flex items-center gap-small rounded-small border border-border p-small text-text"
                    x-show="!newSchoolClass"
                >
                    <option value="">Selecione uma Turma</option>
                    @foreach ($schoolClasses as $schoolClass)
                        <option value="{{ $schoolClass->id }}">{{ $schoolClass->name }}</option>
                    @endforeach
                </select>

                <button
                    type="button"
                    @click="newSchoolClass = true"
                    x-show="!newSchoolClass"
                    class="flex items-center gap-small rounded-small bg-bg-primary p-small text-text hover:bg-bg-primary-hover"
                >
                    <x-lucide-plus />
                    Criar nova turma
                </button>

                <div x-show="newSchoolClass" x-cloak class="space-y-regular">
                    <div>
                        <x-input-label for="course_id"> Courso </x-input-label>
                        <select
                            name="course_id"
                            id="course_id"
                            class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                        >
                            <option value="">Selecione um curso</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="grade_id"> Série </x-input-label>
                        <select
                            name="grade_id"
                            id="grade_id"
                            class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                        >
                            <option value="">Selecione uma série</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="shift_id"> Turno </x-input-label>
                        <select
                            name="shift_id"
                            id="shift_id"
                            class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                        >
                            <option value="">Selecione um turno</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button
                    type="button"
                    @click="newSchoolClass = false"
                    x-show="newSchoolClass"
                    class="flex items-center gap-small rounded-small bg-bg-primary p-small text-text hover:bg-bg-primary-hover"
                >
                    <x-lucide-arrow-left />
                    Adicionar a uma existente
                </button>

                <div class="flex justify-between">
                    <button
                        type="button"
                        @click="schoolClassModal = false"
                        class="flex items-center gap-smaller rounded-small bg-bg-primary p-small text-text hover:bg-bg-primary-hover"
                    >
                        <x-lucide-x />
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex items-center gap-smaller rounded-small bg-accent p-small text-text-white hover:bg-accent-hover"
                    >
                        <x-lucide-check />
                        Confirmar
                    </button>
                </div>
            </form>
        </x-backdrop>
        <button
            type="button"
            class="items-center gap-small rounded-small p-small disabled:cursor-not-allowed"
            :class="[
                selectionMode ? 'flex' : 'hidden',
                selected.length > 0
                    ? 'bg-danger text-text-white hover:bg-danger-hover'
                    : 'bg-bg-primary-disabled text-text-disabled',
            ]"
            :disabled="selected.length == 0"
            @click="if (selected.length > 0) deleteSelectedModal = true;"
        >
            <x-lucide-trash-2 />
            Excluir selecionados
        </button>
        <x-backdrop
            x-show="deleteSelectedModal"
            x-cloak
            class=""
            @keydown.escape.window="deleteSelectedModal = false"
        >
            <form
                method="POST"
                action="{{ route('users.destroyMultiple') }}"
                class="[&>*:not(.keep-auto)]:w-full relative flex w-full max-w-130 flex-col items-center gap-regular rounded-large bg-bg-secondary p-large shadow-md"
                @click.outside="deleteSelectedModal = false"
                onsubmit="
                    return confirm(
                        'Excluir os usuários selecionados? Esta ação não pode ser desfeita.'
                    );
                "
            >
                @csrf
                @method ('DELETE')

                <x-close-button @click="deleteSelectedModal = false" />

                <h3 class="py-smaller text-center font-semibold">Exclusão de Usuários</h3>

                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id" />
                </template>

                <label for="bulk-password" class="text-sm font-medium text-secondary">
                    Confirme sua senha para excluir
                    <span x-text="selected.length"></span> usuário(s) selecionado(s):
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
                        @click="deleteSelectedModal = false"
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
                        Excluir selecionados
                    </button>
                </div>
            </form>
        </x-backdrop>
        <x-backdrop
            x-show="deleteModal"
            x-cloak
            class=""
            @keydown.escape.window="deleteModal = false"
        >
            <form
                method="POST"
                {{-- action="{{ route('users.destroy', $user) }}" --}}
                class="[&>*:not(.keep-auto)]:w-full relative flex w-full max-w-130 flex-col items-center gap-regular rounded-large bg-bg-secondary p-large shadow-md"
                @click.outside="deleteModal = false"
            >
                @csrf
                @method ('DELETE')

                <x-close-button @click="deleteModal = false" />

                <h3 class="py-smaller text-center font-semibold">Exclusão</h3>

                <label for="bulk_password_2" class="text-sm font-medium text-secondary">
                    Confirme sua senha para excluir o usuário
                    <span x-text="userRole" class="capitalize"></span>
                    <span x-text="username" class="capitalize"></span>
                </label>

                <input
                    id="bulk_password_2"
                    type="password"
                    name="password"
                    class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                    placeholder="Sua senha"
                />

                <div class="flex justify-between">
                    <button
                        type="button"
                        @click="deleteModal = false"
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
                        Excluir
                    </button>
                </div>
            </form>
        </x-backdrop>
    </div>

    <div
        class="relative grid size-full grid-cols-[auto_repeat(2,minmax(0,1fr))_repeat(2,auto)] overflow-hidden rounded-regular border border-border"
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
            <span class="col-span-2 flex items-center justify-center p-large font-semibold"
                >Ações</span
            >
        </div>

        @foreach ($usuarios as $usuario)
            <div class="col-span-full grid grid-cols-subgrid border-t border-t-border">
                <label
                    for="{{ "user-checkbox" . $usuario->id }}"
                    class="relative flex items-center justify-center overflow-hidden"
                    :class="selectionMode ? 'w-auto opacity-100 p-large' : 'w-0 opacity-0 p-0'"
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
                        @click="deleteModal = true; idToDelete = {{ $usuario->id }}; username = @js($usuario->name); userRole = @js($usuario->role->value)"
                    >
                        <x-lucide-trash-2 />
                        <x-tooltip> Excluir </x-tooltip>
                    </span>
                @endif
            </div>
        @endforeach
    </div>
</div>
