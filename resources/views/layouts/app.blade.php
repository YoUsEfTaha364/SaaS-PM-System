<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Project Management') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

  <script>
    window.userId={{ Auth::user()->id }}
  </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col transition-all duration-300" :class="{ 'lg:ml-64': sidebarOpen }">
            
            <!-- Topbar -->
            @include('partials.header')

            @isset($header)
                <div class="bg-white border-b border-gray-100 px-4 sm:px-6 lg:px-8 py-4">
                    {{ $header }}
                </div>
            @endisset

            <main class="flex-grow p-4 sm:p-6 lg:p-8">
                @hasSection('main-content')
                    @yield('main-content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>

        </div>
    </div>

    @stack('modals')
    
    @stack('scripts')
</body>
</html>
