<x-guest-layout>
    @php
        $workerDomain = \Illuminate\Support\Str::start(__('auth.domain.worker'), '@');
    @endphp

    <x-auth-session-status :status="session('status')" />

    <section
        class="relative flex max-h-[90dvh] w-full max-w-120 flex-col items-center gap-large overflow-auto rounded-regular bg-bg-secondary p-large shadow-md"
    >
        <x-theme class="fixed top-large right-large text-text" />

        <a href="/">
            <x-application-logo class="h-32 w-full fill-current" />
        </a>

        <div class="flex w-full flex-col gap-regular *:flex-1">
            <x-form-link href="{{ url('/') }}">
                <x-lucide-chevron-left />
                Voltar para a seleção
            </x-form-link>
            <x-form-link href="{{ route('login') }}">
                Já possui uma conta? Entre
                <x-lucide-square-arrow-out-up-right class="size-4 stroke-3" />
            </x-form-link>
            <x-form-text>
                Criando uma conta
                <x-lucide-user-plus />
            </x-form-text>
        </div>

        <form method="POST" action="{{ route('register') }}" class="flex w-full flex-col gap-large">
            @csrf

            {{-- <div>
                <x-input-label for="name" :value="__('auth.register.label.name')" />
                <x-text-input
                    id="name"
                    type="text"
                    name="name"
                    :value="old('name')"
                    :placeholder="__('auth.placeholder.name')"
                    required
                    autofocus
                    autocomplete="name"
                >
                    <x-slot name="icon">
                        <x-lucide-user class="shrink-0" />
                    </x-slot>
                </x-text-input>
                <x-input-error :messages="$errors->get('name')" />
            </div> --}}

            <div x-data="{ role: @js(old('role', '')) }" class="flex flex-col gap-large">
                <div>
                    <x-input-label for="role" :value="__('auth.register.label.role')" />

                    <select id="role" name="role" x-model="role" required autofocus>
                        <option value="">Selecione um cargo</option>
                        @foreach (\App\Enums\Role::cases() as $roleCase)
                            @continue ($roleCase === \App\Enums\Role::Aluno)
                            <option
                                value="{{ $roleCase->value }}"
                                @selected (old('role') == $roleCase->value)
                            >
                                {{ $roleCase->name }}
                            </option>
                        @endforeach
                    </select>

                    <x-input-error :messages="$errors->get('role')" />
                </div>

                <div x-show="role !== ''" x-cloak>
                    <x-input-label
                        for="etec-worker"
                        :value="__('auth.register.label.etec-worker')"
                    />

                    <select multiple id="etec-worker" name="etecs[]">
                        @foreach ($etecs as $etec)
                            <option
                                value="{{ $etec->id }}"
                                @selected (in_array($etec->id, old('etecs', [])))
                            >
                                {{ $etec->name }} ({{ $etec->code }})
                            </option>
                        @endforeach
                    </select>

                    <x-input-error :messages="$errors->get('etecs')" />
                </div>

                <div
                    x-data='{
                        emailPrefix: @js(old('email_prefix', old('email') ? str_replace('@cps.sp.gov.br', '', old('email')) : '')),
                    }'
                >
                    <x-input-label for="email" :value="__('auth.register.label.email')" />
                    <div class="flex items-center gap-small">
                        <x-text-input
                            id="email"
                            class="block w-full"
                            type="text"
                            name="email_prefix"
                            x-model="emailPrefix"
                            placeholder="seunome"
                            required
                            autocomplete="username"
                        />
                        <x-input-label
                            for="email"
                            class="text-sm whitespace-nowrap text-secondary"
                            :value="__('auth.domain.worker')"
                        />
                    </div>
                    <p class="mt-1 text-xs text-secondary">
                        Nome gerado:
                        <span
                            class="font-mono"
                            x-text="
                                emailPrefix
                                    ? emailPrefix
                                          .trim()
                                          .split('@')[0]
                                          .normalize('NFD')
                                          .replace(/[\u0300-\u036f]/g, '')
                                          .toUpperCase()
                                    : '—'
                            "
                        ></span>
                    </p>
                    <p class="mt-1 text-xs text-secondary">O nome é gerado automaticamente a partir do e-mail e não pode ser alterado neste formulário.</p>
                    <x-input-error :messages="$errors->get('email_prefix')" class="" />
                    <x-input-error :messages="$errors->get('email')" class="" />
                </div>
            </div>

            <div
                x-data="{
                    email: @js(old('email', '')),
                    domain: @js($workerDomain),
                    shown: '',
                    init() {
                        this.shown = this.suggestion ?? '';
                        this.$watch('suggestion', (value) => {
                            if (value) this.shown = value;
                        });
                    },
                    get local() {
                        return this.email.split('@')[0].trim();
                    },
                    get suggestion() {
                        const full = this.local + this.domain;

                        if (! this.local || this.email.toLowerCase() === full.toLowerCase()) {
                            return null;
                        }

                        return full;
                    },
                    complete() {
                        this.email = this.suggestion;
                        this.$nextTick(() => {
                            this.$refs.email.dispatchEvent(new Event('input', { bubbles: true }));
                            this.$refs.email.focus();
                        });
                    },
                }"
            >
                <x-input-label for="email" :value="__('auth.register.label.email')" />
                <x-text-input
                    id="email"
                    x-ref="email"
                    x-model="email"
                    type="email"
                    name="email"
                    :placeholder="__('auth.placeholder.email')"
                    required
                    autocomplete="username"
                >
                    <x-slot name="icon">
                        <x-lucide-mail class="shrink-0" />
                    </x-slot>
                </x-text-input>

                <div
                    :data-open="suggestion ? true : false"
                    class="group grid grid-rows-[0fr] transition-[grid-template-rows] duration-200 ease-out data-open:grid-rows-[1fr] motion-reduce:transition-none"
                >
                    <div class="overflow-hidden">
                        <button
                            type="button"
                            x-on:click="complete()"
                            :disabled="!suggestion"
                            :tabindex="suggestion ? 0 : -1"
                            :aria-hidden="!suggestion"
                            class="mt-small flex w-full -translate-y-2 cursor-pointer items-center justify-between gap-regular rounded-small border-2 border-border bg-bg-primary p-small text-sm opacity-0 outline-0 transition duration-200 ease-out group-data-open:translate-y-0 group-data-open:opacity-100 hover:bg-bg-primary-hover focus-visible:border-accent motion-reduce:transition-none"
                        >
                            <span class="truncate text-secondary" x-text="shown"></span>
                            <span class="shrink-0 font-semibold text-text">Autocompletar</span>
                        </button>
                    </div>
                </div>

                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="password" :value="__('auth.register.label.password')" />
                <x-password-input
                    :placeholder="__('auth.placeholder.password')"
                    autocomplete="new-password"
                    required
                />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="w-full *:w-full">
                <x-primary-button class="bg-accent text-white hover:bg-accent-hover">
                    <x-lucide-user-plus />
                    {{ __('auth.register.sign_up') }}
                </x-primary-button>
            </div>
        </form>
    </section>

    @push ('scripts')
        <script>
            const WORKER_DOMAIN = @js ($workerDomain).toLowerCase();

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
                    placeholder: true,
                    placeholderValue: 'Etec na qual trabalha (pode selecionar múltiplas)',
                });

                bindLiveValidation('#name', isValidName);
                bindLiveValidation('#email', isValidEmail);
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
                    document.getElementById('password-real').value = document.getElementById('password').value;
                });
            });

            function bindLiveValidation(selector, validator) {
                $(selector)
                    .on('blur', function () {
                        $(this).data('touched', true);
                        setFeedback(this, validator(this.value));
                    })
                    .on('input', function () {
                        if ($(this).data('touched')) {
                            setFeedback(this, validator(this.value));
                        }
                    });
            }

            // O "!" garante que a cor vença o border-border do x-text-input
            function setFeedback(el, isValid) {
                $(el)
                    .toggleClass('border-green-500!', isValid)
                    .toggleClass('border-red-500!', !isValid)
                    .attr('aria-invalid', !isValid);
            }

            function isValidName(name) {
                // Letras (com acento), espaços, apóstrofo, hífen e ponto: D'Ávila, Maria-Clara, Jr.
                return name.trim().length >= 3 && /^[\p{L}\p{M}\s'’.-]+$/u.test(name.trim());
            }

            function isValidEmail(email) {
                const value = email.trim().toLowerCase();

                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) && value.endsWith(WORKER_DOMAIN);
            }

            function isValidEmail(email) {
                return /^[^\s@]+$/.test(email.trim()) && email.trim().length > 0;
            }
        </script>
    @endpush
</x-guest-layout>
