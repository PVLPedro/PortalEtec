@props (['active'])

@php
    $generalClass = '';
    $activeClass = $active ?? false ? ' bg-bg-secondary-hover text-accent' : '';
@endphp

<a
    class="relative flex w-full items-center justify-start gap-regular overflow-hidden rounded-regular p-regular text-lg/tight font-medium hover:bg-bg-secondary-hover"
    {{
        $attributes->merge([
            'class' => $generalClass . $activeClass,
        ])
    }}
>
    {{ $icon }} {{ $slot }}
</a>
