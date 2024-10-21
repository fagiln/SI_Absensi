<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">
</head>
<body>
    <div class="text-center mb-4">
        <!-- Ganti src dengan URL/logo perusahaan Anda -->
        <img src="{{ asset('img/logo.png') }}" alt="Logo Perusahaan" width="150">
        <h4 class="font-weight-bold">Rekap Presensi Karyawan</h4>
        <p>Bulan: {{ \Carbon\Carbon::create()->month($month)->format('F') }} </p>
        <p>Tahun: {{ $year }}</p>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tanggal Kerja</th>
                <th>Waktu Check-In</th>
                <th>Waktu Check-Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($presensi as $item)
                <tr>
                    <td>{{ $item->user->nik }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->work_date }}</td>
                    <td>{{ $item->check_in_time }}</td>
                    <td>{{ $item->check_out_time }}</td>
                    <td>{{ $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // Print halaman ini saat halaman dimuat
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>