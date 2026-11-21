<x-app-layout>
    <div
        class="flex max-w-xl flex-col gap-regular"
        x-data="{
            courseIds: @js($courseDisciplineIds),
            selected: @js((string) old('discipline_id', $classDiscipline?->discipline_id)),
            get isCourse() {
                return this.courseIds.map(String).includes(String(this.selected));
            },
        }"
    >
        @include ('class-content.partials.back', ['section' => 'disciplinas'])

        <h1 class="text-lg font-semibold">
            {{ $classDiscipline ? 'Editar disciplina' : 'Adicionar disciplina' }}
        </h1>

        <form
            method="POST"
            action="{{ $classDiscipline ? route('class.disciplines.update', [$schoolClass, $classDiscipline]) : route('class.disciplines.store', $schoolClass) }}"
            class="flex flex-col gap-regular"
        >
            @csrf
            @if ($classDiscipline)
                @method('PUT')
            @endif

            <div class="flex flex-col gap-smaller">
                <label for="discipline_id" class="text-sm font-semibold">Disciplina</label>

                @if ($classDiscipline)
                    <p class="rounded-small border border-border p-small">
                        {{ $classDiscipline->discipline->name }}
                    </p>
                @else
                    <select
                        id="discipline_id"
                        name="discipline_id"
                        x-model="selected"
                        required
                        class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                    >
                        <option value="">Selecione uma disciplina</option>
                        @foreach ($disciplines as $discipline)
                            <option value="{{ $discipline->id }}">{{ $discipline->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('discipline_id')" />
                @endif
            </div>

            <div class="flex flex-col gap-smaller">
                <label for="color_id" class="text-sm font-semibold">Cor</label>
                <select
                    id="color_id"
                    name="color_id"
                    x-bind:disabled="isCourse"
                    class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                >
                    <option value="">Selecione uma cor</option>
                    @foreach ($colors as $color)
                        <option
                            value="{{ $color->id }}"
                            @selected ((string) old('color_id', $classDiscipline?->color_id) === (string) $color->id)
                        >
                            {{ $color->name }}
                        </option>
                    @endforeach
                </select>
                <p x-show="isCourse" x-cloak class="text-xs">
                    Disciplina de curso: a cor é sempre cinza.
                </p>
                <x-input-error :messages="$errors->get('color_id')" />
            </div>

            <div class="flex flex-col gap-small">
                <span class="text-sm font-semibold">Professores (no máximo {{ $maxTeachers }})</span>

                @for ($i = 0; $i < $maxTeachers; $i++)
                    @php
                        $row = old("teachers.$i", $assigned[$i] ?? []);
                    @endphp
                    <div class="grid grid-cols-2 gap-small">
                        <select
                            name="teachers[{{ $i }}][id]"
                            class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                        >
                            <option value="">Nenhum professor</option>
                            @foreach ($availableTeachers as $teacher)
                                <option
                                    value="{{ $teacher->id }}"
                                    @selected ((string) ($row['id'] ?? '') === (string) $teacher->id)
                                >
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>

                        <select
                            name="teachers[{{ $i }}][side_id]"
                            class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                        >
                            <option value="">Todos os lados</option>
                            @foreach ($sides as $side)
                                <option
                                    value="{{ $side->id }}"
                                    @selected ((string) ($row['side_id'] ?? '') === (string) $side->id)
                                >
                                    {{ $side->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endfor

                @foreach ($errors->get('teachers') as $message)
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @endforeach
                @foreach (collect($errors->get('teachers.*'))->flatten() as $message)
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @endforeach
            </div>

            <div class="flex items-center gap-regular">
                <button
                    type="submit"
                    class="rounded-small bg-accent px-regular py-small text-sm font-semibold text-white hover:bg-accent-hover"
                >
                    Salvar
                </button>
                <a href="{{ route('school-class.show', [$schoolClass, 'secao' => 'disciplinas']) }}" class="text-sm underline">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
