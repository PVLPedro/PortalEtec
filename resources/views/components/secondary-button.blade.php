<button
    class="flex items-center justify-center rounded-md border border-transparent bg-bg-primary px-regular py-small text-sm font-semibold tracking-wide text-text uppercase hover:bg-accent-hover"
    {{
        $attributes->merge([
            'type' => 'button',
            'class' => '',
            'onclick' => '',
        ])
    }}
>
    {{ $slot }}
</button>
