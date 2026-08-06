<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl leading-tight font-semibold text-gray-800">{{ __('dashboards.title') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('dashboards.welcome.aluno') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
