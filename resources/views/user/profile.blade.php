@extends('user.layouts.app')

@section('content')
<head>
<style>
    .profile img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile h2 {
        font-size: 24px;
        margin-top: 10px;
    }
    
    .profile p {
        font-size:12px; 
    }
    
</style>
</head>

<body>
{{-- logout --}}
<div style="text-align: right; margin-top: 20px;">
    <a style="color: crimson" href="{{ route('home') }}">Logout</a>
</div>

<div class="profile">
    <img src="
    {{-- {{ asset($user['photo']) }} --}}
    " alt="Profile Photo">
    <h2>
        {{-- {{ $user['name'] }} --}}
    </h2>
    {{-- <p>{{ $user['job'] }}</p> --}}

{{-- Input nama --}}
<div class="container">

        <p class="fs-10">Nama</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Silahkan isi nama lengkap" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">NIK</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Silahkan isi nomer NIK " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">Tempat, Tanggal Lahir</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">Email</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Silahkan isi Email" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">No. Hp</p>
        <div class="input-group mb-3">
            <input type="number" class="form-control" placeholder="Silahkan isi No. Hp." aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">Kata Sandi</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Silahkan isi password" hi aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>
        
        <div class="d-grid gap-2 col-6 mx-auto" style="margin-bottom: 90px">
            <button class="btn btn-danger" type="button">submit</button>
          </div>
</div>
</body>
@endsection