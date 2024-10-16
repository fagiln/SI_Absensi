@extends('user.layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <title>Absen masuk dengan Lokasi Spesifik</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            
        }
        .container {
            padding-top: 20px;
            max-width:600px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
        .absen-button {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            width: 100%;
            border-radius: 10px;
        }
        .map {
            margin-top: 20px;
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
        }
        #camera {
            width: 100%;
            max-width: 100%;
            height: auto;
            object-fit: cover;
            border: 1px solid #ccc;
            margin-top: 20px;
        }
        .nav-icons {
            font-size: 20px;
        }
        .nav-item {
            text-align: center;
        }
        #map {
            height: 200px;
            width: 100%;
            margin-top: 20px;
        }
        #peta { height: 300px; }
    </style>

</head>
<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
                <!-- Kamera untuk mengambil foto -->
                <video id="camera" autoplay playsinline></video>
                <button class="absen-button mt-3" id="take-photo">Absen Masuk</button>
                
                <!-- Tempat untuk menampilkan foto yang diambil -->
                <canvas id="photo-canvas" class="d-none"></canvas>
                <img id="photo" class="d-none mt-3" alt="Hasil Foto" style="width: 100%;">

                <!-- Lokasi Pengguna -->
                <div class="mt-4">
                    <p><strong>Lokasi Anda Saat Ini:</strong></p>
                    {{-- <p id="user-location">Mengambil lokasi...</p> --}}
                    <p id="alamat-spesifik"></p> <!-- Untuk menampilkan alamat yang lebih spesifik -->
                </div>

                <!-- Peta Leaflet -->
                <div id="peta"></div>
            </div>
        </div>
        <div style="margin-bottom: 30px"></div>
    </div>
    <div style="margin-bottom: 70px"></div>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
{{-- kamera --}}
<script>
    // File: camera.js
    const video = document.getElementById('camera');
    const canvas = document.getElementById('photo-canvas');
    const photo = document.getElementById('photo');
    const takePhotoButton = document.getElementById('take-photo');

    // Fungsi untuk mengaktifkan kamera
    function enableCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                video.classList.remove('d-none');
            })
            .catch(err => {
                console.error('Kesalahan mengakses kamera: ', err);
            });
    }

    // Fungsi untuk mengambil foto
    function takePhoto() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/png');
        photo.src = dataUrl;
        photo.classList.remove('d-none');
        video.classList.add('d-none');
        canvas.classList.add('d-none');

        alert("Absen berhasil!");

        // Tanyakan apakah ingin mengambil foto lagi
        const takeAnotherPhoto = confirm("Apakah Anda ingin mengambil foto lagi?");
        if (takeAnotherPhoto) {
            // Tampilkan kembali kamera dan sembunyikan hasil foto
            video.classList.remove('d-none');
            photo.classList.add('d-none');
        } else {
            alert("Terima kasih, Anda telah selesai.");
        }
    }

    // Event listener
    takePhotoButton.addEventListener('click', takePhoto);

    // Jalankan kamera saat halaman dimuat
    window.addEventListener('load', enableCamera);

</script>
{{-- lokasi --}}
<script>
    const userLocation = document.getElementById('user-location');
    const alamatSpesifik = document.getElementById('alamat-spesifik');
    let latitude, longitude;

    // Fungsi untuk mendapatkan lokasi pengguna
    // Fungsi untuk mendapatkan lokasi pengguna
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    // userLocation.textContent = `Latitude: ${latitude}, Longitude: ${longitude}`;

                    tampilkanPeta(latitude, longitude);
                    reverseGeocode(latitude, longitude);
                },
                function(error) {
                    // Menangani error dengan lebih jelas
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            userLocation.textContent = 'Izin lokasi ditolak oleh pengguna.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            userLocation.textContent = 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            userLocation.textContent = 'Permintaan lokasi habis waktu.';
                            break;
                        case error.UNKNOWN_ERROR:
                            userLocation.textContent = 'Terjadi kesalahan yang tidak diketahui.';
                            break;
                    }
                }
            );
        } else {
            userLocation.textContent = 'Geolocation tidak didukung oleh browser ini.';
        }
    }

    // Fungsi untuk menampilkan peta Leaflet
    function tampilkanPeta(lat, lng) {
        const leafletMap = L.map('peta').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);

        L.marker([lat, lng]).addTo(leafletMap)
            .bindPopup("Lokasi Anda")
            .openPopup();
    }

    // Fungsi untuk reverse geocoding menggunakan Nominatim
    function reverseGeocode(lat, lng) {
        const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    alamatSpesifik.textContent = `Alamat: ${data.display_name}`;
                } else {
                    alamatSpesifik.textContent = 'Tidak dapat menemukan alamat spesifik.';
                }
            })
            .catch(error => {
                console.error('Kesalahan saat melakukan reverse geocoding:', error);
                alamatSpesifik.textContent = 'Kesalahan saat mengambil alamat spesifik.';
            });
    }

    // Jalankan pengambilan lokasi saat halaman dimuat
    window.addEventListener('load', getLocation);
</script>
</body>
@endsection