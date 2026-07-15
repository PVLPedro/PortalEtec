<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl leading-tight font-semibold text-gray-800">Editar Turma</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('school-classes.update', $schoolClass) }}">
                    @csrf
                    @method ('PUT')

                    <div>
                        <x-input-label for="course_id" value="Curso" />
                        <select
                            id="course_id"
                            name="course_id"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            @foreach ($courses as $course)
                                <option
                                    value="{{ $course->id }}"
                                    @selected (old('course_id', $schoolClass->course_id) == $course->id)
                                >
                                    {{ $course->course_name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="grade_id" value="Série" />
                        <select
                            id="grade_id"
                            name="grade_id"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            @foreach ($grades as $grade)
                                <option
                                    value="{{ $grade->id }}"
                                    @selected (old('grade_id', $schoolClass->grade_id) == $grade->id)
                                >
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('grade_id')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="shift_id" value="Turno" />
                        <select
                            id="shift_id"
                            name="shift_id"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            @foreach ($shifts as $shift)
                                <option
                                    value="{{ $shift->id }}"
                                    @selected (old('shift_id', $schoolClass->shift_id) == $shift->id)
                                >
                                    {{ $shift->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('shift_id')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <a
                            href="{{ route('school-classes.index') }}"
                            class="text-sm text-gray-600 underline"
                        >
                            Cancelar
                        </a>
                        <x-primary-button>Salvar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
