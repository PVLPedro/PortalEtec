@props (['value'])

<label
    class="flex w-full rounded-small bg-bg-secondary p-smaller text-sm/tight font-medium text-secondary"
    {{
        $attributes->merge([
            'class' => '',
        ])
    }}
>
    {{ $value ?? $slot }}
</label>
