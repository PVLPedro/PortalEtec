<x-guest-layout>
    <x-auth-session-status class="" :status="session('status')" />

    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 scrollbar-gutter-both flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large text-text" />

        <a href="{{ route('login') }}">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass }}">Bem-vindo(a) ao Portal Etec!</p>

        <x-form-link href="{{ route('login') }}">
            {{
                __(
                    'auth.register.already_account',
                )
            }}
            <x-slot name="icon">
                <x-lucide-square-arrow-out-up-right
                    class="size-4 stroke-3"
                ></x-lucide-square-arrow-out-up-right>
            </x-slot>
        </x-form-link>

        <form
            method="POST"
            action="{{ route('register') }}"
            class="flex w-full flex-col gap-regular"
        >
            @csrf

            <div class="">
                <x-input-label for="name" :value="__('auth.register.label.name')" />
                <x-text-input
                    id="name"
                    class="block w-full"
                    type="text"
                    name="name"
                    :value="old('name')"
                    :placeholder="__('auth.placeholder.name')"
                    required
                    autofocus
                    autocomplete="name"
                />
                <x-input-error :messages="$errors->get('name')" class="" />
            </div>

            <div x-data="{ role: '{{ old('role', '') }}' }" class="flex flex-col gap-regular">
                <div class="">
                    <x-input-label for="role" :value="__('auth.register.label.role')" />

                    <select id="role" name="role" x-model="role" required>
                        <option value="">Selecione um cargo</option>
                        @foreach (\App\Enums\Role::cases() as $roleCase)
                            @continue ($roleCase === \App\Enums\Role::Aluno)
                            <option value="{{ $roleCase->value }}">{{ $roleCase->name }}</option>
                        @endforeach
                    </select>

                    <x-input-error :messages="$errors->get('role')" class="" />
                </div>

                <div class="">
                    <div x-show="role !== ''" x-cloak>
                        <x-input-label
                            for="etec-worker"
                            value="{{ __('auth.register.label.etec-worker') }}"
                        />
                        <select multiple id="etec-worker" name="etecs[]" class="mt-1 block w-full">
                            <option value="">
                                Etec na qual trabalha (Pode selecionar múltiplas)
                            </option>
                            @foreach ($etecs as $etec)
                                <option
                                    value="{{ $etec->id }}"
                                    {{
                                        in_array($etec->id, old('etecs', []))
                                            ? 'selected'
                                            : ''
                                    }}
                                >
                                    {{ $etec->name }} ({{ $etec->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <x-input-error :messages="$errors->get('etecs')" class="mt-2" />
                </div>

                <div class="">
                    <x-input-label for="email" :value="__('auth.register.label.email')" />
                    <div class="flex items-center gap-small">
                        <x-text-input
                            id="email"
                            class="block w-full"
                            type="email"
                            name="email"
                            :value="old('email')"
                            :placeholder="__('auth.placeholder.email')"
                            required
                            autocomplete="username"
                        />
                        <x-input-label
                            for="email"
                            class="text-sm whitespace-nowrap text-secondary"
                            :value="__('auth.domain.worker')"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="" />
                </div>
            </div>

            <div class="">
                <x-input-label for="password" :value="__('auth.register.label.password')" />
                <div class="relative">
                    <input type="checkbox" id="toggle-password" class="peer sr-only" />

                    <x-text-input
                        id="password"
                        type="password"
                        name="new-password"
                        :placeholder="__('auth.placeholder.password')"
                        class="block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <input type="hidden" id="password-real" name="password" />

                    <label
                        for="toggle-password"
                        class="absolute top-1/2 right-0 -translate-y-1/2 cursor-pointer rounded-small p-small text-secondary peer-checked:hidden hover:bg-bg-secondary-hover"
                    >
                        <x-lucide-eye></x-lucide-eye>
                    </label>

                    <label
                        for="toggle-password"
                        class="absolute top-1/2 right-0 hidden -translate-y-1/2 cursor-pointer rounded-small p-small text-secondary peer-checked:block hover:bg-bg-secondary-hover"
                    >
                        <x-lucide-eye-off></x-lucide-eye-off>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button class=""> {{ __('auth.register.sign_up') }} </x-primary-button>
            </div>
        </form>
    </section>

    @push ('scripts')
        <style>
            .choices__inner,
            .choices__list--dropdown {
                border-radius: var(--radius-small);
                border-color: var(--color-border);
                background-color: var(--color-bg-secondary);
                padding: var(--spacing-small);
                box-shadow: var(--shadow-md);
                font-family: var(--font-poppins);
                color: var(--color-text);
                font-size: var(--text-base);
            }

            .choices__input {
                background-color: transparent;
            }

            .choices__placeholder {
                color: var(--color-secondary);
            }
        </style>

        <script>
            $(function () {
                const roleSelect = document.getElementById('role');

                new Choices(roleSelect, {
                    searchEnabled: false,
                    itemSelectText: '',
                    shouldSort: false,
                });

                roleSelect.addEventListener('change', (event) => {
                    Alpine.$data(roleSelect.closest('[x-data]')).role = event.target.value;
                });

                new Choices('#etec-worker', {
                    searchEnabled: true,
                    removeItemButton: true,
                    itemSelectText: '',
                });

                $('#name')
                    .on('blur', function () {
                        $(this).data('touched', true);
                        setFeedback(this, isValidName($(this).val()));
                    })
                    .on('input', function () {
                        if ($(this).data('touched')) {
                            setFeedback(this, isValidName($(this).val()));
                        }
                    });

                $('#email')
                    .on('blur', function () {
                        $(this).data('touched', true);
                        setFeedback(this, isValidEmail($(this).val()));
                    })
                    .on('input', function () {
                        if ($(this).data('touched')) {
                            setFeedback(this, isValidEmail($(this).val()));
                        }
                    });

                $('#toggle-password').on('change', function () {
                    $('#password').attr('type', this.checked ? 'text' : 'password');
                });

                document.querySelector('form').addEventListener('submit', function () {
                    document.getElementById('password-real').value =
                        document.getElementById('password').value;
                });
            });

            function setFeedback(el, isValid) {
                $(el)
                    .toggleClass('border-green-500 focus:ring-green-500', isValid)
                    .toggleClass('border-red-500 focus:ring-red-500', !isValid)
                    .attr('aria-invalid', !isValid);
            }

            function resetFeedback(el) {
                $(el)
                    .removeClass(
                        'border-green-500 border-red-500 focus:ring-green-500 focus:ring-red-500'
                    )
                    .removeAttr('aria-invalid');
            }

            function isValidName(name) {
                return name.trim().length >= 3 && /^[\p{L}\s]+$/u.test(name.trim());
            }

            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }
        </script>
    @endpush
</x-guest-layout>
