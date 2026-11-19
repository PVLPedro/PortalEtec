<x-app-layout>
    <style>
        :root {
            --color-school-class: var(--color-accent);
            --color-school-class-bg: var(--color-accent-bg);
        }
    </style>
    <div
        x-data="{
            course: 'Curso',
            grade: 'Grade',
            shift: 'Turno',
            colorCode: 'accent',
            iconCode: 'graduation-cap',
        }"
        class="grid grid-cols-1 gap-large *:w-full"
    >
        <div class="flex items-center gap-regular">
            <x-back-link />
            <h2 class="flex-1 text-xl font-semibold">Nova Turma</h2>
        </div>
        <x-card class="grid grid-cols-[auto_1fr] gap-regular">
            <span class="col-span-full flex flex-col">
                <h3 class="text-lg font-semibold">Pré-visualização da Turma</h3>
                <p class="text-sm text-secondary">Assim aparecerá a Turma para os Membros</p>
            </span>
            <div
                class="flex size-16 items-center justify-center rounded-small bg-(--color-school-class-bg) p-regular text-(--color-school-class)"
            >
                <x-dynamic-component :component="'lucide-graduation-cap'" class="size-8" />
            </div>
            <div class="flex items-center justify-between">
                <span class="" :text="grade + course + shift"></span>
            </div>
        </x-card>
        <x-card class="">
            <form
                method="POST"
                action="{{ route('school-classes.store') }}"
                class="grid h-full grid-cols-2 gap-regular"
            >
                @csrf
                <div class="flex items-center">
                    <h3 class="text-lg font-semibold">Configurações da Turma</h3>
                </div>
                <div class="flex justify-end gap-regular self-end justify-self-end">
                    <x-primary-link
                        href="{{ url()->previous() }}"
                        class="bg-bg-primary text-text hover:bg-bg-primary-hover"
                    >
                        <x-lucide-x />
                        Cancelar
                    </x-primary-link>
                    <x-primary-button
                        type="submit"
                        class="bg-accent text-text-white hover:bg-accent-hover"
                    >
                        <x-lucide-check />
                        Criar
                    </x-primary-button>
                </div>
                <div class="grid auto-rows-min gap-regular">
                    <h3 class="col-span-full text-base font-semibold">Opções Gerais</h3>
                    <div>
                        <x-input-label for="course_id" value="Curso" />
                        <select
                            id="course_id"
                            name="course_id"
                            required
                            class="block w-full rounded-md border-gray-300"
                        >
                            <option value="">Selecione um curso</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">
                                    {{ $course->name }} ({{ $course->initialism }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('course_id')" class="" />
                    </div>

                    <div>
                        <x-input-label for="grade_id" value="Série" class="" />
                        <select
                            id="grade_id"
                            name="grade_id"
                            required
                            class="block w-full rounded-md border-gray-300"
                        >
                            <option value="">Selecione uma série</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('grade_id')" class="" />
                    </div>

                    <div>
                        <x-input-label for="shift_id" value="Turno" class="" />
                        <select
                            id="shift_id"
                            name="shift_id"
                            required
                            class="block w-full rounded-md border-gray-300"
                        >
                            <option value="">Selecione um turno</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('shift_id')" class="" />
                    </div>
                </div>
                <div class="grid auto-rows-min gap-regular">
                    <h3 class="col-span-full text-base font-semibold">Personalização</h3>
                    <div>
                        <x-input-label for="color_id" value="Cor" class="" />
                        <select
                            id="color_id"
                            name="color_id"
                            required
                            class="block w-full rounded-md border-gray-300"
                        >
                            <option value="">Selecione uma cor</option>
                            @foreach ($colors as $color)
                                <option
                                    value="{{ $color->id }}"
                                    data-custom-properties='{"colorCode": "{{ $color->code }}"}'
                                >
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('color_id')" class="" />
                    </div>

                    @if ($preselectedUsers->isNotEmpty())
                        <h3 class="col-span-full text-base font-semibold">Membros</h3>
                        <span class="">Estes Usuários serão adicionados como Membros:</span>
                        <div>
                            <span class="flex flex-wrap gap-small">
                                @foreach ($preselectedUsers as $usuario)
                                    <span
                                        class="rounded-full bg-bg-secondary px-small py-smaller font-medium text-secondary capitalize"
                                    >
                                        {{ $usuario->role->value }} {{ $usuario->name }}
                                    </span>
                                    <input
                                        type="hidden"
                                        name="usuarios[]"
                                        value="{{ $usuario->id }}"
                                    />
                                @endforeach
                            </span>
                        </div>
                    @endif
                </div>
            </form>
        </x-card>
    </div>

    @push ('scripts')
        <script>
            $(function () {
                // Monta os templates de "choice" (opção na lista) e "item" (opção selecionada)
                // de forma idêntica, exceto pelo HTML extra injetado antes do label
                // (decorateFn). Evita repetir esse bloco grande de HTML pra cada select customizado.
                function makeDecoratedTemplates(template, itemSelectText, decorateFn) {
                    return {
                        choice: ({ classNames }, data) =>
                            template(`
                        <div class="${classNames.item} ${classNames.itemChoice} ${data.disabled ? classNames.itemDisabled : classNames.itemSelectable}"
                             data-select-text="${itemSelectText}"
                             data-choice
                             ${data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable'}
                             data-id="${data.id}"
                             data-value="${data.value}"
                             role="option">
                            ${decorateFn(data)}${data.label}
                        </div>
                    `),
                        item: ({ classNames }, data) =>
                            template(`
                        <div class="${classNames.item} ${data.highlighted ? classNames.highlightedState : classNames.itemSelectable} ${data.placeholder ? classNames.placeholder : ''}"
                             data-item
                             data-id="${data.id}"
                             data-value="${data.value}"
                             ${data.active ? 'aria-selected="true"' : ''}
                             ${data.disabled ? 'aria-disabled="true"' : ''}>
                            ${decorateFn(data)}${data.label}
                        </div>
                    `),
                    };
                }

                // Selects "simples", sem template customizado
                const plainSelectIds = ['course_id', 'grade_id', 'shift_id'];

                plainSelectIds.forEach((id) => {
                    new Choices($('#' + id).get(0), {
                        searchEnabled: true,
                        itemSelectText: '',
                        shouldSort: false,
                        placeholder: true,
                    });
                });

                // Select de Cor, com swatch renderizado ao lado do nome
                new Choices($('#color_id').get(0), {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    placeholder: true,
                    allowHTML: true,
                    callbackOnCreateTemplates: function (template) {
                        const swatch = (data) => {
                            const code =
                                (data.customProperties && data.customProperties.colorCode) || null;
                            const bg = code ? `var(--color-${code})` : '#ccc';
                            return `<span class="choices__color-swatch" style="background-color: ${bg}"></span>`;
                        };

                        return makeDecoratedTemplates(template, this.config.itemSelectText, swatch);
                    },
                });
            });
        </script>
    @endpush
</x-app-layout>
