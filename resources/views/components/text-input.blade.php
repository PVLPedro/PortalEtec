@props (['disabled' => false])

<input
    @disabled ($disabled)
    {{
        $attributes->merge([
            'class' => 'flex w-full rounded-small border border-border p-small outline-0 shadow-md',
        ])
    }}
/>
