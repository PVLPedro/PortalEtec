<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg" x-data="{ selected: [] }">
                @if (session('status'))
                    <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                @error ('password')
                    <div class="mb-4 rounded bg-red-100 p-3 text-red-800">{{ $message }}</div>
                @enderror

                <div x-data="{ modalTurma: false, criarNova: false }">
                    <button
                        type="button"
                        @click="modalTurma = true"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-white"
                    >
                        Adicionar à Turma
                    </button>

                    <div
                        x-show="modalTurma"
                        x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    >
                        <div
                            class="w-96 rounded-lg bg-white p-6"
                            @click.outside="modalTurma = false"
                        >
                            <h3 class="mb-4 font-semibold">Adicionar à Turma</h3>

                            <form method="POST" action="{{ route('users.add-to-class') }}">
                                @csrf

                                <template
                                    x-for="
                                        id in
                                        Array.from(
                                            document.querySelectorAll(
                                                'input[name=\'usuarios[]\']:checked'
                                            )
                                        ).map((el) => el.value)
                                    "
                                >
                                    <input type="hidden" name="usuarios[]" :value="id" />
                                </template>

                                <div x-show="!criarNova">
                                    <select
                                        name="school_class_id"
                                        class="w-full rounded-md border-gray-300"
                                    >
                                        @foreach ($schoolClasses as $schoolClass)
                                            <option value="{{ $schoolClass->id }}">
                                                {{ $schoolClass->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button
                                        type="button"
                                        @click="criarNova = true"
                                        class="mt-2 text-sm underline"
                                    >
                                        + Criar nova turma
                                    </button>
                                </div>

                                <div x-show="criarNova" x-cloak class="space-y-2">
                                    <x-text-input
                                        name="nova_turma[curso]"
                                        type="text"
                                        placeholder="Curso"
                                        class="w-full"
                                    />
                                    <x-text-input
                                        name="nova_turma[serie]"
                                        type="text"
                                        placeholder="Série (ex: 3º ano)"
                                        class="w-full"
                                    />
                                    <select
                                        name="nova_turma[turno]"
                                        class="w-full rounded-md border-gray-300"
                                    >
                                        <option value="Manhã">Manhã</option>
                                        <option value="Tarde">Tarde</option>
                                        <option value="Noite">Noite</option>
                                    </select>
                                </div>

                                <div class="mt-6 flex justify-end gap-2">
                                    <button type="button" @click="modalTurma = false">
                                        Cancelar
                                    </button>
                                    <x-primary-button>Confirmar</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div
                    x-data="{
                    cargo: '',
                    rm: '',
                    school_class_id: '',
                    serie: '',
                    curso: '',
                    async filtrar() {
                        const params = new URLSearchParams({
                            cargo: this.cargo,
                            rm: this.rm,
                            school_class_id: this.school_class_id,
                            serie: this.serie,
                            curso: this.curso,
                        });
                        const resposta = await fetch(`{{ route('users.filtrar') }}?${params}`);
                        document.getElementById('tabela-usuarios').innerHTML = await resposta.text();
                    }
                }"
                >
                    <select x-model="cargo" @change="filtrar()">
                        <option value="">Cargo</option>
                        <option value="aluno">Aluno</option>
                        <option value="professor">Professor</option>
                        <option value="coordenador">Coordenador</option>
                    </select>

                    <input
                        type="text"
                        x-model="rm"
                        @input.debounce.400ms="filtrar()"
                        placeholder="RM"
                        maxlength="7"
                    />

                    <select x-model="school_class_id" @change="filtrar()">
                        <option value="">Turma</option>
                        @foreach ($schoolClasses as $schoolClass)
                            <option value="{{ $schoolClass->id }}">{{ $schoolClass->nome }}</option>
                        @endforeach
                    </select>

                    <select x-model="serie" @change="filtrar()">
                        <option value="">Série</option>
                        @foreach ($series as $serie)
                            <option value="{{ $serie }}">{{ $serie }}</option>
                        @endforeach
                    </select>

                    <select x-model="curso" @change="filtrar()">
                        <option value="">Curso</option>
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso }}">{{ $curso }}</option>
                        @endforeach
                    </select>

                    <div id="tabela-usuarios">
                        @include ('users.partials.table', ['usuarios' => $usuarios])
                    </div>
                </div>

                <form
                    method="POST"
                    action="{{ route('users.destroyMultiple') }}"
                    onsubmit="
                        return confirm(
                            'Excluir os usuários selecionados? Esta ação não pode ser desfeita.'
                        );
                    "
                >
                    @csrf
                    @method ('DELETE')

                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id" />
                    </template>

                    <div class="mt-4" x-show="selected.length > 0" x-cloak>
                        <label for="bulk-password" class="block text-sm text-gray-700">
                            Confirme sua senha para excluir
                            <span x-text="selected.length"></span> usuário(s) selecionado(s):
                        </label>
                        <input
                            id="bulk-password"
                            type="password"
                            name="password"
                            class="mt-1 block w-full rounded border-gray-300"
                            placeholder="Sua senha"
                        />

                        <button
                            type="submit"
                            class="mt-3 rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700"
                        >
                            Excluir selecionados
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
