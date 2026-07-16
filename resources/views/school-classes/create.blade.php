<x-app-layout>
    <div
        x-show="modalCriar"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    >
        <div class="w-96 rounded-lg bg-white p-6" @click.outside="modalCriar = false">
            <h3 class="mb-4 font-semibold">Nova Turma</h3>
            <form method="POST" action="{{ route('school-classes.store') }}">
                @csrf

                <x-input-label for="course_id" value="Curso" />
                <select
                    name="course_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="">Selecione um curso</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('course_id')" class="mt-2" />

                <x-input-label for="grade_id" value="Série" class="mt-4" />
                <select
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

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="modalCriar = false">Cancelar</button>
                    <x-primary-button>Criar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
