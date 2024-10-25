@extends('user.layouts.app')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuti</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.css') }}">
    <style>
        /* Responsiveness */
        .container {
          background-color: #fff;
          padding: 20px;
          border-radius: 10px;
          max-width: 600px;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
          margin: 0 auto;
        }

        /* Inline element styling */
        .inline {
            display: inline-block;
            margin-right: 2px;
        }

        /* button select */
        .custom-dropdown, .date-input {
            max-width: 100%;
            width: 100%;
            border: 2px solid crimson;
            padding: 8px;
            border-radius: 4px;
            appearance: none;
            position: relative;
            background-color: white;
        }

        .custom-dropdown-container {
            position: relative;
        }

        .custom-dropdown-container .fa-angle-up,
        .custom-dropdown-container .fa-angle-down {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .custom-dropdown-container .fa-angle-up {
            display: none;
        }

        .custom-dropdown.open ~ .fa-angle-up {
            display: block;
        }

        .custom-dropdown.open ~ .fa-angle-down {
            display: none;
        }

        .date-input {
            display: block;
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 2px solid crimson; /* Sama dengan border pada dropdown */
            border-radius: 4px;
            background-color: white;
        }

        /* Form inputs and date period */
        .form-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            width: 100%;
        }

        .form-inline input {
            width: 100%;
            padding: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        /* Style untuk modal gambar */
        .modal {
            display: none; /* Tersembunyi secara default */
            position: fixed; /* Modal tetap pada tempatnya */
            z-index: 1; /* Di atas konten lainnya */
            padding-top: 100px; /* Jarak dari atas layar */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Jika konten melampaui ukuran modal */
            background-color: rgb(0,0,0); /* Background hitam */
            background-color: rgba(0,0,0,0.9); /* Hitam dengan opacity */
        }
        .modal {
            display: none; /* Tersembunyi secara default */
            position: fixed; /* Modal tetap pada tempatnya */
            z-index: 1; /* Di atas konten lainnya */
            padding-top: 100px; /* Jarak dari atas layar */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Jika konten melampaui ukuran modal */
            background-color: rgb(0,0,0); /* Background hitam */
            background-color: rgba(0,0,0,0.9); /* Hitam dengan opacity */
        }

        /* Gambar dalam modal */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }
        /* Gambar dalam modal */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Close button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }
        /* Close button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }

        /* Style untuk hover pada close button */
        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
        /* Style untuk hover pada close button */
        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Responsif */
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }

        .button-submit{
            height: 35px; 
            width: 100px;
            align-content: flex-start;
            color: white;
            background-color: #CC0200;
        }
        /* Responsif */
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }

        .button-submit{
            height: 35px; 
            width: 100px;
            align-content: flex-start;
            color: white;
            background-color: #CC0200;
        }

        /* Data table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid gray;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Keterangan gambar */
        @media (max-width: 600px) {
            .form-inline {
                flex-direction: column;
            }

            .image-preview {
                max-width: 100%;
            }

            textarea {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div style="padding-top: 10px">
    <div class="container">

        <div style="padding: 20px; margin-top: 15px; border: 2px solid crimson; border-radius: 10px">
        
            <form method="POST" action="{{ route('cuti.create') }}" enctype="multipart/form-data">
            @csrf

                <p style="margin: 0; padding: 0; margin-top: 10px; font-size: 18px; font-weight: bold;">Formulir Pengajuan Cuti</p>
                <p style="margin: 0; padding: 0; font-size: 14px;">Ajukan Cuti</p>

                <div style="margin-top: 20px"></div>
                <!-- Pilih Cuti -->
                <div class="inline">
                    <p class="inline" style="font-weight: bold">Pilih Cuti</p>
                    <p class="inline" style="color: crimson">*</p>
                </div>

                <div class="custom-dropdown-container">
                    <select id="filter_izin" name="filter_izin" class="custom-dropdown" onchange="toggleIcon()">
                        <option value="pilihcuti" {{ request('filter_izin') == 'pilihcuti' ? 'selected' : '' }}>Pilih Cuti</option>
                        <option value="sakit" {{ request('filter_izin') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="izin" {{ request('filter_izin') == 'izin' ? 'selected' : '' }}>Izin</option>
                    </select>
                
                    <div>
                        <i class="fas fa-angle-down" id="icon-down"></i>
                        <i class="fas fa-angle-up" id="icon-up" style="display: none;"></i>
                    </div>
                </div>
                <!-- Pesan error -->
                @if($errors->has('filter_izin'))
                    <span class="text-danger">{{ $errors->first('filter_izin') }}</span>
                @endif

                <div style="margin-top: 20px;"></div>

                <!-- Periode Cuti -->
                <div class="inline">
                    <p class="inline" style="font-weight: bold">Periode Cuti</p>
                    <p class="inline" style="color: crimson">*</p>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <label for="start_date">Pilih Tanggal Awal Cuti :</label>
                        <input type="date" name="start_date" id="start_date" class="date-input"
                        {{-- value="{{ request()->start_date }}" --}} >
                        @if($errors->has('start_date'))
                            <span class="text-danger">{{ $errors->first('start_date') }}</span>
                        @endif
                    </div>
                    <div class="col-md-5 mt-2 mt-md-0">
                        <label for="end_date">Pilih Tanggal Akhir Cuti :</label>
                        <input type="date" name="end_date" id="end_date" class="date-input"
                        {{-- value="{{ request()->end_date }}" --}} >
                        @if($errors->has('end_date'))
                            <span class="text-danger">{{ $errors->first('end_date') }}</span>
                        @endif
                    </div>
                </div>

                <div style="margin-top: 20px;"></div>

                <!-- Alasan Cuti -->
                <div class="inline">
                    <p class="inline" style="font-weight: bold">Alasan Cuti</p>
                    <p class="inline" style="color: crimson">*</p>
                </div>

                <textarea name="alasan" placeholder="Tolong jelaskan alasan cuti anda" style="width: 100%; height: 150px; padding: 10px; border: 2px solid crimson; border-radius: 8px; box-sizing: border-box;"></textarea>
                @if($errors->has('alasan'))
                    <span class="text-danger">{{ $errors->first('alasan') }}</span>
                @endif

                <div style="margin-top: 20px;"></div>

                <!-- Unggah Berkas -->
                <p style="font-weight: bold; margin: 0; padding: 0;">Unggah Berkas</p>
                <p style="margin: 0; padding: 0; font-size: 14px;">Silahkan unggah berkas dalam bentuk foto atau pdf</p>
                
                @if($errors->has('file'))
                        <span class="text-danger">{{ $errors->first('file') }}</span>
                @endif
                
                <div class="upload-container" style="text-align: center; padding: 10px;">
                    <input name="file" type="file" id="upload-image" accept="image/*,application/pdf" style="display: block; margin-bottom: 10px;">
                    
                    <div class="image-preview" id="imagePreview" style="border: 1px solid #ddd; width: 300px; height: 300px; display: flex; justify-content: center; align-items: center;">
                        <p>No file chosen</p>
                    </div>
                </div>
                
                <!-- Modal untuk tampilan gambar besar -->
                <div id="imageModal" class="modal">
                    <span class="close">&times;</span>
                    <img class="modal-content" id="modalImage">
                    <div id="caption"></div>
                </div>                                

                <div style="margin-top: 20px;"></div>
                <div class="inline" style="text-align: right; width:100%;">
                    {{-- <button type="reset" style="height: 35px; width: 100px; color: white; background-color: orange; border-radius: 6px;">Batal</button> --}}

                    <button type="submit" class="btn button-submit" 
                    {{-- id="btnAjukan" data-bs-toggle="modal" data-bs-target="#berhasilModal"  --}}
                    @if($AjukanUlang) disabled @endif>Ajukan</button>
                </div>
            </from>   

        </div>

    </div>

    <!-- Modal -->
    {{-- <div class="d-flex justify-content-center">
        <div class="modal fade" id="berhasilModal" tabindex="-1" aria-labelledby="absenModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="max-width: 400px; width: 100%">
                    <div class="modal-body text-center p-4">
                        <!-- Gambar emoji absen -->
                        <img src="{{ asset('img/Emoji_galau.svg') }}" alt="Absen Berhasil" width="100" height="100"> 

                        <!-- Judul modal -->
                        <h4 class="mt-3">Oops!</h4>

                        <!-- Pesan -->
                        <p class="text-muted">Sepertinya Anda belum absen masuk. Silakan lakukan absen pagi terlebih dahulu.</p>

                        <!-- Tombol untuk menutup modal -->
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Siap</button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
   
    {{-- Bagian Tabel --}}

    <div class="table-responsive mt-4">
        <p style="font-weight: bold; margin-top: 20px;">Status</p>
        <table>
            <thead>
                <tr>
                    <th>Tanggal Pengajuan</th>
                    <th>Jenis Cuti</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuanCuti as $cuti)
                <tr>
                    <!-- Tanggal Pengajuan -->
                    <td>{{ \Carbon\Carbon::parse($cuti->created_at)->format('d/m/Y') }}</td>
                    
                    <!-- Jenis Cuti -->
                    <td>{{ $cuti->reason }}</td>
                    
                    <!-- Periode Cuti -->
                    <td>{{ \Carbon\Carbon::parse($cuti->start_date)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($cuti->end_date)->format('d/m/Y') }}</td>
                    
                    <!-- Status Cuti dengan warna sesuai status -->
                    <td>
                        <span style="background-color: 
                            @if ($cuti->status == 'ditolak') 
                                crimson 
                            @elseif ($cuti->status == 'pending') 
                                orange 
                            @elseif ($cuti->status == 'diterima') 
                                green 
                            @else 
                                transparent; 
                            @endif;
                            color: white; 
                            padding: 3px 6px;  {{-- Menambahkan padding di sekitar teks --}}
                            border-radius: 5px;">  {{-- Menambahkan sudut melengkung --}}
                            
                            @if($cuti->status == 'ditolak') Ditolak 
                            @elseif($cuti->status == 'pending') Menunggu 
                            @elseif($cuti->status == 'diterima') Disetujui 
                            @endif
                        </span>
                    </td>                    
    
                    <!-- Icon untuk melihat detail cuti -->
                    <td>
                        <a href="{{ route('cuti-detail', ['id' => $cuti->id]) }}" class="nav-item">
                            <i class="fas fa-edit" style="color: dodgerblue"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div style="margin-bottom: 70px"></div>
{{-- untuk pilih cuti --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cutiDropdown = document.getElementById('filter_izin');
        const defaultOption = cutiDropdown.querySelector('option[value="pilihcuti"]');
        const iconDown = document.getElementById('icon-down');
        const iconUp = document.getElementById('icon-up');

        // Event saat dropdown dibuka (focus)
        cutiDropdown.addEventListener('focus', function () {
            iconDown.style.display = 'none';
            iconUp.style.display = 'block';
        });

        // Event saat dropdown ditutup (blur)
        cutiDropdown.addEventListener('blur', function () {
            iconDown.style.display = 'block';
            iconUp.style.display = 'none';
        });

        // Event saat pilihan dibuat (change)
        cutiDropdown.addEventListener('change', function () {
            if (cutiDropdown.value !== 'pilihcuti') {
                // Disable the 'Pilih Cuti' option after user selects 'Sakit' or 'Izin'
                defaultOption.disabled = true;

                // Toggle icon visibility after selection
                iconDown.style.display = 'block';
                iconUp.style.display = 'none';
            }
        });
    });
</script>
{{-- untuk periode tanggal --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Fungsi untuk mendapatkan tanggal hari ini dalam format yyyy-mm-dd
        function getTodayDate() {
            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0');
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Bulan mulai dari 0, jadi ditambah 1
            const year = today.getFullYear();
            return `${year}-${month}-${day}`;
        }

        // Set minimum tanggal start_date menjadi hari ini
        startDateInput.min = getTodayDate();

        // Event listener untuk start_date
        startDateInput.addEventListener('change', function () {
            const startDateValue = startDateInput.value;

            // Set minimum tanggal akhir (end_date) sesuai dengan start_date yang dipilih
            endDateInput.min = startDateValue;

            // Jika end_date sebelumnya lebih kecil dari start_date yang baru dipilih, reset end_date
            if (endDateInput.value && endDateInput.value < startDateValue) {
                endDateInput.value = ""; // Reset nilai end_date
                alert("Tanggal awal cuti tidak boleh lebih besar dari tanggal akhir cuti.");
            }
        });

        // Event listener untuk end_date
        endDateInput.addEventListener('change', function () {
            const startDateValue = startDateInput.value;
            const endDateValue = endDateInput.value;

            // Validasi jika end_date lebih kecil dari start_date
            if (endDateValue < startDateValue) {
                // Reset end_date dan tampilkan pesan peringatan
                endDateInput.value = "";
                alert("Tanggal awal cuti tidak boleh lebih besar dari tanggal akhir cuti.");
            }
        });
    });
</script>
{{-- Bagian Upload Berkas --}}
<script>
    // Elemen-elemen yang digunakan
    const uploadImage = document.getElementById("upload-image");
    const imagePreview = document.getElementById("imagePreview");
    const modal = document.getElementById("imageModal");
    const modalImage = document.getElementById("modalImage");
    const closeBtn = document.getElementsByClassName("close")[0];

    function handleFileUpload() {
        const file = uploadImage.files[0];  // Ambil file yang dipilih

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.innerHTML = '';  // Kosongkan preview sebelumnya

                if (file.type.startsWith('image/')) {
                    // Jika file adalah gambar
                    const imgElement = document.createElement("img");
                    imgElement.src = e.target.result;  // Tampilkan gambar yang dipilih
                    imgElement.style.maxWidth = "100%";  // Buat gambar responsif
                    imgElement.style.maxHeight = "100%"; // Buat agar gambar tidak melebihi area pratinjau

                    // Tambahkan event listener untuk memperbesar gambar
                    imgElement.addEventListener("click", function() {
                        modal.style.display = "block";
                        modalImage.src = e.target.result;  // Tampilkan gambar besar dalam modal
                    });

                    imagePreview.appendChild(imgElement);  // Tampilkan pratinjau gambar
                } else if (file.type === 'application/pdf') {
                    // Jika file adalah PDF
                    const pdfText = document.createElement("p");
                    pdfText.textContent = 'File PDF telah dipilih';
                    imagePreview.appendChild(pdfText);  // Tampilkan teks PDF
                }
            };

            reader.readAsDataURL(file);  // Baca file untuk menampilkan pratinjau
        } else {
            imagePreview.innerHTML = '<p>No file chosen</p>';  // Jika tidak ada file, tampilkan teks default
        }
    }

    // Event listener untuk input file
    uploadImage.addEventListener("change", handleFileUpload);

    // Event listener untuk tombol close pada modal
    closeBtn.onclick = function() {
        modal.style.display = "none";
    };

    // Event listener untuk menutup modal jika area luar gambar di-klik
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
</script>
{{-- Bagian Tabel --}}
</body>
@endsection
