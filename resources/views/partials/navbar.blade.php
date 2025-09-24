<header class="bg-white shadow-sm">
  <div class="flex items-center justify-between px-6 py-3">
    {{-- Kiri: sapaan --}}
    <div>
      <h1 class="text-2xl font-semibold text-slate-800">Selamat Datang Admin</h1>
      <div class="text-sm text-slate-500">
        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
      </div>
    </div>

    {{-- Kanan: profile dropdown --}}
    <div class="relative" x-data="{ open: false }">
      <button
        @click="open = !open"
        class="flex items-center gap-3 focus:outline-none"
        :aria-expanded="open"
        aria-haspopup="true"
      >
        {{-- Logo kecil --}}
        <div class="w-8 h-8 rounded-full border overflow-hidden bg-white">
          <img src="{{ asset('images/logo.png') }}"
               alt="profile"
               class="w-full h-full object-cover">
        </div>
        <span class="text-slate-700 font-medium">Admin</span>
        <svg :class="{ 'rotate-180': open }"
             class="w-4 h-4 text-slate-500 transform transition-transform duration-150"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>

      {{-- Dropdown menu --}}
      <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        x-cloak
        class="absolute right-0 mt-2 w-44 bg-white border rounded-lg shadow z-50"
        role="menu"
      >
        {{-- Profil link (opsional, sesuaikan route kalau ada) --}}
        <a href="#"
           class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100"
           role="menuitem">
          Profil
        </a>

        {{-- Logout form (harus pakai POST + @csrf) --}}
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
                  class="w-full text-left block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100"
                  role="menuitem">
            Logout
          </button>
        </form>
      </div>
    </div>
  </div>
</header>

{{-- Tambahkan Alpine.js (cukup sekali di layout utama, jangan double) --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
