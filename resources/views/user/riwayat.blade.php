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
                <label for="end_date">Tanggal Akhri:</label>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const startDateInput = document.getElementById("start_date");
        const endDateInput = document.getElementById("end_date");
        const filterForm = document.getElementById("filterForm");

        // Mendapatkan tanggal bulan ini
        const currentMonthStart = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        const currentMonthEnd = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).toISOString().split('T')[0];

        // Event listener untuk end_date
        endDateInput.addEventListener("change", function() {
            if (endDateInput.value) {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                // Jika end_date lebih kecil dari start_date, reset end_date
                if (new Date(endDate) < new Date(startDate)) {
                    endDateInput.value = currentMonthEnd; // Reset ke akhir bulan ini
                    alert("End date tidak dapat lebih kecil dari start date. Tanggal telah di-reset ke akhir bulan ini.");
                } else {
                    // Reload halaman dengan query string yang diperbarui
                    const url = new URL(filterForm.action);
                    url.searchParams.set("start_date", startDate);
                    url.searchParams.set("end_date", endDate);
                    url.searchParams.set("filter", document.getElementById("filter").value); // Menyimpan filter
                    window.location.href = url;
                }
            }
        });

        // Event listener untuk start_date
        startDateInput.addEventListener("change", function() {
            const startDate = new Date(startDateInput.value);
            endDateInput.min = startDateInput.value; // Set min date untuk end_date

            // Jika start_date lebih besar dari end_date, reset start_date
            if (endDateInput.value && new Date(startDate) > new Date(endDateInput.value)) {
                startDateInput.value = currentMonthStart; // Reset ke awal bulan ini
                endDateInput.value = currentMonthEnd;     // Reset end_date ke akhir bulan ini
                alert("Start date tidak dapat lebih besar dari end date. Tanggal telah di-reset ke bulan ini.");
            }

            // Reset end_date jika lebih kecil dari start_date
            if (endDateInput.value && new Date(endDateInput.value) < startDate) {
                endDateInput.value = currentMonthEnd; // Reset ke akhir bulan ini
                alert("End date tidak dapat lebih kecil dari start date. Tanggal telah di-reset ke akhir bulan ini.");
            }

            // Reload halaman dengan query string yang diperbarui
            const url = new URL(filterForm.action);
            url.searchParams.set("start_date", startDateInput.value || currentMonthStart);
            url.searchParams.set("end_date", endDateInput.value || currentMonthEnd);
            url.searchParams.set("filter", document.getElementById("filter").value); // Menyimpan filter
            window.location.href = url;
        });
    });
</script>

</body>
@endsection
