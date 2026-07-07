<a
    {{
        $attributes->merge([
            'class' =>
                'flex w-full min-w-40 items-center gap-small p-regular font-medium text-nowrap hover:bg-bg-secondary-hover',
        ])
    }}
    >{{ $slot }}</a
>
