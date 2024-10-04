@extends('user.layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.css') }}">
    <style>
        /* Custom CSS untuk notifikasi */
        .container {
            max-width: 100%;
            padding-left: 10px;
            padding-right: 10px;
        }

        .box {
            max-width: 250px;
            margin-left: 10px;
            margin-right: 15px;
            margin-top: 20px;
            padding: 8px;
            border: 2px solid rgb(204, 37, 37); /* Garis tepi dengan warna merah */
            border-radius: 8px; /* Membuat sudut tepi membulat */
            background-color: #f9f9f9;
        }

        .date-container {
            display: flex;
            align-items: center;
            font-size: 18px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            max-width: 350px;
            background-color: #f9f9f9;
        }

        .date {
            margin-right: 10px;
        }

        .icon {
            cursor: pointer;
            font-size: 20px;
        }

        .icon:hover {
            color: #007bff;
        }
        
        /* Sembunyikan input date secara default */
        #date-picker {
            display: none;
        }

        /* border */
        .custom-dropdown {
            margin-left: 10px;
            border: 2px solid rgb(204, 37, 37); /* Warna pinggir merah */
            background-color: white; /* Warna tengah putih */
            color: black; /* Warna teks hitam */
            padding: 8px; /* Padding untuk tampilan yang lebih baik */
            border-radius: 10px; /* Sudut melengkung (opsional) */
            width: 100px; /* Lebar dropdown */
        }

        /* data tabel */
        table {
            width: 100%;
            border-radius: 10px;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
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
    </style>
</head>
<body>
<div class="container">
    <div class="box">
        <div class="date">
            <span style="font-size: 12px;">Periode :</span>
            <span id="start-date" style="font-size: 12px">1 Sep 2024</span> - 
            <span id="end-date" style="font-size: 12px">30 Sep 2024</span>
            <i class="fas fa-calendar-alt icon" style="size: 12px; margin-left: 5px; color: rgb(204, 37, 37)" onclick="showDatePicker()"></i>
        </div>
    </div>
    <div style="padding-top: 10px"></div>
        <select class="custom-dropdown">
            <option value="1">izin</option>
            <option value="2">sakit</option>
        </select>
        <div style="padding-top: 20px"></div>
        <h2>Tabel Kehadiran Karyawan</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Jam Kerja</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>17 Sep 2024</td>
                        <td>08:00</td>
                        <td>17:00</td>
                        <td>8 Jam</td>
                        <td>Hadir</td>
                    </tr>
                    <tr>
                        <td>17 Sep 2024</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>izin</td>
                    </tr>
                    <tr>
                        <td>17 Sep 2024</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>izin</td>
                    </tr>
                    <tr>
                        <td>17 Sep 2024</td>
                        <td>08:00</td>
                        <td>17:00</td>
                        <td>8 Jam</td>
                        <td>hadir</td>
                    </tr>
                </tbody>
            </table>
        
</div>

<!-- Input type date untuk memilih tanggal -->
<input type="date" id="date-picker" onchange="updateDate()">

<!-- Bootstrap JS dan jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('asset/js/bootstrap.bundle.js') }}" ></script>
<script>
    // Data untuk bulan dalam ejaan pendek
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Menampilkan date picker saat icon ditekan
    function showDatePicker() {
        document.getElementById('date-picker').click(); // Memicu klik pada input date
    }

    // Fungsi untuk memperbarui tanggal setelah dipilih
    function updateDate() {
        const datePicker = document.getElementById('date-picker');
        const selectedDate = new Date(datePicker.value);

        // Mendapatkan bulan, tanggal, dan tahun dari tanggal yang dipilih
        const day = selectedDate.getDate();
        const month = selectedDate.getMonth();
        const year = selectedDate.getFullYear();

        // Menampilkan tanggal awal (1st of selected month)
        document.getElementById('start-date').innerText = `1 ${months[month]} ${year}`;

        // Mendapatkan hari terakhir bulan yang dipilih
        const endDay = new Date(year, month + 1, 0).getDate();
        document.getElementById('end-date').innerText = `${endDay} ${months[month]} ${year}`;
    }
</script>
</body>
</html>
