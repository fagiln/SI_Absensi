<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin | @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatables/datatables.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('select2/select2.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    @stack('style')
    <style>
        body {
            margin: 0;
        }

        .active {
            background-color: #CC0200 !important;
            color: white !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('/img/logo.png') }}" alt="Thrifty Logo" height="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <span class="nav-link">@yield('title')</span>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" id="dark-mode-toggle">
                        <i class="fas fa-moon" id="theme-icon"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal"data-id="{{ Auth::user()->id }}"
                        data-target="#modalAdmin">
                        Welcome, Admin {{ Auth::user()->username }}!
                    </a>
                </li>
            </ul>

        </nav>
        <!-- /.navbar -->
        <div class="modal fade" id="modalAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Admin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formEditAdmin" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            {{-- <input type="hidden" name="id" id="editId"> --}}
                            <div class="form-group">
                                <label for="adminNo">No tujuan pengiriman Absen</label>
                                <input type="text" class="form-control" name="admin_no" id="adminNo"
                                    placeholder="Masukkan No Whatsapp">
                                @error('admin_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="adminPassword">Password</label>
                                <input type="password" class="form-control" name="admin_password" id="adminPassword"
                                    placeholder="Ubah Password (default 123456)">
                                @error('admin_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-custom">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.dashboard') }}" class="brand-link ">
                <img src="{{ asset('/img/logo.png') }}" alt="AdminLTE Logo" class="brand-image" />
                <span class="brand-text font-weight-bold">ADMIN</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item rounded">
                            <a href="/admin/dashboard" class="nav-link">
                                <i class="fas fa-home nav-icon"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item menu-close menu">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-database"></i>
                                <p>
                                    Data Master
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.index.karyawan') }}" class="nav-link">
                                        <i class="fas fa-user-tie nav-icon"></i>
                                        <p>Karyawan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.index.departemen') }}" class="nav-link">
                                        <i class="fas fa-building nav-icon"></i>
                                        <p>Departemen</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item rounded">
                            <a href="{{ route('admin.monitoring.index') }}" class="nav-link">
                                <i class="fas fa-desktop nav-icon"></i>
                                <p>Monitoring</p>
                            </a>
                        </li>
                        <li class="nav-item rounded">
                            <a href="{{ route('admin.perizinan.index') }}" class="nav-link">
                                <i class="fas fa-notes-medical nav-icon"></i>
                                <p>Perizinan Karyawan</p>
                            </a>
                        </li>
                        <li class="nav-item menu-close menu">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>
                                    Laporan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.presensi.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-user-plus"></i>
                                        <p>Presensi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.rekap-presensi.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>Rekap Presensi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item rounded">
                            <a href="/admin/logout" class="nav-link bg-danger"
                                onclick="return confirm('Anda yakin ingin Logout?')">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 font-weight-bold">@yield('title')</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="p-3">
                @if (session('status'))
                    <div class="mt-3">
                        <div id="success-alert" class="alert alert-success d-flex justify-content-between fade show"
                            role="alert">
                            {{ session('status') }}

                        </div>
                    </div>
                @endif
                @yield('content')
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <span class="text d-none d-sm-inline-block"><strong>This </strong>was made by &#10084; </span>
            <div class="float-right d-none d-sm-inline-block">
                <b>Sistem Informasi Absensi PT. Multi Power Abadi</b>
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->
    {{-- leaflet --}}
    <script src="{{ asset('leaflet/leaflet.js') }}"></script>
    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    {{-- <script src="{{ asset('adminlte/plugins/sparklines/sparkline.js') }}"></script> --}}
    <!-- JQVMap -->
    <script src="{{ asset('adminlte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>

    <script src="{{ asset('adminlte/dist/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('datatables/bootstrap.datatables.js') }}"></script>
    <script src="{{ asset('select2/select2.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('#sidebar .nav-link');
            // const menuLinks = document.querySelectorAll('.menu');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    sidebarLinks.forEach(link => link.classList.remove('active'));
                    this.classList.add('active');
                });
                if (link.href === window.location.href) {
                    link.classList.add('active');
                    const parentMenu = link.closest('.menu');
                    if (parentMenu) {
                        parentMenu.classList.remove('menu-close');
                        parentMenu.classList.add('menu-open');
                    }
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const body = document.body;

            // Fungsi untuk mengganti ikon berdasarkan tema
            function updateThemeIcon() {
                if (body.classList.contains('dark-mode')) {
                    themeIcon.classList.replace('fa-moon', 'fa-sun'); // Ganti ke ikon matahari
                } else {
                    themeIcon.classList.replace('fa-sun', 'fa-moon'); // Ganti ke ikon bulan
                }
            }

            // Cek jika pengguna sudah memilih preferensi dark mode sebelumnya
            if (localStorage.getItem('dark-mode') === 'enabled') {
                body.classList.add('dark-mode');
            }

            // Update ikon saat pertama kali halaman dimuat
            updateThemeIcon();

            darkModeToggle.addEventListener('click', function() {
                body.classList.toggle('dark-mode');

                // Simpan preferensi pengguna di localStorage
                if (body.classList.contains('dark-mode')) {
                    localStorage.setItem('dark-mode', 'enabled');
                } else {
                    localStorage.setItem('dark-mode', 'disabled');
                }

                // Perbarui ikon setelah tema berubah
                updateThemeIcon();
            });
        });



        $(document).on('click', 'a[data-toggle="modal"]', function() {
            var userId = $(this).data('id');
            var url = '/admin/' + userId + '/edit';
            var updateUrl = '/admin/edit/' + userId;
            // Request AJAX untuk mendapatkan data karyawan berdasarkan ID
            $.get(url, function(data) {

                $('#adminNo').val(data.no_hp);
                $('#formEditAdmin').attr('action', updateUrl);
            })
        });

        $(document).ready(function() {

            // Check if there are validation errors and show modal
            @if ($errors->has('admin_password'))
                $('#modalAdmin').modal('show');
            @endif
        });

        document.addEventListener('DOMContentLoaded', function() {
            var alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(function() {
                    var bootstrapAlert = new bootstrap.Alert(alert);
                    bootstrapAlert.close();
                }, 3000); // waktu dalam milidetik (5000 ms = 5 detik)
            }
        });
    </script>
    @stack('scripts')

</body>

</html>
