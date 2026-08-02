<x-app-layout>
    <div class="grid grid-cols-2">
        <div></div>
        <div class="rounded-lg border border-border bg-bg-tertiary p-regular">
            <h3 class="mb-4 font-semibold">Nova Turma</h3>
            <form method="POST" action="{{ route('school-classes.store') }}">
                @csrf

                <x-input-label for="course_id" value="Curso" />
                <select
                    id="course_id"
                    name="course_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="">Selecione um curso</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('course_id')" class="mt-2" />

                <x-input-label for="grade_id" value="Série" class="mt-4" />
                <select
                    id="grade_id"
                    name="grade_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="">Selecione uma série</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('grade_id')" class="mt-2" />

                <x-input-label for="shift_id" value="Turno" class="mt-4" />
                <select
                    id="shift_id"
                    name="shift_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="">Selecione um turno</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('shift_id')" class="mt-2" />

                <x-input-label for="color_id" value="Cor" class="mt-4" />
                <select
                    id="color_id"
                    name="color_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300"
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
                <x-input-error :messages="$errors->get('color_id')" class="mt-2" />

                <x-input-label for="icon_id" value="Ícone" class="mt-4" />
                <select
                    id="icon_id"
                    name="icon_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300"
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
                <x-input-error :messages="$errors->get('icon_id')" class="mt-2" />

                {{-- Ícones reais, renderizados pelo Blade normalmente, mas escondidos.
                     O JS clona o markup daqui na hora de montar os templates do Choices,
                     em vez de tentar recriar o SVG dinamicamente. --}}
                <div id="icon-templates" class="hidden" aria-hidden="true">
                    @foreach ($icons as $icon)
                        <div data-icon-code="{{ $icon->code }}">
                            <x-dynamic-component
                                :component="'lucide-' . $icon->code"
                                class="size-4"
                            />
                        </div>
                    @endforeach
                </div>

                @if ($preselectedUsers->isNotEmpty())
                    <div class="mb-4 rounded-md bg-bg-secondary p-3 text-sm">
                        <p class="font-medium">Usuários que serão adicionados a esta turma:</p>
                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($preselectedUsers as $usuario)
                                <li>{{ $usuario->name }}</li>
                                <input type="hidden" name="usuarios[]" value="{{ $usuario->id }}" />
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('school-classes.index') }}" class="rounded-md px-4 py-2"
                        >Cancelar</a
                    >
                    <x-primary-button>Criar</x-primary-button>
                </div>
            </form>
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
