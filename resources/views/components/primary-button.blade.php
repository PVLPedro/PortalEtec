<button
    {{
        $attributes->merge([
            'type' => '',
            'class' =>
                'flex items-center justify-center gap-small rounded-small border border-transparent p-small text-sm font-semibold tracking-wide uppercase',
        ])
    }}
>
    {{ $slot }}
</button>
