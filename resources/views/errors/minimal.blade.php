<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="manifest" href="/build/manifest.webmanifest"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
        <meta name="msapplication-TileImage" content="{{ asset('build/assets/logo.svg') }}">
        <meta name="apple-touch-icon" content="{{ asset('build/assets/logo.svg') }}">
        <meta name="msapplication-TileColor" content="#F1EDE7">

        <title>@yield('title')</title>

        @vite('resources/src/app.scss')
    </head>
    <body class="antialiased">
        <div class="bg-base-100 w-full px-16 md:px-0 h-screen flex items-center justify-center">
            <div class="card bg-neutral text-neutral-content shadow-2xl min-w-[400px]">
                <div class="card-body items-center">
                    <p class="text-6xl md:text-7xl lg:text-9xl font-bold font-sans tracking-wider text-primary">@yield('code')</p>
                    <p class="text-2xl md:text-3xl lg:text-5xl font-serif italic text-primary">@yield('message')</p>
                </div>
            </div>
        </div>
    </body>
</html>
