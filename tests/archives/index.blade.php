<x-app-layout>
    <div
        x-data="{
            selectionMode: false,
            hoveredRow: null,
            selected: [],
            addModal: false,
            schoolClassModal: false,
            deleteModal: false,
            deleteSelectedModal: false,
            userToAdd: 0,
            userRoleToAdd: 'role',
            userNameToAdd: 'name',
            userToDelete: 0,
            userRoleToDelete: 'role',
            userNameToDelete: 'name',
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
        <div id="modals" class="contents">
            @include ('users.partials.modals')
        </div>
        @if (session('status'))
            <div class="mb-4 rounded bg-valid p-3">{{ session('status') }}</div>
        @endif

        @error ('password')
            <div class="mb-4 rounded bg-red-100 p-3 text-red-800">{{ $message }}</div>
        @enderror

        <div class="grid grid-cols-2 gap-small">
            <div>
                <x-input-label for="role" :value="__('Tipo de Usuário')" />

                <select
                    x-model="role_id"
                    id="role"
                    @change="filtrar()"
                    class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                >
                    <option value="">Cargo</option>
                    <option value="aluno">Aluno</option>
                    <option value="professor">Professor</option>
                    <option value="coordenador">Coordenador</option>
                </select>

                {{-- <x-input-error :messages="$errors->get('role')" class="" /> --}}
            </div>

            <div>
                <x-input-label for="school-class" :value="__('Turma pertencente')" />

                <select
                    x-model="school_class_id"
                    id="school-class"
                    @change="filtrar()"
                    class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                >
                    <option value="">Turma</option>
                    @foreach ($schoolClasses as $schoolClass)
                        <option value="{{ $schoolClass->id }}">{{ $schoolClass->nome }}</option>
                    @endforeach
                </select>

                {{-- <x-input-error :messages="$errors->get('role')" class="" /> --}}
            </div>

            <div>
                <x-input-label for="course" :value="__('Curso designado')" />

                <select
                    x-model="course_id"
                    id="course"
                    @change="filtrar()"
                    class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                >
                    <option value="">Curso</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                    @endforeach
                </select>

                {{-- <x-input-error :messages="$errors->get('role')" class="" /> --}}
            </div>

            <div>
                <x-input-label for="grade" :value="__('Série')" />

                <select
                    x-model="grade_id"
                    id="grade"
                    @change="filtrar()"
                    class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                >
                    <option value="">Série</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>

                {{-- <x-input-error :messages="$errors->get('role')" class="" /> --}}
            </div>

            <div>
                <x-input-label for="rm" :value="__('RM do Aluno')" />
                <input
                    type="text"
                    id="rm"
                    x-model="rm"
                    @input.debounce.400ms="filtrar()"
                    placeholder="RM"
                    maxlength="7"
                    class="flex w-full items-center gap-small rounded-small border border-border p-small text-text"
                />
            </div>
        </div>

        <div class="flex flex-col items-center gap-small rounded-small *:w-full">
            <div class="flex items-center justify-start gap-smaller">
                <x-primary-button
                    type="button"
                    class=""
                    x-bind:class="
                        selectionMode
                            ? 'bg-accent text-text-white hover:bg-accent-hover'
                            : 'bg-bg-primary text-text hover:bg-bg-primary-hover'
                    "
                    @click="
                        selectionMode = !selectionMode;
                        selected = [];
                    "
                >
                    <x-lucide-copy x-show="!selectionMode" class="text-inherit" />
                    <x-lucide-copy-x x-show="selectionMode" x-cloak />
                    <span x-show="!selectionMode">Selecionar usuários</span>
                    <span x-show="selectionMode" x-cloak>Cancelar seleção</span>
                </x-primary-button>

                <div
                    x-show="selectionMode"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="flex flex-1 items-center gap-smaller"
                >
                    <x-primary-button
                        type="button"
                        class="group/tooltip bg-bg-primary text-text hover:bg-bg-primary-hover"
                        {{-- @click="selected = @json($usuarios->where('role', '!=', \App\Enums\Role::Coordenador)->pluck('id')->values())" --}}
                    >
                        <x-icons.select-all />
                        <x-tooltip> Selecionar todos </x-tooltip>
                    </x-primary-button>
                    <x-primary-button
                        type="button"
                        class="group/tooltip bg-bg-primary text-text hover:bg-bg-primary-hover"
                        {{-- @click="selected = []" --}}
                    >
                        <x-icons.select-remove />
                        <x-tooltip> Limpar seleção </x-tooltip>
                    </x-primary-button>
                    <x-primary-button
                        type="button"
                        class="group/tooltip bg-bg-primary text-text hover:bg-bg-primary-hover"
                        {{-- @click="selected = @json($usuarios->where('role', '!=', \App\Enums\Role::Coordenador)->pluck('id')->values()).filter(id => !selected.includes(id))" --}}
                    >
                        <x-icons.select-invert />
                        <x-tooltip> Inverter seleção </x-tooltip>
                    </x-primary-button>
                </div>

                <x-primary-button
                    type="button"
                    class="disabled:cursor-not-allowed"
                    x-bind:class="[
                        selectionMode ? 'flex' : 'hidden',
                        selected.length > 0
                            ? 'bg-accent text-text-white hover:bg-accent-hover'
                            : 'bg-bg-primary-disabled text-text-disabled',
                    ]"
                    x-bind:disabled="selected.length == 0"
                    @click="if (selected.length > 0) schoolClassModal = true;"
                >
                    <x-lucide-book-plus />
                    Adicionar à Turma
                </x-primary-button>

                <x-primary-button
                    type="button"
                    class="disabled:cursor-not-allowed"
                    x-bind:class="[
                        selectionMode ? 'flex' : 'hidden',
                        selected.length > 0
                            ? 'bg-danger text-text-white hover:bg-danger-hover'
                            : 'bg-bg-primary-disabled text-text-disabled',
                    ]"
                    x-bind:disabled="selected.length == 0"
                    @click="if (selected.length > 0) deleteSelectedModal = true;"
                >
                    <x-lucide-trash-2 />
                    Excluir selecionados
                </x-primary-button>
            </div>
            @include ('users.partials.table', ['usuarios' => $usuarios])
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
