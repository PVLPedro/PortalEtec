<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input
                id="name"
                class="mt-1 block w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-1 block w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Etec --}}
        <div class="mt-4">
            <x-input-label for="etec_id" :value="__('Etec')" />
            <select
                id="etec_id"
                name="etec_id"
                class="mt-1 block w-full rounded-md border-gray-300 px-2 py-1 shadow-sm focus:outline-accent active:outline-accent"
                required
            >
                <option value="">Selecione uma Etec</option>
                @foreach ($etecs as $etec)
                    <option
                        value="{{ $etec->id }}"
                        {{
                            old('etec_id') == $etec->id
                                ? 'selected'
                                : ''
                        }}
                    >
                        {{ $etec->nome }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('etec_id')" class="mt-2" />
        </div>

        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
        />
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script>
            new Choices('#etec_id', { searchEnabled: true, itemSelectText: '' });
        </script>

        {{-- CPF --}}
        <div class="mt-4">
            <x-input-label for="cpf" value="CPF" class="mt-4" />
            <x-text-input
                id="cpf"
                name="cpf"
                type="text"
                class="mt-1 block w-full"
                :value="old('cpf')"
                required
            />
            <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
        </div>

        {{-- Telephone --}}
        <div class="mt-4">
            <x-input-label for="phone" value="Telefone" class="mt-4" />
            <x-text-input
                id="phone"
                name="phone"
                type="text"
                class="mt-1 block w-full"
                :value="old('phone')"
                required
            />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        {{-- Role + RM --}}
        <div class="mt-4" x-data="{ role: '{{ old('role', '') }}' }">
            <x-input-label for="role" :value="__('Cargo')" />

            <select
                id="role"
                name="role"
                x-model="role"
                class="mt-1 block w-full rounded-md border-gray-300 px-2 py-1 shadow-sm focus:outline-accent active:outline-accent"
                required
            >
                <option value="">Selecione um cargo</option>
                @foreach (\App\Enums\Role::cases() as $roleCase)
                    <option value="{{ $roleCase->value }}">{{ $roleCase->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />

            <div x-show="role === 'aluno'" x-cloak>
                <div class="mt-4">
                    <x-input-label for="rm" value="RM" />
                    <x-text-input id="rm" name="rm" type="text" maxlength="7" :value="old('rm')" />
                    <x-input-error :messages="$errors->get('rm')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />

            <x-text-input
                id="password_confirmation"
                class="mt-1 block w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <a
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                href="{{ route('login') }}"
            >
                {{ __('Já cadastrado?') }}
            </a>

            <x-primary-button class="ms-4"> {{ __('Cadastrar') }} </x-primary-button>
        </div>
    </form>
</x-guest-layout>
