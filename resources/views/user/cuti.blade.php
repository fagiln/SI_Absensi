@extends('user.layouts.app')

<!DOCTYPE html>
<html lang="en">
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

        /* Radio button styling */
        .radio-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .radio-container input[type="radio"] {
            margin-right: 10px;
            accent-color: red;
        }

        .radio-container label {
            font-size: 16px;
            cursor: pointer;
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

        /* Responsive Design */
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
            <p style="margin: 0; padding: 0; margin-top: 10px; font-size: 18px; font-weight: bold;">Formulir Pengajuan Cuti</p>
            <p style="margin: 0; padding: 0; font-size: 14px;">Ajukan Cuti</p>

            <div style="margin-top: 20px"></div>
            <div class="inline">
                <p class="inline" style="font-weight: bold">Pilih Cuti</p>
                <p class="inline" style="color: crimson">*</p>
            </div>
            <form>
                <!-- Pilihan Izin -->
                <div class="radio-container">
                    <input type="radio" id="izin" name="status" value="izin">
                    <label for="izin">Izin</label>
                </div>

                <!-- Pilihan Sakit -->
                <div class="radio-container">
                    <input type="radio" id="sakit" name="status" value="sakit">
                    <label for="sakit">Sakit</label>
                </div>
            </form>

            <div style="margin-top: 20px;"></div>

            <!-- Periode Cuti -->
            <div class="inline">
                <p class="inline" style="font-weight: bold">Periode Cuti</p>
                <p class="inline" style="color: crimson">*</p>
            </div>

            <form>
                <div class="form-inline">
                    <!-- Tanggal Sekarang -->
                    <input type="date" id="tanggal-sekarang">

                    <!-- Tanggal Besok -->
                    <input type="date" id="tanggal-besok">
                </div>
            </form>

            <div style="margin-top: 20px;"></div>

            <!-- Alasan Cuti -->
            <div class="inline">
                <p class="inline" style="font-weight: bold">Alasan Cuti</p>
                <p class="inline" style="color: crimson">*</p>
            </div>

            <textarea placeholder="Tuliskan alasan cuti kalian" style="width: 100%; height: 150px; padding: 10px; border: 2px solid crimson; border-radius: 8px; box-sizing: border-box;"></textarea>

            <div style="margin-top: 20px;"></div>

            <!-- Unggah Berkas -->
            <p style="font-weight: bold; margin: 0; padding: 0;">Unggah Berkas</p>
            <p style="margin: 0; padding: 0; font-size: 14px;">Silahkan unggah berkas dalam bentuk foto atau pdf</p>

            <div class="upload-container" style="text-align: center; padding: 10px;">
                <input type="file" id="upload-image" accept="image/*,application/pdf" style="display: block; margin-bottom: 10px;">
                
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
            <div class="inline" style="display: flex; justify-content: space-between;">
                <button type="button" style="height: 35px; width: 100px; color: white; background-color: orange; border-radius: 6px;">Batal</button>
                <button type="button" style="height: 35px; width: 100px; color: white; background-color: crimson; border-radius: 6px;">Ajukan</button>
            </div>
            
        </div>

        <div>
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
                    <tr>
                        <td>10/11/2024</td>
                        <td>izin</td>
                        <td>10/11/2024 sd 11/11/2024</td>
                        <td style="color: crimson">Ditolak</td>
                        <td>
                            <a href="{{ route('cuti-detail') }}" class="nav-item">
                                <i class="fas fa-edit" style="color: dodgerblue"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>10/11/2024</td>
                        <td>izin</td>
                        <td>10/11/2024 sd 11/11/2024</td>
                        <td style="color: orange">Menunggu</td>
                        <td><i class="fas fa-edit" style="color: dodgerblue"></i></td>
                    </tr>
                    <tr>
                        <td>10/11/2024</td>
                        <td>izin</td>
                        <td>10/11/2024 sd 11/11/2024</td>
                        <td style="color: green">Disetujui</td>
                        <td><i class="fas fa-edit" style="color: dodgerblue"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    // Mengatur tanggal sekarang dan tanggal besok pada form input
    document.addEventListener("DOMContentLoaded", function() {
        const tanggalSekarang = document.getElementById("tanggal-sekarang");
        const tanggalBesok = document.getElementById("tanggal-besok");

        const today = new Date();
        const tomorrow = new Date();
        tomorrow.setDate(today.getDate() + 1);

        // Format tanggal ke YYYY-MM-DD agar cocok dengan input type="date"
        const formatTanggal = (date) => {
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        // Mengatur nilai tanggal pada input form
        tanggalSekarang.value = formatTanggal(today);
        tanggalBesok.value = formatTanggal(tomorrow);

        // Jika tanggal sekarang diubah, sesuaikan tanggal besok
        tanggalSekarang.addEventListener("change", function() {
            const selectedDate = new Date(tanggalSekarang.value);
            const nextDay = new Date(selectedDate);
            nextDay.setDate(selectedDate.getDate() + 1);
            tanggalBesok.value = formatTanggal(nextDay);
        });
    });
</script>
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
</body>
</html>
