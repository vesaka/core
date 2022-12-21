<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ isset($title) ? $title . ' | ' : '' }}Admin CMS</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- Fonts -->
        <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap"/>
        <!-- Scripts -->
        @routes
        @vite(['resources/css/app.css'])

        <style>
            body {
                background-color: #bbc3cf;
                background-image: url("data:image/svg+xml,%3Csvg width='6' height='6' viewBox='0 0 6 6' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23e5f2f9' fill-opacity='0.4' fill-rule='evenodd'%3E%3Cpath d='M5 0h1L0 6V5zM6 5v1H5z'/%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
        {{ $styles ?? '' }}
    </head>
    <body class="flex flex-col min-h-screen font-sans antialiased m-0 antialiased">
        <x-admin-top-bar/>
        <div class="flex flex-row flex-grow">
            <x-admin-aside.navbar class="w-3/4 md:w-1/6" :items="$items"/>
            <div class="flex-grow w-3/4 md:w-3/4 p-4 bg-white font-light">
                {{ $slot ?? '' }}
            </div>
        </div>
        <script>
            const env = @json(config('frontend'));
        </script>
         @vite(['packages/vesaka/core/resources/js/admin/app.js', 'packages/vesaka/core/resources/js/admin/dashboard.js'])
        {{  $scripts ?? '' }}
    </body>
</html>