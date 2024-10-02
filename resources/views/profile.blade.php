@extends('layouts.app')

@section('content')


<div style="text-align: right; margin-top: 20px;">
    <a style="color: crimson" href="{{ route('home') }}">Logout</a>
</div>

<div class="container">

        <p class="fs-10">Nama</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">NIK</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">Tempat, Tanggal Lahir</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">Email</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">No. Hp</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">Kata Sandi</p>
        <div class="input-group mb-3">
            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

</div>
@endsection