<button
    class="flex items-center justify-center rounded-md border border-transparent bg-accent px-regular py-small text-sm font-semibold tracking-wide text-white uppercase hover:bg-accent-hover"
    {{
        $attributes->merge([
            'type' => 'submit',
            'class' => '',
        ])
    }}
>
    {{ $slot }}
</button>
