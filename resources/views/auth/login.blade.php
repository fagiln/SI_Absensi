<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">
    <title>SI Absensi @yield('title')</title>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class=" py-4 px-5  card card-primary  " style="width: 400px">
            <div class="w-100 d-flex justify-content-center">
                <img src="{{ asset('img/logo.png') }}" alt="" class=""
                    style="width: 200px;">
            </div>
            <p class="fs-3 text-center fw-bolder">Selamat Datang</p>

            <form action="{{ route('login.authenticate') }}" method="POST" class="form">
                @csrf
                <div class="mb-1 label">Username</div>
                <input type="text" class=" form-control" name="username" value="{{ old('username') }}" required
                    placeholder="Masukkan username">
                @error('username')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="mb-1 mt-3 label">Password</div>
                <input type="password" class="mb-3 form-control" name="password" required
                    placeholder="Masukkan Password">


                <button type="submit" class="btn btn-custom w-100 py-2 mt-3">Login</button>
            </form>


        </div>
    </div>
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
</body>

</html>
