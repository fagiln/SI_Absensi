@extends('user.layouts.app')


@section('content')
<head>
<style>
    .container {
        background-color: #fff;
          padding: 20px;
          border-radius: 10px;
          max-width: 600px;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
          margin: 0 auto;
    }

    .profile img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Mengisi bingkai tanpa merusak rasio aspek */
        cursor: pointer;
    }

    .profile h2 {
        font-size: 24px;
        margin-top: 10px;
    }
    
    .profile p {
        font-size:14px; 
    }
    .profile input"::placeholder{
        font-size: 13px;
        color: gray;
        opacity: 1;
    }
</style>
</head>
   
<body style="padding: 15px">
 
{{-- logout --}}

    <div style="text-align: right; margin-top: 20px;">
        <a style="color: crimson" href="{{ route('home') }}">Logout</a>
    </div>

<div style="margin-top: 20px"></div>

<div class="profile">
{{-- Input nama --}}
<div class="container">

    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            
        <div class="form-group">
            {{-- <label for="photo">Profile Photo</label> --}}
    
            <div style="position: relative; display: inline-block;  width: 125px; height: 125px; overflow: hidden; ">
                <!-- Foto Profil -->
                <img id="profile-photo" src="{{ $user->avatar ? asset('img/' . $user->avatar) : asset('img/user.jpg') }}" alt="Profile Photo" style="max-width: 125px; cursor: pointer; border-radius: 50%;" onclick="document.getElementById('photo-input').click();">
    
                <!-- Ikon Plus -->
                <div style="position: absolute; bottom: 5px; right: 5px; background-color: #ffffff; border-radius: 50%; padding: 2px;">
                    <img src="{{ asset('img/icon_tambah.png') }}" alt="Change Photo" style="width: 20px; height: 20px;">
                </div>
            </div>
    
            <!-- Input File (Hidden) -->
            <input type="file" id="photo-input" name="photo" style="display: none;" onchange="previewPhoto(event)">
    
            <!-- Preview the selected image -->
            <p id="file-name" style="margin-top: 10px;"></p>
        </div>


            {{-- <div class="form-group">                    
            {{-- <img src="{{ $user->avatar ? asset('img/' . $user->avatar) : asset('img/user.jpg') }}" 
            alt="Profile Photo"> --}}
            {{-- </div>  --}}
        <h2>
            {{-- @foreach ($user as $item) --}}

            {{ $user->name }}

            {{-- @endforeach --}}
        </h2>
        <p>
            {{ $user->jabatan }}
        </p>
    </div>

    <form action="{{ route('profile.update_dataprofile') }}" method="POST" >
    {{-- @csrf --}}
        
        <p style="font-size: 15px; font-weight: bold;">Silahkan isi kembali, jika terdapat kesalahan dalam penulisan</p>

        <p class="fs-10">Nama</p>
        <div class="input-group mb-3">
            <input id="nama" name="nama" type="text" class="form-control" 
            placeholder="{{ $user->name}}"
            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">NIK</p>
        <div class="input-group mb-3">
            <input id="NIK" name="NIK" type="number" class="form-control" 
            placeholder="{{ $user->nik }}" 
            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        {{-- <p class="fs-10">Tempat, Tanggal Lahir</p>
        <div class="input-group mb-3">
            <input id="tempat_tgl" name="tempat_tgl" type="text" class="form-control" 
            placeholder=" Contoh: Tempat,10 - 12 - 2010"
            pattern="[A-Za-z\s]+,\s[0-9]{2}\s-\s[0-9]{2}\s-\s[0-9]{4}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div> --}}

        <p class="fs-10">Email</p>
        <div class="input-group mb-3">
            <input id="email" name="email" type="text" class="form-control" 
            placeholder="{{ $user->email }}"
            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">No. Hp</p>
        <div class="input-group mb-3">
            <input value="no_hp" name="no_hp" type="number" class="form-control" 
            placeholder="{{ $user->no_hp }}, Pastikan No. Hp. benar" 
            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>
        
        <div class="d-grid gap-2 col-6 mx-auto">
            <button class="btn btn-danger" type="button">Submit</button>
          </div>
    </form>
</div>

<div style="margin-top: 20px"></div>

{{-- Input Username Password --}}
<div class="container">
    <form action="{{ route('profile.update_userpass') }}" method="POST">
        <p style="font-size: 15px; font-weight: bold;" >Silahkan ganti username dan password</p>

        <p class="fs-10">Username</p>
            <div class="input-group mb-3">
                <input id="username" name="username" type="text" class="form-control" 
                placeholder="Silahkan isi username" 
                hi aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
        <p class="fs-10">Kata Sandi</p>
            <div class="input-group mb-3">
                <input id="password" name="password" type="text" class="form-control" 
                placeholder="Silahkan isi password" 
                hi aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
        
        <div class="d-grid gap-2 col-6 mx-auto">
            <button class="btn btn-danger" type="button">Submit</button>
        </div>
    </form>
</div>
</div>

<div style="margin-bottom: 60px" ></div>

</body>

<script>
    function previewPhoto(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profile-photo');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
        document.getElementById('file-name');
    }
</script>

{{-- <script>
    function validateInput() {
        var input = document.getElementById('tempat_tgl').value;
        var pattern = /^[A-Za-z\s]+,\s[0-9]{2}\s-\s[0-9]{2}\s-\s[0-9]{4}$/;
    
        if (!pattern.test(input)) {
            alert("Format tidak sesuai. Harap isi dengan format: Surabaya, 10 - 10 - 2024");
            return false; // Menghentikan pengiriman form jika format salah
        }
    
        return true; // Melanjutkan pengiriman form jika format benar
    }
</script> --}}

@endsection