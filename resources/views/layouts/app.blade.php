<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'SMK Assalam Samarang')</title>

    {{-- CSS bawaan Laravel + Tailwind/Bootstrap kalau dipakai --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js (cukup sekali) --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 text-slate-800">
    <div id="app" class="min-h-screen flex flex-col">
        {{-- Navbar/Header --}}
        @include('layouts.header')

        {{-- Konten utama --}}
        <main class="flex-1">
            @yield('content')
        </main>

        {{-- Footer opsional --}}
        <footer class="bg-white shadow mt-4 py-3 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} SMK Assalam Samarang
        </footer>
    </div>
</body>
</html>
