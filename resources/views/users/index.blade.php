<x-app-layout>
    <div class="">
        <div class="">
            <div class="" x-data="{ selected: [] }">
                @if (session('status'))
                    <div class="mb-4 rounded bg-valid p-3">{{ session('status') }}</div>
                @endif

                @error ('password')
                    <div class="mb-4 rounded bg-red-100 p-3 text-red-800">{{ $message }}</div>
                @enderror

                <div
                    x-data="{
                        role_id: '',
                        rm: '',
                        school_class_id: '',
                        grade_id: '',
                        course_id: '',
                        async filtrar() {
                            const params = new URLSearchParams({
                                role: this.role,
                                rm: this.rm,
                                school_class_id: this.school_class_id,
                                grade_id: this.grade_id,
                                course_id: this.course_id,
                            });
                            const resposta = await fetch(`{{ route('users.filtrar') }}?${params}`);
                            document.getElementById('tabela-usuarios').innerHTML = await resposta.text();
                        }
                    }"
                    class="flex w-full flex-col items-center gap-regular *:w-full"
                >
                    <div class="grid grid-cols-2 gap-small">
                        <div>
                            <x-input-label for="role" :value="__('users.index.filters.role')" />

                            <select
                                x-model="role_id"
                                id="role"
                                @change="filtrar()"
                                class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            >
                                <option value="">{{ __('users.index.filters.role_placeholder') }}</option>
                                <option value="aluno">{{ __('users.index.filters.role_options.aluno') }}</option>
                                <option value="professor">{{ __('users.index.filters.role_options.professor') }}</option>
                                <option value="coordenador">{{ __('users.index.filters.role_options.coordenador') }}</option>
                            </select>

                            {{-- <x-input-error :messages="$errors->get('role')" class="" /> --}}
                        </div>

                        <div>
                            <x-input-label for="school-class" :value="__('users.index.filters.school_class')" />

                            <select
                                x-model="school_class_id"
                                id="school-class"
                                @change="filtrar()"
                                class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            >
                                <option value="">{{ __('users.index.filters.school_class_placeholder') }}</option>

                                @foreach ($schoolClasses as $schoolClass)
                                    <option value="{{ $schoolClass->id }}">
                                        {{ $schoolClass->nome }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- <x-input-error :messages="$errors->get('role')" class="" /> --}}
                        </div>

                        <div>
                            <x-input-label for="course" :value="__('users.index.filters.course')" />

                            <select
                                x-model="course_id"
                                id="course"
                                @change="filtrar()"
                                class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            >
                               <option value="">{{ __('users.index.filters.course_placeholder') }}</option>

                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- <x-input-error :messages="$errors->get('role')" class="" /> --}}
                        </div>

                        <div>
                           <x-input-label for="grade" :value="__('users.index.filters.grade')" />

                            <select
                                x-model="grade_id"
                                id="grade"
                                @change="filtrar()"
                                class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            >
                                <option value="">{{ __('users.index.filters.grade_placeholder') }}</option>

                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>

                            {{-- <x-input-error :messages="$errors->get('role')" class="" /> --}}
                        </div>

                        <div>
                            <x-input-label for="rm" :value="__('users.index.filters.rm')" />

                            <input
                                type="text"
                                id="rm"
                                x-model="rm"
                                @input.debounce.400ms="filtrar()"
                                placeholder="{{ __('users.index.filters.rm_placeholder') }}"
                                maxlength="7"
                                class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                            />
                        </div>
                    </div>

                    <div id="tabela-usuarios">
                        @include ('users.partials.table', ['usuarios' => $usuarios])
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push ('scripts')
    <script>
        $(function () {
            const roleSelect = document.getElementById('role');

            new Choices(roleSelect, {
                searchEnabled: false,
                itemSelectText: '',
                shouldSort: false,
            });

            const classSelect = document.getElementById('school-class');

            new Choices(classSelect, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: true,
            });

            const courseSelect = document.getElementById('course');

            new Choices(courseSelect, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: true,
            });

            const gradeSelect = document.getElementById('grade');

            new Choices(gradeSelect, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: true,
            });
        });
    </script>
@endpush
