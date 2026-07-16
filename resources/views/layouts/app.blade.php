<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Portal Etec</title>

    <!-- Scripts -->
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="grid h-dvh grid-rows-[auto_auto_1fr] gap-small bg-bg-primary p-small text-text md:grid-cols-[auto_1fr] md:grid-rows-[auto_1fr] md:gap-regular md:p-regular"
>
    @include ('layouts.header')

    @include ('layouts.navigation')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <!-- Page Content -->
    <main
        class="grid h-full grid-cols-1 gap-regular overflow-y-auto rounded-large border border-border bg-bg-secondary p-large"
    >
        {{ $slot }}
    </main>

    @stack ('scripts')
</body>
</html>
