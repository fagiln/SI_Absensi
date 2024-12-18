<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">
    <style>
        @media print {
            .page-break {
                page-break-before: always;
            }

            .signature-container {
                display: flex;
                justify-content: space-between;
                margin-top: 50px;
            }
        }
    </style>
</head>

<body>
    @php
        \Carbon\Carbon::setLocale('id');
        $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;
        $no = 1; // Memulai penomoran dari 1
        // Kelompokkan data presensi berdasarkan user_id
        $presensiGrouped = $presensi->groupBy('user_id');
    @endphp

    <div class="text-center mb-4">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Perusahaan" width="150">
        <h4 class="font-weight-bold">REKAP PRESENSI KARYAWAN</h4>
        <h4 class="font-weight-bold">PT. MULTI POWER ABADI</h4>
        <p class="font-italic">Jl.Gn.Anyar Tambak IV No.50, Gn. Anyar Tambak, Kec. Gn. Anyar, Surabaya, Jawa Timur 60294
        </p>
        <p>Bulan: {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}</p>
        <p>Tahun: {{ $year }}</p>
    </div>

    <!-- Tabel untuk tanggal 1-15 -->

    @php
        \Carbon\Carbon::setLocale('id');
        $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;
        $no = 1;
        $nomor = 1; // Memulai penomoran dari 1
        // Ambil semua data pengguna
        $allUsers = \App\Models\User::where('role', 'user')->get();
    @endphp

    <!-- Tabel untuk tanggal 1-15 -->
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIK</th>
                <th rowspan="2">Nama</th>
                <th colspan="15">Tanggal</th>
            </tr>
            <tr>
                @for ($day = 1; $day <= 15; $day++)
                    <th>{{ \Carbon\Carbon::create($year, $month, $day)->translatedFormat('l') }}, {{ $day }}
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php
                $cutiGrouped = \App\Models\Perizinan::whereMonth('start_date', '<=', $month)
                    ->whereMonth('end_date', '>=', $month)
                    ->where('status', 'diterima')
                    ->get()
                    ->groupBy('user_id');
            @endphp

            @foreach ($allUsers as $user)
                @php
                    $userPresensi = $presensi->where('user_id', $user->id);
                    $userCuti = $cutiGrouped->get($user->id) ?? collect();
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $user->nik }}</td>
                    <td>{{ $user->name }}</td>
                    @for ($day = 1; $day <= 15; $day++)
                        @php
                            $currentDate = \Carbon\Carbon::create($year, $month, $day)->toDateString();
                            $presensiOnDay = $userPresensi->firstWhere('work_date', $currentDate);
                            $isWeekend = \Carbon\Carbon::create($year, $month, $day)->translatedFormat('l');
                            $isCuti = $userCuti->contains(function ($cuti) use ($currentDate) {
                                return $currentDate >= $cuti->start_date && $currentDate <= $cuti->end_date;
                            });
                        @endphp
                        <td>
                            @if ($presensiOnDay)
                                {{ \Carbon\Carbon::parse($presensiOnDay->check_in_time)->format('H:i') }} -
                                {{ $presensiOnDay->check_out_time ? \Carbon\Carbon::parse($presensiOnDay->check_out_time)->format('H:i') : 'Tidak absen pulang' }}
                            @elseif ($isWeekend == 'Sabtu' || $isWeekend == 'Minggu')
                                Libur
                            @elseif ($isCuti)
                                Cuti
                            @endif
                        </td>
                    @endfor
                </tr>
            @endforeach

        </tbody>
    </table>

    <!-- Tabel untuk tanggal 16 hingga akhir bulan -->
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIK</th>
                <th rowspan="2">Nama</th>
                <th colspan="{{ $daysInMonth - 15 }}">Tanggal</th>
            </tr>
            <tr>
                @for ($day = 16; $day <= $daysInMonth; $day++)
                    <th>{{ \Carbon\Carbon::create($year, $month, $day)->translatedFormat('l') }}, {{ $day }}
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($allUsers as $user)
                @php
                    $userPresensi = $presensi->where('user_id', $user->id);
                    $userCuti = $cutiGrouped->get($user->id) ?? collect();
                @endphp
                <tr>
                    <td>{{ $nomor++ }}</td>
                    <td>{{ $user->nik }}</td>
                    <td>{{ $user->name }}</td>
                    @for ($day = 16; $day <= $daysInMonth; $day++)
                        @php
                            $currentDate = \Carbon\Carbon::create($year, $month, $day)->toDateString();
                            $presensiOnDay = $userPresensi->firstWhere('work_date', $currentDate);
                            $isWeekend = \Carbon\Carbon::create($year, $month, $day)->translatedFormat('l');
                            $isCuti = $userCuti->contains(function ($cuti) use ($currentDate) {
                                return $currentDate >= $cuti->start_date && $currentDate <= $cuti->end_date;
                            });
                        @endphp
                        <td>
                            @if ($presensiOnDay)
                                {{ \Carbon\Carbon::parse($presensiOnDay->check_in_time)->format('H:i') }} -
                                {{ $presensiOnDay->check_out_time ? \Carbon\Carbon::parse($presensiOnDay->check_out_time)->format('H:i') : 'Tidak absen pulang' }}
                            @elseif ($isWeekend == 'Sabtu' || $isWeekend == 'Minggu')
                                Libur
                            @elseif ($isCuti)
                                Cuti
                            @endif
                        </td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>


    <div class="container-fluid d-flex justify-content-end mt-5">
        <p>Surabaya, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>
    <div class="signature-container ">
        <div class="text-center">
            <p>Mario Mariyadi</p>
            <br>
            <img src="{{ asset('img/ttd_direktur.png') }}" alt="Tanda Tangan Direktur" style="height: 100px">
            <br>
            <p>(...............................)</p>
            <p class="font-italic">Direktur</p>
        </div>
        <div class="text-center">
            <p>Iqbal</p>
            <br>
            <div style="height: 100px;"></div>
            <p>(...............................)</p>
            <br>
            <p class="font-italic">Admin</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
