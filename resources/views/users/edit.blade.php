<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-md sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method ('PUT')
                    <input
                        type="hidden"
                        name="role"
                        value="{{ old('role', $user->role->value) }}"
                    />

                    <x-input-label for="name" value="Nome" />
                    <x-text-input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('name', $user->name)"
                        required
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />

                    <x-input-label for="email" value="E-mail" class="mt-4" />
                    <x-text-input
                        id="email"
                        name="email"
                        type="email"
                        class="mt-1 block w-full"
                        :value="old('email', $user->email)"
                        required
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    @if ($user->isStudent())
                        <x-input-label for="id_side" value="Lado" class="mt-4" />
                        <select
                            id="id_side"
                            name="id_side"
                            class="mt-1 block w-full rounded-md border-gray-300"
                        >
                            <option
                                value=""
                                @selected (old('id_side', optional($userStudent)->id_side) === null)
                            >
                                Não definido
                            </option>
                            @foreach ($sides as $side)
                                <option
                                    value="{{ $side->id_side }}"
                                    @selected ((int) old('id_side', optional($userStudent)->id_side) === $side->id_side)
                                >
                                    {{ $side->side_name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('id_side')" class="mt-2" />
                    @endif

                    <x-primary-button class="mt-6">Salvar</x-primary-button>
                </form>

                @if ($user->role !== \App\Enums\Role::Coordenador)
                    <hr class="my-6" />

                    <form
                        method="POST"
                        action="{{ route('users.destroy', $user) }}"
                        onsubmit="
                            return confirm(
                                'Excluir este usuário? Esta ação não pode ser desfeita.'
                            );
                        "
                    >
                        @csrf
                        @method ('DELETE')

                        <x-input-label
                            for="delete_password"
                            value="Confirme sua senha para excluir este usuário"
                        />
                        <x-text-input
                            id="delete_password"
                            name="password"
                            type="password"
                            class="mt-1 block w-full"
                            required
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        <button
                            type="submit"
                            class="mt-4 rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700"
                        >
                            Excluir usuário
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
