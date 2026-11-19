<a
    class="flex items-center justify-center gap-small rounded-md border border-transparent bg-bg-primary px-regular py-small text-sm font-semibold text-secondary underline hover:bg-bg-secondary-hover"
    {{
        $attributes->merge([
            'href' => '',
        ])
    }}
>
    {{ $slot }}
</a>
