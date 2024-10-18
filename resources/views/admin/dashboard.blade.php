@extends('admin.template.app')
@section('title', 'Dashboard')
@section('content')


    <!-- Main content -->
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-sm-6 col-12">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $karyawanHadirCount }}</h3>

                        <p class="font-weight-bold">Karyawan Hadir Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ route('admin.monitoring.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <!-- ./col -->
            <div class="col-sm-6 col-12">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $karyawanIzinCount }}</h3>

                        <p class="font-weight-bold">Karyawan Izin Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-document"></i>
                    </div>
                    <a href="{{ route('admin.perizinan.index') }}" class="small-box-footer" data-toggle="modal"
                        data-target="#modalKaryawanIzin">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <div class="col-sm-6 col-12">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $karyawanSakitCount }}</h3>

                        <p class="font-weight-bold">Karyawan Sakit Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-sad"></i>
                    </div>
                    <a href="{{ route('admin.perizinan.index') }}" class="small-box-footer" data-toggle="modal"
                        data-target="#modalKaryawanSakit">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <div class="col-sm-6 col-12">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $karyawanTelatCount }}</h3>

                        <p class="font-weight-bold">Karyawan Telat Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-clock"></i>
                    </div>
                    <a href="{{ route('admin.monitoring.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <!-- ./col -->
        </div>

    </div><!-- /.container-fluid -->
    <!-- /.content -->

    {{-- Modal --}}
    <div class="modal fade" id="modalKaryawanSakit" tabindex="-1" aria-labelledby="modalKaryawanSakitLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKaryawanSakitLabel">Daftar Karyawan Sakit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tempat untuk menampilkan daftar karyawan yang sedang Sakit -->
                    <ul id="listKaryawanSakit">
                        <!-- Daftar karyawan Sakit akan ditambahkan di sini melalui JavaScript -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalKaryawanIzin" tabindex="-1" aria-labelledby="modalKaryawanIzinLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKaryawanIzinLabel">Daftar Karyawan Izin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tempat untuk menampilkan daftar karyawan yang sedang Izin -->
                    <ul id="listKaryawanIzin">
                        <!-- Daftar karyawan Sakit akan ditambahkan di sini melalui JavaScript -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#modalKaryawanIzin').on('show.bs.modal', function() {
                    // Ambil nilai filter_date dari input
                    let filterDate = new Date().toISOString().split('T')[
                        0];
                    console.log('Filter Date:', filterDate);

                    // Panggil endpoint untuk mendapatkan data karyawan yang sedang cuti, dengan filter_date sebagai parameter
                    $.ajax({
                        url: "{{ route('admin.karyawan.izin') }}",
                        type: 'GET',
                        data: {
                            filter_date: filterDate
                        },
                        success: function(response) {
                            const listKaryawanIzin = $('#listKaryawanIzin');
                            listKaryawanIzin.empty();

                            if (response.length > 0) {
                                response.forEach(item => {
                                    const listItem =
                                        `<li>${item.user.name} (NIK: ${item.user.nik}) -  ${item.start_date} hingga ${item.end_date} : ${item.reason}</li>`;
                                    listKaryawanIzin.append(listItem);
                                });
                            } else {
                                listKaryawanIzin.append(
                                    '<li>Tidak ada karyawan yang sedang izin pada hari ini.</li>'
                                );
                            }
                        },
                        error: function() {
                            $('#listKaryawanIzin').html(
                                '<li>Gagal memuat data karyawan izin.</li>'
                            );
                        }
                    });
                });
            });
            $(document).ready(function() {
                $('#modalKaryawanSakit').on('show.bs.modal', function() {
                    // Ambil nilai filter_date dari input
                    let filterDate = new Date().toISOString().split('T')[
                        0];
                    console.log('Filter Date:', filterDate);

                    // Panggil endpoint untuk mendapatkan data karyawan yang sedang cuti, dengan filter_date sebagai parameter
                    $.ajax({
                        url: "{{ route('admin.karyawan.sakit') }}",
                        type: 'GET',
                        data: {
                            filter_date: filterDate
                        },
                        success: function(response) {
                            const listKaryawanSakit = $('#listKaryawanSakit');
                            listKaryawanSakit.empty();

                            if (response.length > 0) {
                                response.forEach(item => {
                                    const listItem =
                                        `<li>${item.user.name} (NIK: ${item.user.nik}) -  ${item.start_date} hingga ${item.end_date} : ${item.reason}</li>`;
                                    listKaryawanSakit.append(listItem);
                                });
                            } else {
                                listKaryawanSakit.append(
                                    '<li>Tidak ada karyawan yang sedang sakit pada hari ini.</li>'
                                );
                            }
                        },
                        error: function() {
                            $('#listKaryawanSakit').html(
                                '<li>Gagal memuat data karyawan sakit.</li>'
                            );
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
