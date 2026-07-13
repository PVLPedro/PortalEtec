@props (['disabled' => false])

<input
    @disabled ($disabled)
    {{
        $attributes->merge([
            'class' => 'flex w-full rounded-small border border-border p-small shadow-md outline-0',
        ])
    }}
/>
