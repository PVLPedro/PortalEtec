@props (['value'])

<label
    {{
        $attributes->merge([
            'class' => 'flex font-medium text-text',
        ])
    }}
>
    {{ $value ?? $slot }}
</label>
