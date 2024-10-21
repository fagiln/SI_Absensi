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
            max-width: 600px;
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
        .menu {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .bottom-nav {
            background-color: white;
            box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 0;
            width: 100%;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
        }
        #map {
            height: 300px;
            width: 100%;
            margin-top: 20px;
        }
        #peta {
            height: 680px;
        }
    </style>

    <!-- css leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   crossorigin=""/>

   <!-- leafletjs -->
   <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   crossorigin=""></script>
</head>
<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
                <!-- Kamera untuk mengambil foto -->
                <video id="camera" autoplay playsinline></video>
                <button class="absen-button mt-3" id="take-photo" data-bs-toggle="modal" data-bs-target="#absenModal">Absen Pulang</button>
                
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

    <!-- Bootstrap Modal for Absen Confirmation -->
    <div class="modal fade" id="absenModal" tabindex="-1" aria-labelledby="absenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="{{ asset('img/berhasil.png') }}" alt="Absen Berhasil" width="150" height="100"> 
                    <h4>Alhamdulillah Absen Berhasil!!</h4>
                    <p style="color: #A7A7A7;">Selamat pagi best, semangat ya kerjanya</p>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Siap</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Ambil kamera dan tampilkan di video element
        const video = document.getElementById('camera');
        const canvas = document.getElementById('photo-canvas');
        const photo = document.getElementById('photo');
        const userLocation = document.getElementById('user-location');
        const alamatSpesifik = document.getElementById('alamat-spesifik'); 
        let latitude, longitude;

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error('Kesalahan mengakses kamera: ', err);
            });

        // Ambil foto ketika tombol ditekan
        document.getElementById('take-photo').addEventListener('click', function() {
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
                    tampilkanPeta(latitude, longitude);
                    reverseGeocode(latitude, longitude);
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
            var leafletMap = L.map('peta').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(leafletMap);

            // Tambahkan marker di lokasi pengguna
            L.marker([lat, lng]).addTo(leafletMap)
                .bindPopup("Lokasi Anda: " + lat + ", " + lng)
                .openPopup();
        }

        // Fungsi untuk melakukan reverse geocoding menggunakan Nominatim (OpenStreetMap)
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
    </script>
</body>
</html>
