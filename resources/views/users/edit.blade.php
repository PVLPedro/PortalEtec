<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-md sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method ('PUT')

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

                    <x-primary-button class="mt-6">Salvar</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
