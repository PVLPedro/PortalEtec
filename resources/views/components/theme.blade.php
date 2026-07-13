<div
    id="toggle-theme"
    {{
        $attributes->merge([
            'class' =>
                'flex items-center justify-center rounded-regular p-regular *:size-8 hover:bg-bg-primary-hover',
        ])
    }}
>
    <x-lucide-sun class="inline dark:hidden"></x-lucide-sun>
    <x-lucide-moon class="hidden dark:inline"></x-lucide-moon>
</div>
