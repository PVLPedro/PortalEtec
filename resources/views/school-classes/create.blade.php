<x-app-layout>
    <div
        x-data="{
            course: 'Curso',
            grade: 'Grade',
            shift: 'Turno',
            colorCode: 'accent',
            iconCode: 'graduation-cap',
        }"
        class="flex flex-col gap-large *:w-full"
    >
        <div class="flex items-center gap-regular">
            <x-primary-link
                href="{{ url()->previous() }}"
                class="bg-bg-primary text-text hover:bg-bg-primary-hover"
            >
                <x-lucide-chevron-left />
                Voltar
            </x-primary-link>
            <h2 class="flex-1 text-xl font-semibold">Nova Turma</h2>
        </div>
        <x-card class="grid grid-cols-[auto_1fr] gap-regular">
            <h3 class="col-span-full text-lg font-semibold">Pré-visualização da Turma</h3>
            <div
                class="flex size-32 items-center justify-center rounded-large p-regular"
                style="background-color: var(--color-accent-bg); color: var(--color-accent)"
            >
                <x-dynamic-component :component="'lucide-graduation-cap'" class="size-16" />
            </div>
            <div class="flex items-center justify-between">
                <span class="" :text="grade + course + shift"></span>
            </div>
        </x-card>
        <x-card class="flex-1">
            <form
                method="POST"
                action="{{ route('school-classes.store') }}"
                class="grid h-full grid-cols-2 gap-regular"
            >
                @csrf
                <div class="grid auto-rows-min gap-regular">
                    <h3 class="col-span-full text-lg font-semibold">Opções Gerais</h3>
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
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
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
                    <h3 class="col-span-full text-lg font-semibold">Personalização</h3>
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

                    <div>
                        <x-input-label for="icon_id" value="Ícone" class="" />
                        <select
                            id="icon_id"
                            name="icon_id"
                            required
                            class="block w-full rounded-md border-gray-300"
                        >
                            <option value="">Selecione um ícone</option>
                            @foreach ($icons as $icon)
                                <option
                                    value="{{ $icon->id }}"
                                    data-custom-properties='{"iconCode": "{{ $icon->code }}"}'
                                >
                                    {{ $icon->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('icon_id')" class="" />
                    </div>
                </div>
                <div class="">
                    @if ($preselectedUsers->isNotEmpty())
                        <div class="">
                            <p class="font-medium">Usuários que serão adicionados a esta turma:</p>
                            <ul class="list-inside list-disc">
                                @foreach ($preselectedUsers as $usuario)
                                    <li>{{ $usuario->name }}</li>
                                    <input
                                        type="hidden"
                                        name="usuarios[]"
                                        value="{{ $usuario->id }}"
                                    />
                                @endforeach
                            </ul>
                        </div>
                    @endif
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
            </form>
        </x-card>
        <div id="icon-templates" class="hidden" aria-hidden="true">
            @foreach ($icons as $icon)
                <div data-icon-code="{{ $icon->code }}">
                    <x-dynamic-component :component="'lucide-' . $icon->code" class="size-4" />
                </div>
            @endforeach
        </div>
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

                // Select de Ícone, com o SVG do Lucide clonado do container oculto
                new Choices($('#icon_id').get(0), {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    placeholder: true,
                    allowHTML: true,
                    callbackOnCreateTemplates: function (template) {
                        const icon = (data) => {
                            const code = data.customProperties && data.customProperties.iconCode;
                            if (!code) return '';

                            const source = document.querySelector(
                                `#icon-templates [data-icon-code="${code}"]`
                            );

                            // Se o code não corresponder a nenhum ícone renderizado,
                            // não quebra o layout, só não mostra nada
                            return source
                                ? `<span class="choices__icon">${source.innerHTML}</span>`
                                : '';
                        };

                        return makeDecoratedTemplates(template, this.config.itemSelectText, icon);
                    },
                });
            });
        </script>
    @endpush
</x-app-layout>
