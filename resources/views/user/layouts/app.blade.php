<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title>@yield('title', 'User Home')</title>

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.css') }}">
     
     <!-- Font Awesome CDN -->
     <link rel="stylesheet" href="{{ asset('asset/fontawesome/css/all.min.css') }}">
     {{-- <link rel="stylesheet" href="{{ asset('datatables/datatables.bundle.css') }}"> --}}

     <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    
    <style>
        .navbar-bottom {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            z-index: 1000;
        }

        .navbar-bottom a {
            text-align: center;
            color: #8eaac3;
            font-size: 14px;
            text-decoration: none
        }

        .navbar-bottom a i {
            font-size: 24px;
            display: block;
        }

        .navbar-bottom a.active {
            color: #ff002b;
        }

        .nav-item i {
            margin-bottom: 5px; /* Menambahkan jarak antara ikon dan teks */
        }

        .nav-item span {
            font-size: 14px; /* Ukuran font untuk teks */
            text-align: center; /* Pusatkan teks */
            display: block; /* Pastikan teks berada di bawah ikon */
        }

        /* box notif */
        /* .a {
            display: flex;
            justify-content: space-around;
            margin: 20px;
    
        }
        .span {
            text-align: center;
            width: 120px;
        } */
        /* .attendance-item img {
            width: 50px;
            height: 50px;
        } */

    </style>
</head>
<body>

    <div class="container">
       
        {{-- <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <a class="navbar-brand" href="#">Aplikasi Presensi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </nav> --}}

        <!-- Konten Halaman -->
        @yield('content')

        {{-- Navbar Bottom --}}

        <nav class="navbar-bottom">
            <a href="{{ route('home') }}" class="nav-item {{ Route::is('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('cuti') }}" class="nav-item {{ Route::is('cuti') ? 'active' : '' }}">
                <i class="far fa-calendar-check"></i>
                <span>Cuti</span>
            </a>
            <a href="{{ route('riwayat') }}" class="nav-item {{ Route::is('riwayat') ? 'active' : '' }}">
                <i class="far fa-file-alt"></i>
                <span>Riwayat</span>
            </a>
            <a href="{{ route('notif') }}" class="nav-item {{ Route::is('notif') ? 'active' : '' }}">
                {{-- <span class="badge-number">{{ session('hadirCount', 0) }}</span> --}}
                <i class="far fa-bell"></i>
                <span>Notif</span>
            </a>
            <a href="{{ route('profile.show') }}" class="nav-item {{ Route::is('profile.show') ? 'active' : '' }}">
                <i class="far fa-user"></i>
                <span>Profile</span>
            </a>
        </nav>
        


    </div>
<script src="{{ asset('asset/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
