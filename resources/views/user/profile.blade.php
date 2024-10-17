@extends('user.layouts.app')

@section('content')
<head>
        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <div style="text-align: right;">
        <i class="fas fa-sign-out-alt" style="color: crimson;"></i>
        <a style="color: crimson; text-decoration: none" href="{{ route('logout') }}">Logout</a>
    </div>

<div style="margin-top: 20px"></div>

<div class="profile">
{{-- Input nama --}}
<div class="container">

    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <form id="foto-profile" enctype="multipart/form-data">
        @csrf    
            
            <div class="form-group">
                {{-- <label for="photo">Profile Photo</label> --}}
        
                <div style="position: relative; display: inline-block;  width: 125px; height: 125px; overflow: hidden; ">
                    <!-- Foto Profil -->
                    <img id="profile_photo" 
                        src="{{ $user->avatar ? asset('storage/photos/' . $user->avatar) : asset('img/user.jpg') }}" 
                        alt="Profile Photo" 
                        style="max-width: 125px; cursor: pointer; border-radius: 50%;" 
                        onclick="document.getElementById('photo-input').click();">
        
                    <!-- Ikon Plus -->
                    <div style="position: absolute; bottom: 5px; right: 5px; background-color: #ffffff; border-radius: 50%;">
                        {{-- <img src="{{ asset('img/icon_tambah.png') }}" alt="Change Photo" style="width: 20px; height: 20px;"> --}}
                        <i class="fas fa-camera" alt="Change Photo" style="width: 20px; height: 20px;"></i>
                    </div>
                </div>
        
                <!-- Input File (Hidden) -->
                <input type="file" id="photo-input" name="photo" style="display: none;" onchange="previewAndUploadPhoto(event)">
        
                <!-- Preview the selected image -->
                <p id="file-name" style="margin-top: 10px;"></p>
            </div>
        </form>

        <h2>
            {{-- @foreach ($user as $item) --}}

            {{ $user->name }}

            {{-- @endforeach --}}
        </h2>
        <p>
            {{ $user->jabatan }}
        </p>
    </div>

    {{-- Input data profile --}}
    <form id="update_dataprofile" action="{{ route('profile.update_dataprofile', $user->id) }}" method="POST" >
    @csrf
        
        <p style="font-size: 15px; font-weight: bold;">Silahkan lengkapi profile atau isi kembali jika terdapat kesalahan penulisan</p>

        <p class="fs-10">Nama</p>
        @error('name')
                <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="input-group mb-3">
            <input id="name" name="name" type="text" class="form-control" 
            placeholder="{{ $user->name}}"
            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">Username</p>
        @error('username')
                <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="input-group mb-3">
            <input id="username" name="username" type="text" class="form-control" 
            placeholder="{{ $user->username }}" 
            hi aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">NIK</p>
        <div class="input-group mb-3">
            <input type="number" class="form-control" 
            {{-- placeholder="{{ $user->nik }}"  --}}
            value="{{ $user->nik }}"
            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly>
        </div>

        <p class="fs-10">Perusahaan</p>
        <div class="input-group mb-3">
            <input class="form-control" 
            {{-- placeholder="{{ $user->nik }}"  --}}
            value="{{ $user->departemen->nama_departemen }}"
            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly>
        </div>

        <p class="fs-10">Email</p>
        @error('email')
                <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="input-group mb-3">
            <input id="email" name="email" type="text" class="form-control" 
            placeholder="{{ $user->email }}"
            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>

        <p class="fs-10">No. Hp</p>
        @error('no_hp')
                <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="input-group mb-3">
            <input id="no_hp" name="no_hp" type="number" class="form-control" 
            placeholder="{{ $user->no_hp }}, Pastikan No. Hp. benar" 
            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>
        
        <div class="d-grid gap-2 col-6 mx-auto">
            <button class="btn btn-danger" type="submit">Submit</button>
          </div>
    </form>
</div>

<div style="margin-top: 20px"></div>

{{-- Input Password --}}
<div class="container">
    <form action="{{ route('profile.update_pass', $user->id) }}" method="POST">
    @csrf
        <p style="font-size: 15px; font-weight: bold;" >Silahkan ganti password</p>

        <p class="fs-10">Kata Sandi</p>
            @error('new_password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <div class="input-group mb-3">
                <input id="new_password" name="new_password" type="password" class="form-control" 
                placeholder="Silahkan isi password" 
                hi aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" >
            </div>
            
        <p class="fs-10">Isi Ulang Kata Sandi</p>
            <div class="input-group mb-3">
                <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control" 
                placeholder="Silahkan isi konfirmasi password" 
                hi aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" >
            </div>

        <div class="d-grid gap-2 col-6 mx-auto">
            <button class="btn btn-danger" type="submit">Submit</button>
        </div>
    </form>
</div>
</div>

<div style="margin-bottom: 60px" ></div>

</body>

<script>
    function previewAndUploadPhoto(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profile_photo');
            output.src = reader.result; // Preview the image
        };
        reader.readAsDataURL(event.target.files[0]);

        // Create FormData object and send file via AJAX
        var formData = new FormData();
        formData.append('photo', event.target.files[0]);
        formData.append('_token', '{{ csrf_token() }}'); // Append CSRF token for security

        // Send AJAX request
        $.ajax({
        url: '{{ url("user/profile/update_foto/") }}', // Ganti dengan ID pengguna
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.success) {
                // Tambahkan timestamp untuk mencegah cache
                $('#profile_photo').attr('src', response.avatar + '?' + new Date().getTime());
            } else {
                alert('Error: ' + response.message);
            }
        }
        });
    }
</script>

{{-- cadangan buat update --}}
{{-- <script>

    document.getElementById('update_dataprofile').addEventListener('submit', function(e) {
    e.preventDefault(); // Mencegah submit form secara default

    // Ambil data dari form
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let no_hp = document.getElementById('no_hp').value;

    // Buat object FormData untuk mengirimkan data
    let formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('no_hp', no_hp);

</script> --}}

@endsection