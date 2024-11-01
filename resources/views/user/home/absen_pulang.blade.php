@extends('user.layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .button-container {
        display: flex;
        justify-content: flex-start; /* Mengatur posisi tombol ke kiri */
        margin-top: 10px; /* Tambahkan margin atas untuk pemisahan dari foto */
        }
        
        .absen-button {
            background-color: red;
            margin-right: 10px; /* Jarak antar tombol */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            width: 100%;
            border-radius: 10px;
        }

        .btn-custom {
            padding: 10px 20px; /* Ukuran padding untuk memperbesar tombol */
            font-size: 16px; /* Ukuran font yang sesuai */
            border-radius: 5px; /* Sudut membulat */
        }

        .modal-footer {
            padding: 10px 0; /* Mengatur jarak antara footer dan konten modal */
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
        <!-- Form Absen -->
<form id="absen-form" action="{{ route('absen_pulang.store') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="photo-data" id="photo-data">
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">
    {{-- <button type="button" id="confirm-absen">Konfirmasi</button> --}}
</form>
        <div class="row">
            <div class="col-md-12">
                <!-- Kamera untuk mengambil foto -->
                <video id="camera" autoplay playsinline></video>
                <!-- Tempat untuk menampilkan foto yang diambil -->
                <canvas id="photo-canvas" class="d-none"></canvas>
                <img id="photo" class="d-none mt-3" alt="Hasil Foto" style="width: 100%;">

                <button class="absen-button mt-3" id="take-photo">Absen Pulang</button>
                <button class="absen-button mt-3 d-none" id="cancel-photo">Absen Ulang</button>

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

    <!-- Bootstrap Modal pop-up konfimasi -->
    <div class="d-flex justify-content-center">
        <div class="modal fade" id="absenModal" tabindex="-1" aria-labelledby="absenModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="justify-content: center">
                <div class="modal-content" style="width: 60%">
                    <div class="modal-body text-center">
                        <!-- Gambar emoji absen berhasil -->
                        <img src="{{ asset('img/Emoji_berhasil.svg') }}" alt="Absen Berhasil" width="150" height="100"> 

                        <!-- Judul modal -->
                        <h4 class="mt-2">Alhamdulillah Absen Berhasil!!</h4>
                        <h5 class="mt-2">Jazakumullah Khoir</h5>

                        <!-- Pesan -->
                        <p class="text-muted">Terimakasih telah absen pulang, Hati - hati di jalan</p>

                        <!-- Tombol untuk menutup modal -->
                        <div class="modal-footer justify-content-center">
                            <button id="button_siap" type="button" class="btn btn-primary btn-custom mx-2" data-bs-dismiss="modal">Siap</button>
                            <a id="waLink" href="#" target="_blank" class="btn btn-success">Kirim Pesan WhatsApp</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    const video = document.getElementById('camera');
    const canvas = document.getElementById('photo-canvas');
    const photo = document.getElementById('photo');
    const takePhotoButton = document.getElementById('take-photo');
    const cancelPhotoButton = document.getElementById('cancel-photo');
    const absenForm = document.getElementById('absen-form');
    let latitude, longitude;

    // Fungsi untuk mengaktifkan kamera
    function enableCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                video.classList.remove('d-none');

                // Membalik tampilan video jika menggunakan kamera depan
                video.style.transform = 'scaleX(-1)';
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

        // Membalikkan gambar jika menggunakan kamera depan
        context.save(); // Simpan state canvas
        context.translate(canvas.width, 0); // Geser posisi gambar
        context.scale(-1, 1); // Balikkan gambar horizontal
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        context.restore(); // Kembalikan state canvas

        const dataUrl = canvas.toDataURL('image/png');
        photo.src = dataUrl;
        photo.classList.remove('d-none'); // Tampilkan foto
        video.classList.add('d-none'); // Sembunyikan video
        canvas.classList.add('d-none'); // Sembunyikan canvas

        // Set data foto ke dalam form untuk dikirim ke server
        document.getElementById('photo-data').value = dataUrl;

        // Sembunyikan tombol absen
        takePhotoButton.classList.add('d-none'); 

        // Membuat kontainer untuk tombol
        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add('button-container'); // Tambahkan class untuk styling

        // Tambahkan kontainer tombol setelah foto
        photo.parentNode.insertBefore(buttonContainer, photo.nextSibling); // Menyisipkan di bawah foto

        // Tombol konfirmasi
        const confirmButton = document.createElement('button');
        confirmButton.id = 'confirm-absen';
        confirmButton.classList.add('absen-button', 'mr-2', 'mt-3'); // Tambahkan margin kanan untuk jarak
        confirmButton.textContent = 'Konfirmasi';
        buttonContainer.appendChild(confirmButton); // Tambahkan tombol konfirmasi ke dalam kontainer

        // Tombol ambil ulang foto
        const retakePhotoButton = document.createElement('button');
        retakePhotoButton.id = 'retake-photo';
        retakePhotoButton.classList.add('absen-button', 'mt-3');
        retakePhotoButton.textContent = 'Ambil Ulang';
        buttonContainer.appendChild(retakePhotoButton); // Tambahkan tombol ambil ulang ke dalam kontainer

        // Event listener untuk konfirmasi foto
        confirmButton.addEventListener('click', confirmAbsen);

        // Event listener untuk mengambil foto ulang
        retakePhotoButton.addEventListener('click', function() {
            // Tampilkan kembali kamera dan sembunyikan hasil foto
            video.classList.remove('d-none');
            photo.classList.add('d-none'); // Sembunyikan foto
            takePhotoButton.classList.remove('d-none'); // Tampilkan tombol absen
            takePhotoButton.textContent = 'Absen'; // Kembalikan tombol menjadi "Absen"
            buttonContainer.remove(); // Hapus kontainer tombol
        });

        confirmButton.addEventListener('click', function() {
            // Cek apakah teks tombol adalah "konfirmasi"
            if (confirmButton.textContent === 'konfirmasi') {  // Perbandingan menggunakan ===
                confirmAbsen();  // Panggil fungsi untuk konfirmasi absen
            }
        });
    }

    
    // Event listener untuk mengambil foto
    takePhotoButton.addEventListener('click', takePhoto);

    // Event listener untuk tombol batal
    cancelPhotoButton.addEventListener('click', function() {
        // Tampilkan kembali kamera dan sembunyikan hasil foto
        video.classList.remove('d-none');
        photo.classList.add('d-none');
        canvas.classList.add('d-none'); // Sembunyikan canvas
        cancelPhotoButton.classList.add('d-none'); // Sembunyikan tombol batal
        takePhotoButton.classList.remove('d-none'); // Tampilkan tombol absen
        takePhotoButton.textContent = 'Absen'; // Kembalikan tombol menjadi "Absen"
    });

    // Jalankan kamera saat halaman dimuat
    window.addEventListener('load', enableCamera);

    // Jalankan pengambilan lokasi saat halaman dimuat
    window.addEventListener('load', getLocation);

    // Lokasi pengguna
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;

                    tampilkanPeta(latitude, longitude);
                    reverseGeocode(latitude, longitude);
                },
                function(error) {
                    alert("Gagal mendapatkan lokasi.");
                }
            );
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
        }
    }

    // Fungsi untuk menampilkan peta
    function tampilkanPeta(lat, lng) {
        const leafletMap = L.map('peta').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);

        L.marker([lat, lng]).addTo(leafletMap)
            .bindPopup("Lokasi Anda")
            .openPopup();
    }

    // Fungsi reverse geocode untuk mendapatkan alamat
    function reverseGeocode(lat, lng) {
        const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    document.getElementById('alamat-spesifik').textContent = `Alamat: ${data.display_name}`;
                } else {
                    document.getElementById('alamat-spesifik').textContent = 'Tidak dapat menemukan alamat spesifik.';
                }
            })
            .catch(error => {
                console.error('Kesalahan saat melakukan reverse geocoding:', error);
                document.getElementById('alamat-spesifik').textContent = 'Kesalahan saat mengambil alamat spesifik.';
            });
    }

    // Fungsi untuk konfirmasi absen dan kirim data
    // function confirmAbsen() {
    //     // Tampilkan modal konfirmasi
    //     const confirmationModal = new bootstrap.Modal(document.getElementById('absenModal'));
    //     confirmationModal.show();
        
    //     setTimeout(function() {
    //             // Set lokasi pengguna ke dalam form
    //             document.getElementById('latitude').value = latitude;
    //             document.getElementById('longitude').value = longitude;

    //             // Kirim form absen ke server
    //             document.getElementById('absen-form').submit();
                
    //             // Redirect ke halaman utama
    //             // window.location.href = '/home'; // Ganti dengan URL halaman utama

    //     }, 1800);
    // }

    function confirmAbsen() {
        
        // Set lokasi pengguna ke dalam form
        document.getElementById('latitude').value = latitude;
        document.getElementById('longitude').value = longitude;
        
        // Buat FormData dari form
        const formData = new FormData(absenForm);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Token CSRF
        
        // Kirim data ke server menggunakan fetch
        fetch('/user/absen_pulang', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json()) // Mengonversi respons menjadi JSON
        .then(response => {
            // Cek apakah respons sukses
            if (response.success) {
                // Ambil informasi yang diperlukan untuk membuat pesan
                var userName = response.userName; // Nama pengguna dari respons
                var checkOutTime = response.checkOutTime; // Jam absen dari respons
                var latitude = response.latitude; // Latitude dari respons
                var longitude = response.longitude; // Longitude dari respons
                var phone = response.adminPhone; // Nomor HP admin dari respons

                // Buat pesan untuk WhatsApp
                var message = `${userName}, absen pulang hari ini ${checkOutTime}.`;
                var waLink = `https://wa.me/${phone}?text=${encodeURIComponent(message)} Lokasi saya: https://www.google.com/maps?q=${latitude},${longitude}`;

                // Tampilkan modal
                const confirmationModal = new bootstrap.Modal(document.getElementById('absenModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                confirmationModal.show();

                // Set link WhatsApp ke elemen di modal
                document.getElementById('waLink').setAttribute('href', waLink);
            }
        })
        .catch(error => {
            // Tangani error jika pengiriman gagal
            console.error('Error:', error);
        });

        // Event listener untuk tombol "siap" di modal yang akan mengarahkan ke home
        document.getElementById('button_siap').addEventListener('click', function() {
            window.location.href = '/home';
        });
    }

</script>
</body>
@endsection