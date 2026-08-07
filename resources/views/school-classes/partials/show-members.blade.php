<div
    x-show="section == 'membersSection'"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    class="flex flex-col gap-regular justify-self-start"
>
    <div class="flex items-center gap-regular">
        <h2 class="flex-1 text-lg font-semibold">Membros da Turma</h2>
    </div>
    <div class="flex items-center gap-regular">
        <div
            class="flex flex-1 items-center justify-start gap-small rounded-small border border-border bg-bg-secondary p-small text-base text-text"
        >
            <label for="member-search">
                <x-lucide-user-search />
            </label>
            <input
                placeholder="Pesquisar Membro (Nome, Cargo ou Email)"
                type="text"
                class="flex-1 border-b-2 border-b-transparent text-text outline-0 placeholder:text-secondary focus:border-b-(--color-school-class)"
                id="member-search"
            />
        </div>
        @if (auth()->user()->role === \App\Enums\Role::Coordenador)
            <x-primary-link href="" class="bg-accent text-text-white hover:bg-accent-hover">
                <x-lucide-user-plus />
                <span>Adicionar Membros</span>
            </x-primary-link>
        @endif
    </div>
    <div class="flex flex-col items-center justify-center gap-small">
        <span class="flex items-center gap-smaller text-sm/tight font-medium">
            Páginas
            <x-dot />
            Exibindo 5 Membros
        </span>
        <div class="flex justify-center gap-small">
            {{-- @foreach ($pages as $page) --}}
            <span
                class="flex size-8 items-center justify-center rounded-small bg-(--color-school-class) p-regular text-sm/tight font-semibold text-text-white"
            >
                {{-- {{ $page->number }} --}}
                1
            </span>
            <span
                class="flex size-8 items-center justify-center rounded-small bg-bg-primary p-regular text-sm/tight font-medium text-text hover:bg-bg-primary-hover active:bg-(--color-school-class) active:font-semibold active:text-text-white"
            >
                {{-- {{ $page->number }} --}}
                2
            </span>
            {{-- @endforeach --}}
        </div>
    </div>
    <div
        class="relative grid size-full max-h-200 grid-cols-[auto_1fr_repeat(2,auto)] rounded-regular border border-border"
    >
        <div class="col-span-full grid grid-cols-subgrid">
            @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                <div class="col-span-full flex items-center justify-start gap-smaller p-large">
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
        </div>
        <div class="col-span-full grid grid-cols-subgrid bg-(--color-school-class-bg)">
            <span class="col-start-2 flex flex-col justify-center p-large">
                <x-card-text>
                    <x-slot name="primary">
                        Nome
                        <x-dot />
                        Cargo
                    </x-slot>
                    <x-slot name="secondary">
                        Email
                    </x-slot>
                </x-card-text>
            </span>
        </div>

        @forelse ($schoolClass->users as $usuario)
            <div
                class="col-span-full grid grid-cols-subgrid gap-smaller border-t border-t-border p-smaller"
            >
                <label
                    for="{{ "user-checkbox" . $usuario->id }}"
                    class="relative flex h-16 items-center justify-center overflow-hidden transition-all"
                    :class="selectionMode
                        ? 'w-16 opacity-100 border-r border-r-border'
                        : 'w-0 opacity-0 border-0'"
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
                        class="flex flex-col justify-center rounded-regular p-regular"
                        @if ($usuario->role->value != 'coordenador')
                            :class="selectionMode &&
                            'hover:bg-bg-secondary-hover hover:cursor-pointer'"
                        @endif
                    >
                        <x-card-text>
                            <x-slot name="primary">
                                {{ $usuario->name }}
                                <x-dot />
                                {{ $usuario->role->value }}
                            </x-slot>
                            <x-slot name="secondary">
                                {{ $usuario->email }}
                            </x-slot>
                        </x-card-text>
                    </label>
                    @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                        <a
                            href="{{ route('users.edit', $usuario) }}"
                            class="group/tooltip relative flex items-center justify-center rounded-regular font-semibold text-border uppercase group-hover:text-accent hover:bg-bg-secondary-hover"
                        >
                            <span class="flex size-16 items-center justify-center">
                                <x-lucide-square-pen class="size-5" />
                                <x-tooltip> Editar </x-tooltip>
                            </span>
                        </a>
                        <span
                            class="group/tooltip items-cen ter relative flex justify-center rounded-regular font-semibold text-border uppercase group-hover:text-danger hover:bg-bg-secondary-hover"
                            @click="userToRemove = {{ $usuario->id }}; userNameToRemove = '{{ $usuario->name }}'; confirmUserRemove = true"
                        >
                            <span class="flex size-16 items-center justify-center">
                                <x-lucide-user-minus class="size-5" />
                                <x-tooltip> Remover </x-tooltip>
                            </span>
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full flex items-center gap-regular p-regular">
                <p class="text-secondary">Nenhum Membro na Turma ainda.</p>
                @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                    <x-form-link href="">
                        Adicionar Membros
                        <x-slot name="icon">
                            <x-lucide-square-arrow-out-up-right class="size-4 stroke-3" />
                        </x-slot>
                    </x-form-link>
                @endif
            </div>
        @endforelse
    </div>
</div>
