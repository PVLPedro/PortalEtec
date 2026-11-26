<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{
            __(
                'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.',
            )
        }}
    </div>

    @auth
        <div
            class="mb-4 flex flex-col gap-1 rounded-small border border-border bg-bg-secondary p-small text-sm"
        >
            <p class="font-medium text-text">Seu identificador no Portal Etec</p>
            <p class="flex flex-wrap items-center gap-1.5 font-mono text-text">
                <span>{{ Auth::user()->name }}</span>
                @if (Auth::user()->formattedIdentifier)
                    <span
                        class="rounded bg-bg-primary px-1.5 py-0.5 text-xs text-secondary"
                    >{{ Auth::user()->formattedIdentifier }}</span>
                @endif
            </p>
            <p class="text-xs text-secondary">
                Gerado automaticamente a partir do e-mail informado no cadastro.
            </p>
        </div>
    @endauth

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-medium text-green-600">
            {{
                __(
                    'A new verification link has been sent to the email address you provided during registration.',
                )
            }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button> {{ __('Resend Verification Email') }} </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
            >
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
