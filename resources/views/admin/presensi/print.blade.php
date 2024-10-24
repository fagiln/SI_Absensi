<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Laporan Presensi Karyawan</title>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
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
    @endphp
    <div class="container mt-5">
        <div class="text-center mb-4">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Perusahaan" width="150" class="mb-3">
            <h4>Laporan Presensi Karyawan</h4>
            <p>Bulan: {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</p>
        </div>


        <table class="mb-3">

            <tbody>
                <tr>
                    <td rowspan="6" class="align-middle">
                        <img src="{{ asset('img/logo.png') }}" alt="" width="100" class="mr-3">
                    </td>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $karyawan->nik }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $karyawan->name }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $karyawan->jabatan }}</td>
                </tr>
                <tr>
                    <td>Departemen</td>
                    <td>:</td>
                    <td>{{ $karyawan->departemen->nama_departemen }}</td>
                </tr>
                <tr>
                    <td>No.Hp</td>
                    <td>:</td>
                    <td>{{ $karyawan->no_hp }}</td>
                </tr>
            </tbody>
        </table>


        <table class="table table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal Kerja</th>
                    <th>Jam Datang</th>
                    <th>Foto Datang</th>
                    <th>Jam Pulang</th>
                    <th>Foto Pulang</th>
                    <th>Status</th>
                    <th>Jml Jam Kerja</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($presensi as $item)
                    @php
                        $checkInTime = \Carbon\Carbon::parse($item->check_in_time);
                        $checkOutTime = \Carbon\Carbon::parse($item->check_out_time);

                        // Menghitung durasi kerja dalam menit
                        $workDurationInMinutes = $checkOutTime->diffInMinutes($checkInTime);

                        // Mengonversi menit menjadi jam dan menit
                        $hours = floor($workDurationInMinutes / 60);
                        $minutes = $workDurationInMinutes % 60;

                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->work_date }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->check_in_time)->translatedFormat('H:i') }}</td>
                        <td>
                            @if ($item->check_in_photo == null)
                                <p>belum foto</p>
                            @else
                                <img src="{{ asset('storage/kehadiran/' . $item->check_in_photo) }}" alt="Foto Datang"
                                    width="50">
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->check_out_time)->translatedFormat('H:i') }}</td>
                        <td>
                            @if ($item->check_out_photo == null)
                                <p>belum foto</p>
                            @else
                                <img src="{{ asset('storage/kehadiran/' . $item->check_out_photo) }}" alt="Foto Pulang"
                                    width="50">
                            @endif
                        </td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $hours }} Jam {{ $minutes }} Menit</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tambahkan bagian tanda tangan di sini -->
        <div class="container-fluid d-flex justify-content-end mt-5">

            <p>Surabaya, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="signature-container ">
            <div class="text-center">
                <p>Mario Mariyadi</p>
                <br><br><br>
                <p>(...............................)</p>
                <p class="font-italic">Direktur</p>
            </div>
            <div class="text-center">
                <p>Iqbal</p>
                <br><br><br>
                <p>(...............................)</p>
                <p class="font-italic">Admin</p>
            </div>
        </div>
    </div>

    <script>
        // Print halaman ini saat halaman dimuat
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
