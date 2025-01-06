@extends('user.layouts.app')

@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.css') }}">
    <style>
        /* Custom CSS untuk notifikasi */
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            min-height: 600px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            margin: 0 auto;
        }

        /* Custom dropdown */
        .custom-dropdown{
            margin-left: 10px;
            border: 2px solid crimson; /* Warna pinggir merah */
            background-color: white; /* Warna tengah putih */
            color: black; /* Warna teks hitam */
            padding: 8px;
            border-radius: 5px;
            width: 100px;
        }

        .date-input {
            display: block;
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 2px solid crimson; /* Sama dengan border pada dropdown */
            border-radius: 5px;
            background-color: white;
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

        .pagination {
            justify-content: center; /* Menjajarkan pagination ke tengah */
        }
    </style>
</head>
<body style="padding: 15px">
<div class="container">

    <!-- Form Filter Kehadiran -->
    <form id="filterForm" method="GET" action="{{ route('riwayat') }}">
        <label for="daterange">Periode:</label>
          <div class="row">  
            <div class="col-md-4">
                <label for="start_date">Tanggal Awal :</label>
                <input class="date-input" type="date" name="start_date" id="start_date" value="{{ request()->start_date ?? $startDate->toDateString() }}" required>
            </div>
            <div class="col-md-4 mt-2 mt-md-0">
                <label for="end_date">Tanggal Akhir :</label>
                <input class="date-input" type="date" name="end_date" id="end_date" value="{{ request()->end_date ?? $endDate->toDateString() }}" required>
            </div>
          </div>
        <div style="margin-top: 20px"></div>

        <label for="filter">Filter Kehadiran:</label>
        <select id="filter" name="filter" class="custom-dropdown" onchange="this.form.submit()">
            <option value="semua" {{ request('filter') == 'semua' ? 'selected' : '' }}>Semua</option>
            <option value="hadir" {{ request('filter') == 'hadir' ? 'selected' : '' }}>Hadir</option>
            <option value="telat" {{ request('filter') == 'telat' ? 'selected' : '' }}>Telat</option>
            <option value="sakit" {{ request('filter') == 'sakit' ? 'selected' : '' }}>Sakit</option>
            <option value="izin" {{ request('filter') == 'izin' ? 'selected' : '' }}>Izin</option>
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
                @if(empty($riwayatPaginated) || $riwayatPaginated->isEmpty())
                    <tr>
                        <td colspan="5">Tidak ada data kehadiran dan perizinan yang ditemukan.</td>
                    </tr>
                @else
                    @foreach($riwayatPaginated as $data)
                        <tr>
                            <td>{{ $data['tanggal'] }}</td>
                            <td>{{ $data['masuk'] }}</td>
                            <td>{{ $data['pulang'] }}</td>
                            <td>{{ $data['jam_kerja'] }}</td>
                            <td>{{ $data['status'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        
    </div>

    <!-- Pagination -->
    <div class="pagination mt-3">
        @if ($riwayatPaginated->currentPage() > 1)
            <a href="{{ $riwayatPaginated->previousPageUrl() }}" class="text"><< Prev</a>
        @endif

        <span style="margin-left: 5px; margin-right: 5px">Halaman {{ $riwayatPaginated->currentPage() }} dari {{ $riwayatPaginated->lastPage() }}</span>

        @if ($riwayatPaginated->hasMorePages())
            <a href="{{ $riwayatPaginated->nextPageUrl() }}" class="text">Next >></a>
        @endif
    </div>

</div>
<div style="margin-bottom: 70px"></div>
</body>

<script>

    console.log('resah')
    document.addEventListener("DOMContentLoaded", function () {
        const startDateInput = document.getElementById("start_date");
        const endDateInput = document.getElementById("end_date");
        const filterForm = document.getElementById("filterForm");

        // // Fungsi untuk mendapatkan awal dan akhir bulan
        // function getDefaultDates() {
        //     const now = new Date();
        //     const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
        //     const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().split('T')[0];
        //     return { startOfMonth, endOfMonth };
        // }

         // Fungsi untuk mendapatkan awal dan akhir bulan
    function getDefaultDates() {
        const now = new Date();
        const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1); // Tanggal 1 bulan ini
        const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0); // Tanggal terakhir bulan ini

        // Format tanggal sebagai yyyy-mm-dd
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        return { startOfMonth: formatDate(startOfMonth), endOfMonth: formatDate(endOfMonth) };
    }

        // Ambil parameter query dari URL untuk start_date dan end_date
    const urlParams = new URLSearchParams(window.location.search);
    const startDateFromUrl = urlParams.get("start_date");
    const endDateFromUrl = urlParams.get("end_date");

    // Tentukan apakah start_date dan end_date harus direset atau tetap
    const { startOfMonth, endOfMonth } = getDefaultDates();

    // Set nilai default saat halaman dimuat pertama kali
    startDateInput.value = startDateFromUrl || startOfMonth; // Atur ke bulan ini jika tidak ada di URL
    endDateInput.value = endDateFromUrl || endOfMonth; // Atur ke bulan ini jika tidak ada di URL

    // Jika URL mengandung parameter (start_date atau end_date), hapus parameter tersebut dan reset halaman
    if (startDateFromUrl || endDateFromUrl) {
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl); // Reset URL tanpa parameter
    }

        // Fungsi untuk reload halaman dengan query string
        function updateQueryString() {
            const url = new URL(filterForm.action);
            url.searchParams.set("start_date", startDateInput.value);
            url.searchParams.set("end_date", endDateInput.value);
            url.searchParams.set("filter", document.getElementById("filter").value || ""); // Pastikan filter disimpan
            window.location.href = url.toString();
        }

        // Validasi dan event listener untuk start_date
        startDateInput.addEventListener("change", function () {
            if (new Date(startDateInput.value) > new Date(endDateInput.value)) {
                alert("Tanggal awal tidak dapat lebih besar dari Tanggal akhir. Mengatur ulang ke awal bulan.");
                startDateInput.value = startOfMonth;
            }
            endDateInput.min = startDateInput.value; // Set batas minimum end_date
            updateQueryString();
        });

        // Validasi dan event listener untuk end_date
        endDateInput.addEventListener("change", function () {
            if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                alert("Tanggal akhir tidak dapat lebih kecil dari Tanggal awal. Mengatur ulang ke akhir bulan.");
                endDateInput.value = endOfMonth;
            }
            updateQueryString();
        });
    });

</script>

@endsection
