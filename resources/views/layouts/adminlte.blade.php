<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title')SMPN 1 Karang Bahagia</title>
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.css') }}">
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/dist/js/adminlte.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    {{-- Leaflet Dependency --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="null"><i class="fa fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="home" class="brand-link">
                <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">

                <span class="brand-text font-weight-light">SMPN 1 <br>Karang Bahagia</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">

                    <!-- Tampilkan nama user yang sedang login -->
                    <div class="info">
                        <a class="d-block" id="user-name">Halo {{ Auth::user()->nama }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                        {{-- <li class="nav-item">
                            <a href="dashboard" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="users-list" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Data Guru PNS & Honorer
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="presensi" class="nav-link">
                                <i class="nav-icon fas fa-clock"></i>
                                <p>
                                    Data Presensi Harian
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="laporan?isGetReport=true" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>
                                    Laporan Presensi Bulanan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="revisi" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>
                                    Data Revisi Absen
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="keluar" class="nav-link">
                                <i class="nav-icon fas fa-plane-departure"></i>
                                <p>
                                    Data Izin Keluar
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="ketidakhadiran" class="nav-link">
                                <i class="nav-icon fas fa-calendar-times"></i>
                                <p>
                                    Data Izin Tidak Masuk dan Cuti
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" id="title">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title')</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content" id="mainContent">
                <div class="container-fluid">
                    @yield('content')
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-12">
                            @yield('user-content')
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Version 3.0.5
            </div>
            <!-- Default to the left -->
            <strong> &copy; 2023 <a href="#">AdminLTE</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- Script untuk mengganti nama user dengan JavaScript -->
    <script>
        $(document).ready(function() {
            const user_name = '{{ Auth::user()->name }}'; // Mendapatkan nama user dari server-side
            $('.user-name').text(user_name); // Mengubah teks dengan nama user
        });
    </script>

    <script>
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (xhr.status == 401) {
                $('#main-content').html('Session Expired!');
                window.location.href = "/"; // Ganti "/login" dengan URL halaman login Anda
            }
        });

        $(document).ready(function() {
            $('.nav-link').click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                $('#mainContent').load(url);
                $('#title').load(url);
            });
        });
    </script>

</body>

</html>
