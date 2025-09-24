<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Admin')</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 h-screen antialiased font-sans">

  <div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    @include('partials.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col">
      {{-- NAVBAR --}}
      @include('partials.navbar')

      {{-- PAGE CONTENT --}}
      <main class="p-8 overflow-auto">
        @yield('content')
      </main>
    </div>
  </div>

</body>
</html>
