@props (['value'])

<label
    class="flex text-sm/tight font-medium text-secondary"
    {{
        $attributes->merge([
            'class' => '',
        ])
    }}
>
    {{ $value ?? $slot }}
</label>
