<div
    x-data="{
        selectionMode: false,
        hoveredRow: null,
        selected: [],
        schoolClassModal: false,
        newSchoolClass: false,
        deleteSelectedModal: false,
    }"
    class="relative grid size-full grid-cols-[repeat(3,minmax(0,1fr))_repeat(2,auto)] p-small"
>
    <div class="col-span-full flex items-center justify-start gap-smaller">
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
        <div
            x-show="schoolClassModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @keydown.escape.window="schoolClassModal = false"
        >
            <form
                method="POST"
                action="{{ route('users.add-to-class') }}"
                class="[&>*:not(.keep-auto)]:w-full relative flex w-full max-w-120 flex-col items-center gap-regular rounded-large bg-bg-secondary p-large shadow-md"
                @click.outside="schoolClassModal = false"
            >
                @csrf

                <x-close-button @click="schoolClassModal = false" />

                <h3 class="py-smaller text-center font-semibold">Adicionar à Turma</h3>

                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="usuarios[]" :value="id" />
                </template>

                <select
                    name="school_class_id"
                    class="flex items-center gap-small rounded-small border border-border p-small text-text"
                    x-show="!newSchoolClass"
                >
                    <option value="">Selecione uma Turma</option>
                    @foreach ($schoolClasses as $schoolClass)
                        <option value="{{ $schoolClass->id }}">{{ $schoolClass->nome }}</option>
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
                    <select
                        name="nova_turma[course_id]"
                        class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                    >
                        <option value="">Selecione um curso</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                    <select
                        name="nova_turma[grade_id]"
                        class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                    >
                        <option value="">Selecione uma série</option>
                        @foreach ($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                    <select
                        name="nova_turma[shift_id]"
                        class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                    >
                        <option value="">Selecione um turno</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
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
        </div>
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
        <div
            x-show="deleteSelectedModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @keydown.escape.window="deleteSelectedModal = false"
        >
            <form
                method="POST"
                action="{{ route('users.destroyMultiple') }}"
                class="[&>*:not(.keep-auto)]:w-full relative flex w-full max-w-120 flex-col items-center gap-regular rounded-large bg-bg-secondary p-large shadow-md"
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
        </div>
    </div>

    <span class="p-small text-center font-semibold">Nome</span>
    <span class="p-small text-center font-semibold">Email</span>
    <span class="p-small text-center font-semibold">Cargo</span>
    <span class="p-small text-center font-semibold">Ações</span>
    <span class=""></span>

    @foreach ($usuarios as $usuario)
        <label
            for="{{ "user-checkbox" . $usuario->id }}"
            class="flex items-center rounded-l-small p-small capitalize"
            @mouseenter="hoveredRow = {{ $usuario->id }}"
            @mouseleave="hoveredRow = null"
            :class="hoveredRow === {{ $usuario->id }} && 'bg-bg-secondary-hover'"
            >{{ $usuario->name }}</label
        >
        <label
            for="{{ "user-checkbox" . $usuario->id }}"
            class="flex items-center p-small"
            @mouseenter="hoveredRow = {{ $usuario->id }}"
            @mouseleave="hoveredRow = null"
            :class="hoveredRow === {{ $usuario->id }} && 'bg-bg-secondary-hover'"
            >{{ $usuario->email }}</label
        >
        <label
            for="{{ "user-checkbox" . $usuario->id }}"
            class="flex items-center rounded-r-small p-small capitalize"
            @mouseenter="hoveredRow = {{ $usuario->id }}"
            @mouseleave="hoveredRow = null"
            :class="hoveredRow === {{ $usuario->id }} && 'bg-bg-secondary-hover'"
            >{{ $usuario->role->value }}
            @if ($usuario->rm)
                {{ $usuario->rm }}
            @endif
        </label>
        <a
            href="{{ route('users.edit', $usuario) }}"
            class="group/tooltip relative flex items-center justify-center rounded-small p-small font-semibold hover:bg-bg-secondary-hover"
        >
            <x-lucide-square-pen />
            <x-tooltip> Editar </x-tooltip>
        </a>
        <label
            for="{{ "user-checkbox" . $usuario->id }}"
            class="relative flex items-center justify-center overflow-hidden transition-all duration-300 ease-in-out"
            :class="selectionMode ? 'w-8 opacity-100' : 'w-0 opacity-0'"
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
    @endforeach
</div>
