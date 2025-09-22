@if (Auth::check())
  <p>Halo, {{ Auth::user()->username }}</p>

  @if (Auth::user()->role === 'admin')
    <p>Anda login sebagai <b>Admin</b></p>
  @endif

  @if (Auth::user()->hasRole('guru'))
    <p>Anda login sebagai <b>Guru</b></p>
  @endif
@endif
