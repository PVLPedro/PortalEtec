<div
    {{
        $attributes->merge([
            'class' => 'fixed inset-0 z-50 flex items-center justify-center bg-black/50 transition-all',
            'x-transition:enter' => 'transition ease-out duration-200',
            'x-transition:enter-start' => 'opacity-0 *:scale-80',
            'x-transition:enter-end' => 'opacity-100 *:scale-100',
            'x-transition:leave' => 'transition ease-in-out duration-100',
            'x-transition:leave-start' => 'opacity-100 *:scale-100',
            'x-transition:leave-end' => 'opacity-0 *:scale-80',
        ])
    }}
>
    {{ $slot }}
</div>
