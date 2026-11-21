<x-app-layout>
    <div class="flex max-w-xl flex-col gap-regular">
        @include ('class-content.partials.back', ['section' => 'comunicados'])

        <h1 class="text-lg font-semibold">
            {{ $announcement ? 'Editar comunicado' : 'Novo comunicado' }}
        </h1>

        <form
            method="POST"
            action="{{ $announcement ? route('class.announcements.update', [$schoolClass, $announcement]) : route('class.announcements.store', $schoolClass) }}"
            class="flex flex-col gap-regular"
        >
            @csrf
            @if ($announcement)
                @method('PUT')
            @endif

            <div class="flex flex-col gap-smaller">
                <label for="title" class="text-sm font-semibold">Título</label>
                <input
                    id="title"
                    type="text"
                    name="title"
                    maxlength="120"
                    required
                    value="{{ old('title', $announcement?->title) }}"
                    class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                />
                <x-input-error :messages="$errors->get('title')" />
            </div>

            <div class="flex flex-col gap-smaller">
                <label for="body" class="text-sm font-semibold">Mensagem</label>
                <textarea
                    id="body"
                    name="body"
                    rows="6"
                    maxlength="5000"
                    required
                    class="w-full rounded-small border border-border bg-bg-primary p-small text-text"
                >{{ old('body', $announcement?->body) }}</textarea>
                <x-input-error :messages="$errors->get('body')" />
            </div>

            <div class="flex items-center gap-regular">
                <button
                    type="submit"
                    class="rounded-small bg-accent px-regular py-small text-sm font-semibold text-white hover:bg-accent-hover"
                >
                    {{ $announcement ? 'Salvar' : 'Publicar' }}
                </button>
                <a href="{{ route('school-class.show', [$schoolClass, 'secao' => 'comunicados']) }}" class="text-sm underline">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
