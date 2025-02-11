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
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if(config('services.google.analytics_tag'))
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics_tag') }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ config('services.google.analytics_tag') }}');
            </script>
        @endif

        @inertiaHead
        @vite('resources/src/app.ts')
    </head>
    <body>
        @inertia
    </body>
</html>
