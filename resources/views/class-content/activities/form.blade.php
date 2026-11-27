<x-app-layout>
    <div class="flex max-w-xl flex-col gap-regular">
        @include ('class-content.partials.back', ['section' => 'atividades'])

        <h1 class="text-lg font-semibold">
            {{ $activity ? 'Editar' : 'Nova atividade ou material' }}
        </h1>

        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ $activity ? route('class.activities.update', [$schoolClass, $activity]) : route('class.activities.store', $schoolClass) }}"
            class="flex flex-col gap-regular"
        >
            @csrf
            @if ($activity)
                @method('PUT')
            @endif

            <div class="flex flex-col gap-smaller">
                <label for="school_class_discipline_id" class="text-sm font-semibold">Disciplina</label>
                <select
                    id="school_class_discipline_id"
                    name="school_class_discipline_id"
                    required
                    class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                >
                    <option value="">Selecione uma disciplina</option>
                    @foreach ($classDisciplines as $item)
                        <option
                            value="{{ $item->id }}"
                            @selected ((string) old('school_class_discipline_id', $activity?->school_class_discipline_id) === (string) $item->id)
                        >
                            {{ $item->discipline->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('school_class_discipline_id')" />
            </div>

            <div class="flex flex-col gap-smaller">
                <label for="title" class="text-sm font-semibold">Título</label>
                <input
                    id="title"
                    type="text"
                    name="title"
                    maxlength="120"
                    required
                    value="{{ old('title', $activity?->title) }}"
                    class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                />
                <x-input-error :messages="$errors->get('title')" />
            </div>

            <div class="flex flex-col gap-smaller">
                <label for="description" class="text-sm font-semibold">Descrição</label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    maxlength="5000"
                    class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                >{{ old('description', $activity?->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" />
            </div>

            <div class="flex flex-col gap-smaller">
                <label for="deadline" class="text-sm font-semibold">Data de entrega</label>
                <input
                    id="deadline"
                    type="datetime-local"
                    name="deadline"
                    value="{{ old('deadline', $activity?->deadline?->format('Y-m-d\TH:i')) }}"
                    class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                />
                <p class="text-xs">
                    Deixe em branco para publicar como material de apoio (exige pelo menos um arquivo).
                </p>
                <x-input-error :messages="$errors->get('deadline')" />
            </div>

            <div class="flex flex-col gap-smaller">
                <label for="side_id" class="text-sm font-semibold">Lado</label>
                <select
                    id="side_id"
                    name="side_id"
                    class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                >
                    <option value="">Todos os alunos</option>
                    @foreach ($sides as $side)
                        <option
                            value="{{ $side->id }}"
                            @selected ((string) old('side_id', $activity?->side_id) === (string) $side->id)
                        >
                            {{ $side->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs">Com um lado definido, só alunos desse lado acessam.</p>
                <x-input-error :messages="$errors->get('side_id')" />
            </div>

            <label class="flex items-center gap-small text-sm">
                <input
                    type="checkbox"
                    name="comments_enabled"
                    value="1"
                    @checked (old('comments_enabled', $activity?->comments_enabled ?? true))
                />
                Habilitar comentários
            </label>

            @if ($activity && $activity->files->isNotEmpty())
                <div class="flex flex-col gap-small rounded-small border border-border p-regular">
                    <span class="text-sm font-semibold">Arquivos atuais</span>
                    @foreach ($activity->files as $file)
                        <label class="flex items-center gap-small text-sm">
                            <input type="checkbox" name="remove_files[]" value="{{ $file->id }}" />
                            Remover {{ $file->original_name }}
                        </label>
                    @endforeach
                    <input
                        type="password"
                        name="password"
                        placeholder="Senha (necessária só se remover arquivos)"
                        class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                    />
                    <x-input-error :messages="$errors->get('password')" />
                </div>
            @endif

            <div class="flex flex-col gap-smaller">
                <label for="files" class="text-sm font-semibold">
                    {{ $activity ? 'Adicionar arquivos' : 'Arquivos' }}
                </label>
                <input id="files" type="file" name="files[]" multiple class="text-sm" />
                <p class="text-xs">Até {{ $maxFiles }} arquivos de {{ $maxFileMb }} MB cada.</p>
                <x-input-error :messages="$errors->get('files')" />
                @foreach (collect($errors->get('files.*'))->flatten() as $message)
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
                <a href="{{ route('school-class.show', [$schoolClass, 'secao' => 'atividades']) }}" class="text-sm underline">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
