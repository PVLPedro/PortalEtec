@props (['name', 'title' => null, 'open' => false])

<x-backdrop
    x-data="{ open: {{ $open ? 'true' : 'false' }}, payload: {} }"
    x-show="open"
    x-cloak
    x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') { payload = $event.detail; open = true }"
    x-on:close-modal.window="if (!$event.detail?.name || $event.detail.name === '{{ $name }}') open = false"
    x-on:keydown.escape.window="open = false"
>
    <template x-if="open">
        <x-form-modal
            :attributes="$attributes->except(['name', 'title', 'open'])->merge(['method' => 'POST'])"
            x-on:click.outside="open = false"
        >
            @csrf
            <x-close-button x-on:click="open = false" />

            @if ($title)
                <h3 class="py-smaller text-center font-semibold">{{ $title }}</h3>
            @endif

            {{ $slot }}
        </x-form-modal>
    </template>
</x-backdrop>
