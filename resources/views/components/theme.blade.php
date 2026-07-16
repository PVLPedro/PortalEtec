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

@push ('scripts')
    <script>
        $(function () {
            updateTheme();

            $('#toggle-theme').on('click', function () {
                let currentTheme = localStorage.getItem('theme');

                switch (currentTheme) {
                    case 'light':
                        localStorage.setItem('theme', 'dark');
                        break;
                    case 'dark':
                        localStorage.setItem('theme', 'light');
                        break;
                    default:
                        localStorage.setItem('theme', 'light');
                        break;
                }

                updateTheme();
            });

            function updateTheme() {
                let mode = localStorage.getItem('theme');

                if (mode == 'dark') {
                    $('body').addClass('dark');
                } else {
                    $('body').removeClass('dark');
                }
            }
        });
    </script>
@endpush
