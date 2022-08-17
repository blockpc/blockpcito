<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <style>
            [x-cloak] { 
                display: none !important;
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @stack('styles')
    </head>
    <body class="font-sans antialiased text-dark bg-dark" x-data="{ mode: localStorage.theme == 'dark' }">
        <div class="min-h-screen">
            @include('layouts.guest.navigation')

            <!-- Page Heading -->
            <header class="shadow">
                <div class="content">
                    @include('layouts.frontend.messages')
                </div>
            </header>

            <main class="content">
                @yield('content')
            </main>
        </div>
        @livewireScripts
        @stack('scripts')
    </body>
</html>