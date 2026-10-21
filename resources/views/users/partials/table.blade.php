<div
    x-data="{
        perPage: 10,
        currentPage: 1,
        totalUsers: {{ $usuarios->count() }},
        get totalPages() {
            return Math.max(1, Math.ceil(this.totalUsers / this.perPage));
        },
        get rangeStart() {
            return this.totalUsers === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1;
        },
        get rangeEnd() {
            return Math.min(this.currentPage * this.perPage, this.totalUsers);
        },
        setPerPage(value) {
            this.perPage = value;
            this.currentPage = 1;
        },
        goToPage(page) {
            this.currentPage = Math.min(Math.max(page, 1), this.totalPages);
        },
        prevPage() {
            this.goToPage(this.currentPage - 1);
        },
        nextPage() {
            this.goToPage(this.currentPage + 1);
        },
    }"
    class="flex flex-col gap-regular"
>
    <div class="flex flex-col items-center justify-center gap-small">
        <span class="flex items-center gap-smaller text-sm/tight font-medium">
            Páginas
            <x-dot />
            <span
                x-text="
                    totalUsers === 0
                        ? 'Nenhum usuário'
                        : `Exibindo ${rangeStart}-${rangeEnd} de ${totalUsers} Usuários`
                "
            ></span>
            <x-dot />
            <span class="flex items-center gap-smaller">
                <button
                    type="button"
                    class="flex size-8 items-center justify-center rounded-small font-semibold"
                    x-bind:class="
                        perPage === 10
                            ? 'bg-accent text-text-white'
                            : 'bg-bg-primary text-text hover:bg-bg-primary-hover'
                    "
                    @click="setPerPage(10)"
                >
                    10
                </button>
                <button
                    type="button"
                    class="flex size-8 items-center justify-center rounded-small font-semibold"
                    x-bind:class="
                        perPage === 20
                            ? 'bg-accent text-text-white'
                            : 'bg-bg-primary text-text hover:bg-bg-primary-hover'
                    "
                    @click="setPerPage(20)"
                >
                    20
                </button>
            </span>
        </span>

        <div class="flex items-center justify-center gap-small">
            <button
                type="button"
                class="flex size-8 items-center justify-center rounded-small bg-bg-primary text-text hover:bg-bg-primary-hover disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:bg-bg-primary"
                @click="prevPage()"
                x-bind:disabled="currentPage === 1"
            >
                <x-lucide-chevron-left class="size-4" />
            </button>

            <template x-for="page in totalPages" :key="page">
                <span
                    class="flex size-8 cursor-pointer items-center justify-center rounded-small p-regular text-sm/tight font-medium"
                    x-bind:class="
                        page === currentPage
                            ? 'bg-accent font-semibold text-text-white'
                            : 'bg-bg-primary text-text hover:bg-bg-primary-hover'
                    "
                    x-text="page"
                    @click="goToPage(page)"
                ></span>
            </template>

            <button
                type="button"
                class="flex size-8 items-center justify-center rounded-small bg-bg-primary text-text hover:bg-bg-primary-hover disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:bg-bg-primary"
                @click="nextPage()"
                x-bind:disabled="currentPage === totalPages"
            >
                <x-lucide-chevron-right class="size-4" />
            </button>
        </div>
    </div>

    <div
        class="relative grid size-full max-h-200 grid-cols-[auto_1fr_repeat(3,auto)] rounded-regular border border-border"
    >
        <div class="col-span-full grid grid-cols-subgrid bg-accent-bg">
            <span class="col-start-2 flex flex-col justify-center p-large">
                <x-card-text>
                    <x-slot name="primary">
                        Nome
                        <x-dot />
                        Cargo
                    </x-slot>
                    <x-slot name="secondary">
                        Email / RM
                    </x-slot>
                </x-card-text>
            </span>
        </div>

        @forelse ($usuarios as $usuario)
            @php
                $isCoordinator = $usuario->role === \App\Enums\Role::Coordenador;
                $isStudent = $usuario->role === \App\Enums\Role::Aluno;
            @endphp

            <div
                class="col-span-full grid grid-cols-subgrid gap-smaller border-t border-t-border p-smaller"
                x-show="Math.ceil({{ $loop->iteration }} / perPage) === currentPage"
            >
                <label
                    for="{{ 'user-checkbox' . $usuario->id }}"
                    class="relative flex h-16 items-center justify-center overflow-hidden transition-all"
                    :class="selectionMode
                        ? 'w-16 opacity-100 border-r border-r-border'
                        : 'w-0 opacity-0 border-0'"
                >
                    @unless ($isCoordinator)
                        <input
                            type="checkbox"
                            id="{{ 'user-checkbox' . $usuario->id }}"
                            class="peer size-6 appearance-none rounded-small border border-border shadow-md checked:bg-accent hover:bg-accent-bg checked:hover:bg-accent-hover"
                            value="{{ $usuario->id }}"
                            :disabled="!selectionMode"
                            x-model="selected"
                        />
                        <x-lucide-check
                            class="pointer-events-none absolute top-1/2 left-1/2 hidden size-4 -translate-1/2 stroke-3 peer-checked:block peer-checked:text-text-white peer-hover:block peer-hover:text-text peer-hover:opacity-50 peer-checked:peer-hover:text-text-white peer-checked:peer-hover:opacity-100"
                        />
                    @endunless
                </label>

                <div class="group contents">
                    <label
                        for="{{ 'user-checkbox' . $usuario->id }}"
                        class="flex flex-col justify-center rounded-regular p-regular"
                        @unless ($isCoordinator)
                            :class="selectionMode &&
                            'hover:bg-bg-secondary-hover hover:cursor-pointer'"
                        @endunless
                    >
                        <x-card-text>
                            <x-slot name="primary">
                                {{ $usuario->name }}
                                <x-dot />
                                <span class="capitalize">{{ $usuario->role->value }}</span>
                            </x-slot>
                            <x-slot name="secondary">
                                @if ($isStudent)
                                    RM {{
                                        $usuario->etecs->first()?->pivot
                                            ->rm
                                    }}
                                @else
                                    {{ $usuario->email }}
                                @endif
                            </x-slot>
                        </x-card-text>
                    </label>

                    @unless ($isCoordinator)
                        <a
                            href="{{ route('users.edit', $usuario) }}"
                            class="group/tooltip relative flex items-center justify-center rounded-regular font-semibold text-border uppercase group-hover:text-accent hover:bg-bg-secondary-hover"
                        >
                            <span class="flex size-16 items-center justify-center">
                                <x-lucide-square-pen class="size-5" />
                                <x-tooltip> Editar </x-tooltip>
                            </span>
                        </a>
                        @if ($isStudent)
                            <button
                                type="button"
                                class="group/tooltip relative flex items-center justify-center rounded-regular font-semibold text-border uppercase group-hover:text-accent hover:bg-bg-secondary-hover"
                                @click="userToAdd = {{ $usuario->id }}; userNameToAdd = @js($usuario->name); userRoleToAdd = @js($usuario->role->value); addModal = true"
                            >
                                <span class="flex size-16 items-center justify-center">
                                    <x-lucide-book-plus class="size-5" />
                                    <x-tooltip> Adicionar à Turma </x-tooltip>
                                </span>
                            </button>
                        @else
                            <span aria-hidden="true"></span>
                        @endif
                        <button
                            type="button"
                            class="group/tooltip relative flex items-center justify-center rounded-regular font-semibold text-border uppercase group-hover:text-danger hover:bg-bg-secondary-hover"
                            @click="userToDelete = {{ $usuario->id }}; userNameToDelete = @js($usuario->name); userRoleToDelete = @js($usuario->role->value); deleteModal = true"
                        >
                            <span class="flex size-16 items-center justify-center">
                                <x-lucide-trash-2 class="size-5" />
                                <x-tooltip> Excluir </x-tooltip>
                            </span>
                        </button>
                    @endunless
                </div>
            </div>
        @empty
            <div class="col-span-full flex items-center gap-regular p-regular">
                <p class="text-secondary">Nenhum usuário encontrado.</p>
            </div>
        @endforelse
    </div>
</div>
