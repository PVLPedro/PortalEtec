<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Portal Etec</title>

    <!-- Scripts -->
    @vite (['resources/css/app.css', 'resources/js/app.js'])

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
    />
</head>
<body
    class="grid h-dvh grid-cols-[auto_1fr] grid-rows-[auto_1fr] gap-regular bg-bg-primary p-regular text-text"
>
    @include ('layouts.header')

    @include ('layouts.navigation')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Page Content -->
    <main
        class="grid h-full scrollbar-gutter-both grid-cols-1 gap-regular overflow-auto rounded-large border border-border bg-bg-secondary p-regular *:*:w-full *:w-full *:max-w-8xl *:justify-self-center"
    >
        {{ $slot }}
    </main>

    @stack ('scripts')
</body>
</html>
