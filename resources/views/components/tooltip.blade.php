<span
    {{
        $attributes->merge([
            'class' =>
                'pointer-events-none absolute -top-smaller left-1/2 z-50 -translate-x-1/2 -translate-y-full rounded-small border border-border bg-tooltip p-small text-sm font-semibold whitespace-nowrap text-text opacity-0 shadow-sm transition-opacity duration-200 group-hover/tooltip:opacity-100',
        ])
    }}
    >{{ $slot }}</span
>
{{--
    The parent element needs these classes: group/tooltip relative
    O elemento pai precisa dessas classes: group/tooltip relative
--}}
