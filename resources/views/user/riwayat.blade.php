@extends('user.layouts.app')

@section('content')
<head>
    <title>Riwayat</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.css') }}">
    <style>
        /* Custom CSS untuk notifikasi */
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            margin: 0 auto;
        }

        /* Custom dropdown */
        .custom-dropdown {
            margin-left: 10px;
            border: 2px solid crimson; /* Warna pinggir merah */
            background-color: white; /* Warna tengah putih */
            color: black; /* Warna teks hitam */
            padding: 8px;
            border-radius: 10px;
            width: 100px;
        }

        /* Data tabel css*/
        table {
            width: 100%;
            border-radius: 10px;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px; /* Tambah margin bawah untuk tabel */
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
<body style="padding: 15px">
<div class="container">

    <!-- Form Filter Kehadiran -->
    <form method="GET">
        <label for="daterange">Periode:</label>
        <!-- Memperbaiki dua kali input yang sama -->
        <div class="row">
            <div class="col-md-4">
                <input type="date" id="start_date" name="start_date" value="2024-09-01" />
            </div>
            
            <div class="col-md-4">
                <input type="date" id="end_date" name="end_date" value="2024-09-30" />
            </div>
        </div>

        <div style="margin-top: 20px"></div>

        <label for="filter">Filter Kehadiran:</label>
        <select id="filter" name="filter" class="custom-dropdown">
            <option value="semua">Semua</option>
            <option value="sakit">Sakit</option>
            <option value="izin">Izin</option>
            <option value="hadir">Hadir</option>
        </select>
        
    </form>

    <!-- Tabel Kehadiran Karyawan -->
    <div class="table-responsive mt-4">
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
                @if($kehadiran->isEmpty())
                    <tr>
                        <td colspan="5">Tidak ada data kehadiran ditemukan.</td>
                    </tr>
                @else
                    @foreach($kehadiran as $data)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($data->check_in_time)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->check_in_time)->format('H:i') ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->check_out_time)->format('H:i') ?? '-' }}</td>
                            <td>{{ $data->jam_kerja ?? '-' }} Jam</td>
                            <td>{{ ucfirst($data->status) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    
</div>

</body>
@endsection
