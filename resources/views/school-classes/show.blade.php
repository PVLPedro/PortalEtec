<x-app-layout>
    <div class="py-12" x-data="{ editando: false }">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-semibold">{{ $schoolClass->nome }}</h2>
                <div class="flex gap-2">
                    <button @click="editando = true" class="text-sm underline">Editar</button>
                    <form
                        method="POST"
                        action="{{ route('school-classes.destroy', $schoolClass) }}"
                        onsubmit="return confirm('Excluir esta turma?');"
                    >
                        @csrf
                        @method ('DELETE')
                        <button type="submit" class="text-sm text-red-600 underline">
                            Excluir turma
                        </button>
                    </form>
                </div>
            </div>

            <div x-show="editando" x-cloak class="mb-6 rounded-lg bg-white p-4 shadow">
                <form
                    method="POST"
                    action="{{ route('school-classes.update', $schoolClass) }}"
                    class="flex items-end gap-2"
                >
                    @csrf
                    @method ('PUT')
                    <div>
                        <x-input-label value="Curso" />
                        <x-text-input
                            name="curso"
                            type="text"
                            :value="$schoolClass->curso"
                            required
                        />
                    </div>
                    <div>
                        <x-input-label value="Série" />
                        <x-text-input
                            name="serie"
                            type="text"
                            :value="$schoolClass->serie"
                            required
                        />
                    </div>
                    <div>
                        <x-input-label value="Turno" />
                        <select name="turno" class="rounded-md border-gray-300">
                            <option value="Manhã" @selected ($schoolClass->turno === 'Manhã')>
                                Manhã
                            </option>
                            <option value="Tarde" @selected ($schoolClass->turno === 'Tarde')>
                                Tarde
                            </option>
                            <option value="Noite" @selected ($schoolClass->turno === 'Noite')>
                                Noite
                            </option>
                        </select>
                    </div>
                    <x-primary-button>Salvar</x-primary-button>
                </form>
            </div>

            <div class="rounded-lg bg-white shadow">
                <table class="w-full text-left">
                    <thead class="border-b">
                        <tr>
                            <th class="p-3">Nome</th>
                            <th class="p-3">Cargo</th>
                            <th class="p-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schoolClass->users as $usuario)
                            <tr class="border-b">
                                <td class="p-3">{{ $usuario->name }}</td>
                                <td class="p-3">{{ $usuario->role->value }}</td>
                                <td class="p-3 text-right">
                                    <form
                                        method="POST"
                                        action="{{ route('school-classes.remove-user', [$schoolClass, $usuario]) }}"
                                    >
                                        @csrf
                                        @method ('DELETE')
                                        <button
                                            type="submit"
                                            class="text-sm text-red-600 underline"
                                        >
                                            Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-3 text-gray-500" colspan="3">
                                    Nenhum membro nesta turma ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
