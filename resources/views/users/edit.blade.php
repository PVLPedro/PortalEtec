<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-md sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method ('PUT')

                    <x-input-label for="name" value="__('users.edit.label.name')" />
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

                    <x-primary-button class="mt-6">{{ __('users.edit.submit') }}</x-primary-button>
                </form>

                @if ($user->role !== \App\Enums\Role::Coordenador)
                    <hr class="my-6" />

                    <form
                        method="POST"
                        action="{{ route('users.destroy', $user) }}"
                        onsubmit="
                            return confirm(
                                '{{ __('users.edit.delete_confirm_js') }}'
                            );
                        "
                    >
                        @csrf
                        @method ('DELETE')

                        <x-input-label
                            for="delete_password"
                            value="__('users.edit.label.delete_password')"
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
                            {{ __('users.edit.delete_button') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
