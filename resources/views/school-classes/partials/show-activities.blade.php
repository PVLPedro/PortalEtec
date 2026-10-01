<div
    x-show="section == 'activitiesSection'"
    x-data="{
        perPage: 10,
        currentPage: 1,
        totalUsers: {{ $schoolClass->students->count() + $schoolClass->teachers->count() }},
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
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    class="flex flex-col gap-regular"
>
    <div class="flex items-center gap-regular">
        <h2 class="flex-1 text-lg font-semibold">Atividades da Turma</h2>
    </div>
    <div class="flex items-center gap-regular">
        <div
            class="flex flex-1 items-center justify-start gap-small rounded-small border border-border bg-bg-secondary p-small text-base text-text"
        >
            <label for="member-search">
                <x-lucide-file-search-corner />
            </label>
            <input
                placeholder="Pesquisar Atividades"
                type="text"
                class="flex-1 border-b-2 border-b-transparent text-text outline-0 placeholder:text-secondary focus:border-b-(--color-school-class)"
                id="member-search"
            />
        </div>
        @if (auth()->user()->role !== \App\Enums\Role::Aluno)
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
            <span
                x-text="
                    totalUsers === 0
                        ? 'Nenhuma Atividade'
                        : `Exibindo ${rangeStart}-${rangeEnd} de ${totalUsers} Atividades`
                "
            ></span>
            <x-dot />
            <span class="flex items-center gap-smaller">
                <button
                    type="button"
                    class="flex size-8 items-center justify-center rounded-small font-semibold"
                    x-bind:class="
                        perPage === 10
                            ? 'bg-(--color-school-class) text-text-white'
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
                            ? 'bg-(--color-school-class) text-text-white'
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
                            ? 'bg-(--color-school-class) font-semibold text-text-white'
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
        class="relative grid size-full max-h-200 grid-cols-[repeat(auto-fit,minmax(350px,1fr))] gap-regular"
    >
        <a
            class="grid grid-cols-[auto_1fr] gap-regular rounded-regular border border-border bg-bg-secondary p-regular hover:bg-bg-secondary-hover"
        >
            <div
                class="row-span-2 flex size-16 items-center justify-center rounded-small bg-light-blue-bg p-regular text-light-blue"
            >
                <x-lucide-drafting-compass class="size-8" />
            </div>
            <span class="flex text-lg/tight font-medium"> Apostila 3 </span>
            <span class="flex items-center gap-smaller text-base/tight font-medium">
                <span> Matemática </span>
                <x-lucide-dot class="size-4 stroke-3" />
                <span> João Edison </span>
            </span>
        </a>
        <a
            class="grid grid-cols-[auto_1fr] gap-regular rounded-regular border border-border bg-bg-secondary p-regular hover:bg-bg-secondary-hover"
        >
            <div
                class="row-span-2 flex size-16 items-center justify-center rounded-small bg-purple-bg p-regular text-purple"
            >
                <x-lucide-book-open-text class="size-8" />
            </div>
            <span class="flex text-lg/tight font-medium"> Redação </span>
            <span class="flex items-center gap-smaller text-base/tight font-medium">
                <span> Português </span>
                <x-lucide-dot class="size-4 stroke-3" />
                <span> Gustavo </span>
            </span>
        </a>
    </div>
</div>
