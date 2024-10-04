<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen Pulang dengan Lokasi Spesifik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            height: 300px;
            width: 100%;
            margin-top: 20px;
        }
        #peta { height: 680px; }
    </style>

    <!-- css leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>

   <!-- leafletjs -->
   <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
</head>
<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
                <!-- Kamera untuk mengambil foto -->
                <video id="camera" autoplay playsinline></video>
                <button class="absen-button mt-3" id="take-photo">Absen Pulang</button>
                
                <!-- Tempat untuk menampilkan foto yang diambil -->
                <canvas id="photo-canvas" class="d-none"></canvas>
                <img id="photo" class="d-none mt-3" alt="Hasil Foto" style="width: 100%;">

                <!-- Lokasi Pengguna -->
                <div class="mt-4">
                    <p><strong>Lokasi Anda Saat Ini:</strong></p>
                    <p id="user-location">Mengambil lokasi...</p>
                    <p id="alamat-spesifik"></p> <!-- Untuk menampilkan alamat yang lebih spesifik -->
                </div>

                <!-- Peta Leaflet -->
                <div id="peta"></div>
            </div>
        </div>
    </div>

    <!-- Navigasi Bawah -->
    <nav class="navbar navbar-light bg-light fixed-bottom">
        <div class="container-fluid justify-content-around">
            <a class="nav-item" href="#"><i class="nav-icons bi bi-house-door"></i><br>Home</a>
            <a class="nav-item" href="#"><i class="nav-icons bi bi-calendar"></i><br>Cuti</a>
            <a class="nav-item" href="#"><i class="nav-icons bi bi-file-earmark-text"></i><br>Riwayat</a>
            <a class="nav-item" href="#"><i class="nav-icons bi bi-bell"></i><br>Notifikasi</a>
            <a class="nav-item" href="#"><i class="nav-icons bi bi-person"></i><br>Profil</a>
        </div>
    </nav>

    <script>
        // Ambil kamera dan tampilkan di video element
        const video = document.getElementById('camera');
        const canvas = document.getElementById('photo-canvas');
        const photo = document.getElementById('photo');
        const takePhotoButton = document.getElementById('take-photo');
        const userLocation = document.getElementById('user-location');
        const alamatSpesifik = document.getElementById('alamat-spesifik'); // Tambahan
        let latitude, longitude;

        // Minta akses kamera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error('Kesalahan mengakses kamera: ', err);
            });

        // Ambil foto ketika tombol ditekan
        takePhotoButton.addEventListener('click', function() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Konversi gambar dari canvas ke data URL
            const dataUrl = canvas.toDataURL('image/png');
            photo.src = dataUrl;
            photo.classList.remove('d-none');

            // Sembunyikan video dan tampilkan hasil foto
            video.classList.add('d-none');
            canvas.classList.add('d-none');

            // Pop-up konfirmasi setelah absen
            alert("Absen berhasil!");

            // Tampilkan peta lokasi di bawah setelah absen
            if (latitude && longitude) {
                tampilkanPeta(latitude, longitude);
            }
        });

        // Mendapatkan lokasi pengguna menggunakan Geolocation API
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    userLocation.textContent = `Lat: ${latitude}, Long: ${longitude}`;

                    // Tampilkan peta dengan lokasi pengguna
                    tampilkanPeta(latitude, longitude);

                    // Panggil reverse geocoding untuk mendapatkan alamat spesifik (Tambahan)
                    reverseGeocode(latitude, longitude); // Tambahan
                },
                function(error) {
                    userLocation.textContent = 'Tidak dapat mengambil lokasi.';
                }
            );
        } else {
            userLocation.textContent = 'Geolocation tidak didukung oleh browser ini.';
        }

        // Fungsi untuk menampilkan peta Leaflet dengan marker
        function tampilkanPeta(lat, lng) {
            // Atur peta leaflet
            var leafletMap = L.map('peta').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(leafletMap);

            // Tambahkan marker di lokasi pengguna
            L.marker([lat, lng]).addTo(leafletMap)
                .bindPopup("Lokasi Anda: " + lat + ", " + lng)
                .openPopup();
        }

        // Fungsi untuk melakukan reverse geocoding menggunakan Nominatim (OpenStreetMap) -- Tambahan
        function reverseGeocode(lat, lng) { // Tambahan
            const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`; // Tambahan

            fetch(url) // Tambahan
                .then(response => response.json()) // Tambahan
                .then(data => { // Tambahan
                    if (data && data.display_name) { // Tambahan
                        alamatSpesifik.textContent = `Alamat: ${data.display_name}`; // Tambahan
                    } else { // Tambahan
                        alamatSpesifik.textContent = 'Tidak dapat menemukan alamat spesifik.'; // Tambahan
                    } // Tambahan
                }) // Tambahan
                .catch(error => { // Tambahan
                    console.error('Kesalahan saat melakukan reverse geocoding:', error); // Tambahan
                    alamatSpesifik.textContent = 'Kesalahan saat mengambil alamat spesifik.'; // Tambahan
                }); // Tambahan
        } // Tambahan
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
