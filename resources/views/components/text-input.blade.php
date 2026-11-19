@props (['disabled' => false, 'icon' => null])

<div class="relative flex w-full items-center">
    @if ($icon)
        <span class="pointer-events-none absolute left-3 flex items-center text-text">
            {!! $icon !!}
        </span>
    @endif

    <input
        @disabled ($disabled)
        {{
            $attributes->class([
                'flex w-full text-text bg-bg-input rounded-small border-2 border-border p-small shadow-md outline-0 focus:border-accent',
                'pl-11' => (bool) $icon,
            ])
        }}
    />
</div>
