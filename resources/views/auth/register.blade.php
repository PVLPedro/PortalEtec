<x-guest-layout>
    <section
        class="relative flex max-h-[80dvh] w-full max-w-120 scrollbar-gutter-both flex-col items-center gap-large overflow-auto rounded-regular bg-bg-primary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large"></x-theme>

        <a href="{{ route('login') }}">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <p class="text-center font-medium {{ $generalTextClass }}">Bem-vindo(a) ao Portal Etec!</p>

        <form
            method="POST"
            action="{{ route('register') }}"
            class="flex w-full flex-col gap-regular"
        >
            @csrf

            <!-- Name -->
            <div class="">
                <x-input-label for="name" :value="__('auth.register.name')" />
                <x-input-mini-label for="name" :value="__('auth.register.mini_label.name')" />
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

            <!-- Email Address -->
            <div class="">
                <x-input-label for="email" :value="__('auth.register.email')" />
                <x-input-mini-label for="email" :value="__('auth.register.mini_label.email')" />
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
                <x-input-error :messages="$errors->get('email')" class="" />
            </div>

            <!-- Role + RM -->
            <div class="" x-data="{ role: '{{ old('role', '') }}' }">
                <x-input-label for="role" :value="__('auth.register.role')" />
                <x-input-mini-label for="role" :value="__('auth.register.mini_label.role')" />

                <select
                    id="role"
                    name="role"
                    x-model="role"
                    class="flex w-full rounded-small border border-border p-small shadow-md outline-0"
                    required
                >
                    <option value="">Selecione um cargo</option>
                    @foreach (\App\Enums\Role::cases() as $roleCase)
                        <option value="{{ $roleCase->value }}">{{ $roleCase->name }}</option>
                    @endforeach
                </select>

                <x-input-error :messages="$errors->get('role')" class="" />

                <div x-show="role === 'aluno'" x-cloak>
                    <div class="">
                        <x-input-label for="rm" value="RM" />
                        <x-text-input
                            id="rm"
                            name="rm"
                            type="text"
                            maxlength="7"
                            :value="old('rm')"
                        />
                        <x-input-error :messages="$errors->get('rm')" class="mt-2" />
                    </div>
                </div>
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

            <!-- Password -->
            <div class="">
                <x-input-label for="password" :value="__('auth.register.password')" />
                <x-input-mini-label
                    for="password"
                    :value="__('auth.register.mini_label.password')"
                />
                <x-password-rules
                    for="password"
                    :value="__('auth.register.mini_label.password_min_max')"
                />
                <x-password-rules
                    for="password"
                    :value="__('auth.register.mini_label.password_special')"
                />
                <x-password-rules
                    for="password"
                    :value="__('auth.register.mini_label.password_number')"
                />
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
                    <input type="hidden" id="password" name="password" />

                    <label
                        for="toggle-password"
                        class="absolute top-1/2 right-0 -translate-y-1/2 cursor-pointer rounded-small p-small text-secondary peer-checked:hidden hover:bg-bg-primary-hover"
                    >
                        <x-lucide-eye></x-lucide-eye>
                    </label>

                    <label
                        for="toggle-password"
                        class="absolute top-1/2 right-0 hidden -translate-y-1/2 cursor-pointer rounded-small p-small text-secondary peer-checked:block hover:bg-bg-primary-hover"
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
        <script>
            $(document).ready(function () {
                // CPF e phoneefone (já existentes)
                $('.cpf-mask').mask('000.000.000-00', {
                    onComplete: (v) => setFeedback('#cpf', isValidCpf(v)),
                });
                $('.phone-mask').mask('(00) 00000-0000', {
                    onComplete: (v) => setFeedback('#phone', isValidPhone(v)),
                });

                // Name — só valida depois que o usuário sair do campo pela 1ª vez
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

                // Email — mesma lógica
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

                // CPF e phoneefone também merecem o mesmo "touched" pattern no blur
                $('.cpf-mask, .phone-mask').on('blur', function () {
                    const digits = $(this).val().replace(/\D/g, '');
                    if (digits.length === 0) return resetFeedback(this);
                    if (digits.length < 11) setFeedback(this, false);
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

            function isValidCpf(cpf) {
                const n = cpf.replace(/\D/g, '');
                if (n.length !== 11 || /^(\d)\1{10}$/.test(n)) return false;

                let sum = 0,
                    rest;
                for (let i = 1; i <= 9; i++) sum += parseInt(n[i - 1]) * (11 - i);
                rest = (sum * 10) % 11;
                if (rest >= 10) rest = 0;
                if (rest !== parseInt(n[9])) return false;

                sum = 0;
                for (let i = 1; i <= 10; i++) sum += parseInt(n[i - 1]) * (12 - i);
                rest = (sum * 10) % 11;
                if (rest >= 10) rest = 0;
                return rest === parseInt(n[10]);
            }
            function isValidPhone(phone) {
                return phone.replace(/\D/g, '').length === 11;
            }

            $(document).ready(function () {
                setupTogglePassword('toggle-password', 'password');
            });

            function setupTogglePassword(toggleId, inputId) {
                const toggle = document.gephoneementById(toggleId);
                const input = document.gephoneementById(inputId);

                toggle.addEventListener('change', () => {
                    input.type = toggle.checked ? 'text' : 'password';
                });
            }
        </script>
    @endpush
</x-guest-layout>
