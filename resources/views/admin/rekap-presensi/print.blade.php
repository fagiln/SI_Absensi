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
    @php
        \Carbon\Carbon::setLocale('id');
    @endphp
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Perusahaan" width="150">
        <h4 class="font-weight-bold">Rekap Presensi Karyawan</h4>
        <p>Bulan: {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}</p>
        <p>Tahun: {{ $year }}</p>
    </div>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                @for ($day = 1; $day <= 15; $day++)
                    <th>{{ \Carbon\Carbon::create()->day($day)->translatedFormat('l') }} {{ $day }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($presensi as $item)
                <tr>
                    <td>{{ $item->user->nik }}</td>
                    <td>{{ $item->user->name }}</td>
                    @for ($day = 1; $day <= 15; $day++)
                        @php
                            $workDate = \Carbon\Carbon::parse($item->work_date);
                        @endphp
                        @if ($workDate->day === $day)
                            <td>{{ \Carbon\Carbon::parse($item->check_in_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($item->check_out_time)->format('H:i') }}</td>
                        @else
                            <td></td>
                        @endif
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                @for ($day = 16; $day <= 31; $day++)
                <th>{{ \Carbon\Carbon::create()->day($day)->translatedFormat('l') }} {{ $day }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($presensi as $item)
                <tr>
                    <td>{{ $item->user->nik }}</td>
                    <td>{{ $item->user->name }}</td>
                    @for ($day = 16; $day <= 31; $day++)
                        @php
                            $workDate = \Carbon\Carbon::parse($item->work_date);
                        @endphp
                        @if ($workDate->day === $day)
                            <td>{{ \Carbon\Carbon::parse($item->check_in_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($item->check_out_time)->format('H:i') }}</td>
                        @else
                            <td></td>
                        @endif
                    @endfor
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
