<div
    x-show="section == 'disciplinesSection'"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    class="flex flex-col gap-regular justify-self-start"
>
    <div class="flex items-center gap-regular">
        <h2 class="flex-1 text-lg font-semibold">Disciplinas da Turma</h2>
    </div>
    <div class="flex items-center gap-regular">
        <div
            class="flex flex-1 items-center justify-start gap-small rounded-small border border-border bg-bg-secondary p-small text-base text-text"
        >
            <label for="discipline-search">
                <x-lucide-book-search />
            </label>
            <input
                placeholder="Pesquisar Membro (Nome, Cargo ou Email)"
                type="text"
                class="flex-1 border-b-2 border-b-transparent text-text outline-0 placeholder:text-secondary focus:border-b-(--color-school-class)"
                id="discipline-search"
            />
        </div>
        @if (auth()->user()->role === \App\Enums\Role::Coordenador)
            <x-primary-link href="" class="bg-accent text-text-white hover:bg-accent-hover">
                <x-lucide-book-plus />
                <span>Criar Disciplina</span>
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
        class="relative grid size-full max-h-200 grid-cols-[repeat(auto-fit,minmax(350px,1fr))] gap-regular"
    >
        @forelse ($schoolClass->users as $discipline)
            @php
                $discipline == null;
                $discipline->icon_code == null;
                if ($discipline->icon_code == null) {
                    $discipline->icon_code = $schoolClass->icon->code;
                }
                $discipline->color_code == null;
                if ($discipline->color_code == null) {
                    $discipline->color_code = $schoolClass->color->code;
                }
                $discipline->name == 'Matemática';
            @endphp
            <a
                class="grid grid-cols-[auto_1fr] gap-regular rounded-regular border border-border bg-bg-secondary p-regular hover:bg-bg-secondary-hover"
            >
                <div
                    class="flex size-16 items-center justify-center rounded-small"
                    style="background-color: var(--color-{{ $discipline->color_code }}-bg); color: var(--color-{{ $discipline->color_code }})"
                >
                    <x-dynamic-component
                        :component="'lucide-' . $discipline->icon_code"
                        class="size-8"
                    />
                </div>
                <span class="flex text-base/tight font-medium"> {{ $discipline->name }} </span>
            </a>
        @empty
            <div class="col-span-full flex items-center gap-regular p-regular">
                <p class="text-secondary">Nenhuma Disciplina criada ainda.</p>
                @if (auth()->user()->role === \App\Enums\Role::Coordenador)
                    <x-form-link href="">
                        Criar Disciplina
                        <x-slot name="icon">
                            <x-lucide-square-arrow-out-up-right class="size-4 stroke-3" />
                        </x-slot>
                    </x-form-link>
                @endif
            </div>
        @endforelse
    </div>
</div>
