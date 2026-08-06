<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('auth.verify_email.description') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ __('auth.verify_email.link_sent') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button> {{ __('auth.verify_email.resend') }} </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
            >
                {{ __('auth.verify_email.logout') }}
            </button>
        </form>
    </div>
</x-guest-layout>
