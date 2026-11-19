<div
    {{
        $attributes->merge([
            'class' => 'rounded-lg border border-border bg-bg-tertiary p-regular shadow-sm',
        ])
    }}
>
    {{ $slot }}
</div>
