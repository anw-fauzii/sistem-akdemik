<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 ">
            <!-- Logo Section -->
            <a href="/">
                <img src="{{ asset('storage/logo/samping.png') }}" alt="Logo" class="mx-auto w-3/4 sm:w-1/2 md:w-1/4 lg:w-1/8 xl:w-1/2" />
            </a>

            <!-- Form Content Section -->
            <div class="w-11/12 sm:max-w-md mt-4 px-4 sm:px-6 md:px-8 py-4 bg-white  shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        <script>
            @if (Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @elseif(Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif(Session::has('warning'))
                toastr.warning('{{ Session::get('warning') }}');
            @endif
        </script>
    </body>
</html>
