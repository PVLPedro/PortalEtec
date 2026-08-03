<div x-show="section == 'membersSection'" class="flex flex-col gap-regular">
    <div class="flex items-center gap-regular">
        <h2 class="flex-1 text-lg font-semibold">Membros da Turma</h2>
        @if (auth()->user()->role === \App\Enums\Role::Coordenador ||
            auth()->user()->role === \App\Enums\Role::Professor)
            <x-primary-link href="" class="bg-accent text-text-white hover:bg-accent-hover">
                <x-lucide-user-plus />
                <span>Adicionar Usuários</span>
            </x-primary-link>
        @endif
    </div>
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
                <x-lucide-user-minus />
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
            <span class="col-span-2 flex items-center p-large font-semibold">Cargo</span>
        </div>

        @forelse ($schoolClass->users as $usuario)
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
                @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                    <a
                        href="{{ route('users.edit', $usuario) }}"
                        class="group/tooltip relative flex items-center justify-center rounded-regular p-large font-semibold uppercase hover:bg-bg-secondary-hover"
                    >
                        <x-lucide-square-pen />
                        <x-tooltip> Editar </x-tooltip>
                    </a>
                    <span
                        class="group/tooltip relative flex items-center justify-center rounded-regular p-large font-semibold text-danger uppercase hover:bg-bg-secondary-hover"
                        @click="userToRemove = {{ $usuario->id }}; userNameToRemove = '{{ $usuario->name }}'; confirmUserRemove = true"
                    >
                        <x-lucide-user-minus />
                        <x-tooltip> Remover </x-tooltip>
                    </span>
                @endif
            </div>
        @empty
            <div class="col-span-full flex items-center gap-regular p-regular">
                <p class="text-secondary">Nenhum usuário na turma ainda.</p>
                <x-form-link href="">
                    Adicionar usuários
                    <x-slot name="icon">
                        <x-lucide-square-arrow-out-up-right
                            class="size-4 stroke-3"
                        ></x-lucide-square-arrow-out-up-right>
                    </x-slot>
                </x-form-link>
            </div>
        @endforelse
    </div>
</div>
