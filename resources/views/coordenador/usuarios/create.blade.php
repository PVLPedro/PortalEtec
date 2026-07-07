<x-app-layout>
    <div class="py-12 max-w-md mx-auto">
        <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf

            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />

            <x-input-label for="role" value="Cargo" class="mt-4" />
            <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md active:outline-accent focus:outline-accent shadow-sm px-2 py-1" required>
                <option value="aluno">Aluno</option>
                <option value="professor">Professor</option>
                <option value="coordenador">Coordenador</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />

            <x-input-label for="email" value="E-mail" class="mt-4" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <x-input-label for="cpf" value="CPF" class="mt-4" />
            <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf')" required />
            <x-input-error :messages="$errors->get('cpf')" class="mt-2" />

            <x-input-label for="phone" value="Telefone" class="mt-4" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />

            <x-input-label for="password" value="Senha" class="mt-4" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <x-input-label for="password_confirmation" value="Confirmar Senha" class="mt-4" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />

            <x-primary-button class="mt-6">Cadastrar</x-primary-button>
        </form>
    </div>
</x-app-layout>