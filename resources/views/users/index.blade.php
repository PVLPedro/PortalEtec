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

                <div
                    x-data="{
                    cargo: '',
                    rm: '',
                    async filtrar() {
                        const params = new URLSearchParams({ cargo: this.cargo, rm: this.rm });
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

                    {{-- <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="w-8">
                                    <input
                                        type="checkbox"
                                        @change="selected = $event.target.checked
                                            ? @json($usuarios->where('role', '!=', \App\Enums\Role::Coordenador)->pluck('id')->values())
                                            : []"
                                    />
                                </th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Cargo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $usuario)
                                <tr>
                                    <td>
                                        @if ($usuario->role !== \App\Enums\Role::Coordenador)
                                            <input
                                                type="checkbox"
                                                value="{{ $usuario->id }}"
                                                x-model="selected"
                                            />
                                        @endif
                                    </td>
                                    <td>{{ $usuario->name }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>{{ $usuario->role->value }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $usuario) }}">Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}

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
