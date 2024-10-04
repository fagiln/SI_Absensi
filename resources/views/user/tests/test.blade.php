<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Navbar Button')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.css') }}">
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="{{ asset('asset/fontawesome/css/all.min.css') }}">

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
        }

        .navbar-bottom a i {
            font-size: 24px;
            display: block;
        }

        .navbar-bottom a.active {
            color: #ff002b;
        }

        /* .navbar-bottom a:hover {
            color: #8d8d8d;
        } */

        .nav-item i {
            margin-bottom: 5px; /* Menambahkan jarak antara ikon dan teks */
        }

        .nav-item span {
            font-size: 14px; /* Ukuran font untuk teks */
            text-align: center; /* Pusatkan teks */
            display: block; /* Pastikan teks berada di bawah ikon */
        }
    </style>
</head>
<body>

<!-- Bottom Navbar -->
<nav class="navbar-bottom">
    <a href="#" class="nav-item">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="#" class="nav-item">
        <i class="far fa-file-alt"></i>
        <span>Cuti</span>
    </a>
    <a href="#" class="nav-item">
        <i class="far fa-calendar-check"></i>
        <span>Riwayat</span>
    </a>
    <a href="#" class="nav-item">
        <i class="far fa-bell"></i>
        <span>Notif</span>
    </a>
    <a href="#" class="nav-item">
        <i class="far fa-user"></i>
        <span>Profile</span>
    </a>
</nav>

</body>
</html>
