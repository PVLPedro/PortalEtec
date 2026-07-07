<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="grid h-dvh grid-rows-[auto_auto_1fr] gap-small bg-bg-primary p-small text-text md:grid-cols-[auto_1fr] md:grid-rows-[auto_1fr] md:gap-regular md:p-regular"
>
    @include ('layouts.header')

    @include ('layouts.navigation')

    <!-- Page Content -->
    <main>{{ $slot }}</main>

    @stack ('scripts')

    @push ('scripts')
        <script>
            function getById(id) {
                return document.getElementById(id);
            }
            function getByClass(className) {
                return document.querySelector(className);
            }
            function getByClassAll(className) {
                return document.querySelectorAll(className);
            }
        </script>
    @endpush
</body>
</html>
