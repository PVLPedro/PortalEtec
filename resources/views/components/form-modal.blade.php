<form
    {{
        $attributes->merge([
            'class' =>
                '[&>*:not(.keep-auto)]:w-full relative flex w-full max-w-130 flex-col items-center gap-regular rounded-large bg-bg-secondary p-large shadow-md transition-all',
        ])
    }}
>
    {{ $slot }}
</form>
