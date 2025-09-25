<!doctype html>
<html lang="en">
<!--begin::Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi | @yield('title')</title>
    <!--begin::Fonts-->
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
            integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
            crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
            integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
            crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
            integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
            crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!--end::Required Plugin(AdminLTE)-->
    @stack('styles')
</head>
<!--end::Head-->
<!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapse bg-body-tertiary">
<!--begin::App Wrapper-->
<div class="app-wrapper">
    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Start Navbar Links-->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
            </ul>
            <!--end::Start Navbar Links-->
            <!--begin::End Navbar Links-->
            <ul class="navbar-nav ms-auto">
                <!--begin::Fullscreen Toggle-->
                <li class="nav-item">
                    <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                        <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                        <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                    </a>
                </li>
                <!--end::Fullscreen Toggle-->

            </ul>
            <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
    </nav>
    <!--end::Header-->
    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
            <!--begin::Brand Link-->
            <a href="{{ route('dashboard') }}" class="brand-link">
                <!--begin::Brand Image-->
                <img
                        src="{{ asset('img/AdminLTELogo.png') }}"
                        alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow"
                />
                <!--end::Brand Image-->
                <!--begin::Brand Text-->
                <span class="brand-text fw-light">Absensi</span>
                <!--end::Brand Text-->
            </a>
            <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <!--begin::Sidebar Menu-->
                <ul
                        class="nav sidebar-menu flex-column"
                        data-lte-toggle="treeview"
                        role="menu"
                        data-accordion="false"
                >

                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ setActive(['dashboard']) }}">
                            <i class="bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    @if (auth()->user()->hasRole('admin'))
                    <li class="nav-item {{ setMenuOpen(['jurusan', 'kelas', 'guru', 'siswa', 'wali', 'mapel']) }}">
                        <a href="#" class="nav-link {{ setActive(['jurusan', 'kelas', 'guru', 'siswa', 'wali', 'mapel']) }}">
                            <i class="nbi bi-clipboard-data"></i>
                            <p>
                                Master Data
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('jurusan') }}" class="nav-link {{ setActive(['jurusan']) }}">
                                    <i class="bi bi-heart-arrow"></i>
                                    <p>Jurusan</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('kelas') }}" class="nav-link {{ setActive(['kelas']) }}">
                                    <i class="bi bi-heart-arrow"></i>
                                    <p>Kelas</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('guru') }}" class="nav-link {{ setActive(['guru']) }}">
                                    <i class="bi bi-heart-arrow"></i>
                                    <p>Guru</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('siswa') }}" class="nav-link {{ setActive(['siswa']) }}">
                                    <i class="bi bi-heart-arrow"></i>
                                    <p>Siswa</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('wali') }}" class="nav-link {{ setActive(['wali']) }}">
                                    <i class="bi bi-heart-arrow"></i>
                                    <p>Wali</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('mapel') }}" class="nav-link {{ setActive(['mapel']) }}">
                                    <i class="bi bi-heart-arrow"></i>
                                    <p>Mata Pelajaran</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('jadwal') }}" class="nav-link {{ setActive(['jadwal']) }}">
                            <i class="bi bi-kanban"></i>
                            <p>Jadwal</p>
                        </a>
                    </li>
                    @endif

                    @if (auth()->user()->hasRole('guru'))
                    <li class="nav-item">
                        <a href="{{ route('jadwalsaya') }}" class="nav-link {{ setActive(['jadwalsaya']) }}">
                            <i class="bi bi-kanban"></i>
                            <p>Jadwal Saya</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('absensi') }}" class="nav-link {{ setActive(['absensi']) }}">
                            <i class="bi bi-journal-check"></i>
                            <p>Absensi</p>
                        </a>
                    </li>
                    @endif

                    @hasanyrole('guru|admin')
                    <li class="nav-item">
                        <a href="{{ route('laporanabsensi') }}" class="nav-link {{ setActive(['laporanabsensi']) }}">
                            <i class="bi bi-info-lg"></i>
                            <p>Laporan Absensi</p>
                        </a>
                    </li>
                    @endhasanyrole

                    @if (auth()->user()->hasRole('siswa'))
                    <li class="nav-item">
                        <a href="{{ route('absensisaya') }}" class="nav-link {{ setActive(['absensisaya']) }}">
                            <i class="bi bi-info-lg"></i>
                            <p>Absensi Saya</p>
                        </a>
                    </li>
                    @endif

                    @if (auth()->user()->hasRole('wali'))
                    <li class="nav-item">
                        <a href="{{ route('absensianaksaya') }}" class="nav-link {{ setActive(['absensianaksaya']) }}">
                            <i class="bi bi-info-lg"></i>
                            <p>Absensi Anak Saya</p>
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a href="#" class="nav-link" id="logout-btn">
                            <i class="bi bi-box-arrow-right"></i>
                            <p>Logout</p>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>

                </ul>
                <!--end::Sidebar Menu-->
            </nav>
        </div>
        <!--end::Sidebar Wrapper-->
    </aside>
    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                @yield('breadcumb')
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content Header-->


        <!--begin::App Content-->
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">
                @yield('content')
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content-->



    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <footer class="app-footer py-3 bg-light">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 col-md-6 text-center text-md-start mb-2 mb-md-0">
              <strong>
                Copyright &copy; 2025&nbsp;
                <span class="text-decoration-none">Absensi</span>.
              </strong>
              All rights reserved.
            </div>
            <div class="col-12 col-md-6 text-center text-md-end">
              ITG
            </div>
          </div>
        </div>
      </footer>
      
    <!--end::Footer-->
</div>
<!--end::App Wrapper-->
<!--begin::Script-->
<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
        crossorigin="anonymous"
></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"
></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"
></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="{{ asset('js/adminlte.js') }}"></script>
<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif
</script>

<script>
    document.getElementById('logout-btn').addEventListener('click', function(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Kamu akan keluar dari sesi ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, logout!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>
</body>
</html>
