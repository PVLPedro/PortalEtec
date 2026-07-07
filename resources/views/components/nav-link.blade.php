@props (['active'])

@php
    $generalClass =
        'relative flex w-full items-center gap-regular overflow-hidden rounded-regular p-regular text-lg/tight font-medium hover:bg-bg-secondary-hover';
    $activeClass = $active ?? false ? ' bg-bg-secondary-hover text-accent' : '';
@endphp

<a
    {{
        $attributes->merge([
            'class' => $generalClass . $activeClass,
        ])
    }}
>
    {{ $icon }} {{ $slot }}
</a>

{{-- Organização Teste de Classes Tailwind CSS --}}
<a
    class="relative flex hidden w-full items-center gap-regular overflow-hidden rounded-regular p-regular text-lg/tight font-medium hover:bg-bg-secondary-hover"
>
</a>
