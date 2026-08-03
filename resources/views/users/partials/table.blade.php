<div
    class="relative grid size-full grid-cols-[auto_repeat(2,minmax(0,1fr))_auto] overflow-hidden rounded-regular border border-border"
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
        <span class="col-span-2 flex items-center p-large font-semibold">Cargo</span>
    </div>

    @foreach ($usuarios as $usuario)
        <div class="col-span-full grid grid-cols-subgrid border-t border-t-border">
            <label
                for="{{ "user-checkbox" . $usuario->id }}"
                class="relative flex items-center justify-center overflow-hidden"
                :class="selectionMode
                    ? 'w-auto opacity-100 p-large border-r border-r-border'
                    : 'w-0 opacity-0 p-0 border-0'"
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
                    class="flex flex-col justify-center rounded-l-regular p-large"
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
                    class="flex items-center rounded-r-regular p-large capitalize"
                    @if ($usuario->role->value != 'coordenador')
                        :class="selectionMode &&
                        'group-hover:bg-bg-secondary-hover hover:cursor-pointer'"
                    @endif
                    >{{ $usuario->role->value }}
                </label>
            </div>
            @if ($usuario->role->value != 'coordenador')
                <div class="flex items-center *:h-full">
                    <a
                        href="{{ route('users.edit', $usuario) }}"
                        class="group/tooltip relative flex items-center justify-center rounded-regular bg-bg-secondary p-large text-text uppercase hover:bg-bg-secondary-hover"
                    >
                        <x-lucide-pencil-line />
                        <x-tooltip> Editar </x-tooltip>
                    </a>
                    <button
                        class="group/tooltip relative flex items-center justify-center rounded-regular bg-bg-secondary p-large text-text uppercase hover:bg-bg-secondary-hover"
                        @click="userToAdd = {{ $usuario->id }}; userNameToAdd = '{{ $usuario->name }}'; userRoleToAdd = '{{ ($usuario->role->value) }}'; addModal = true"
                    >
                        <x-lucide-book-plus />
                        <x-tooltip> Adicionar à Turma </x-tooltip>
                    </button>
                    <button
                        class="group/tooltip relative flex items-center justify-center rounded-regular bg-bg-secondary p-large text-danger uppercase hover:bg-bg-secondary-hover"
                        @click="userToDelete = {{ $usuario->id }}; userNameToDelete = '{{ $usuario->name }}'; userRoleToDelete = '{{ ($usuario->role->value) }}'; deleteModal = true"
                    >
                        <x-lucide-trash-2 />
                        <x-tooltip> Excluir </x-tooltip>
                    </button>
                </div>
            @endif
        </div>
    @endforeach
</div>
