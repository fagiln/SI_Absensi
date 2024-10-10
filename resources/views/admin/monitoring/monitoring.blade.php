@extends('admin.template.app')
@section('content')
@section('title', 'Monitoring')

<form action="" method="" class="mb-3">
    <div class="row">
        <div class="col-md-4">

            <div class="form-group">
                <label for="filter_date">Filter berdasarkan Tanggal:</label>
                <div class="d-flex">
                    <input type="date" id="filter_date" name="filter_date" class="form-control filter" value="">

                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalKaryawanCuti">
                        Karyawan Cuti
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="modalKaryawanCuti" tabindex="-1" aria-labelledby="modalKaryawanCutiLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKaryawanCutiLabel">Daftar Karyawan Cuti</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Tempat untuk menampilkan daftar karyawan yang sedang cuti -->
                <ul id="listKaryawanCuti">
                    <!-- Daftar karyawan cuti akan ditambahkan di sini melalui JavaScript -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    {{-- Tampilkan hanya data yang difilter di tabel --}}
    {!! $dataTable->table(['class' => 'table table-bordered table-striped my-2']) !!}
</div>

{{-- Gunakan semua data kehadirans untuk modal --}}
@foreach ($kehadirans as $kehadiran)
    <div class="modal fade" id="modalMapsIn_{{ $kehadiran->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lokasi Datang: {{ $kehadiran->user->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mapIn{{ $kehadiran->id }}" style="height: 400px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@foreach ($kehadirans as $kehadiran)
    <div class="modal fade" id="modalMapsOut_{{ $kehadiran->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lokasi Pulang: {{ $kehadiran->user->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mapOut{{ $kehadiran->id }}" style="height: 400px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach



@push('scripts')
    {{ $dataTable->scripts() }}
    <script>
        // Load the map when the modal is shown
        $('[id^="modalMapsIn_"]').on('shown.bs.modal', function(event) {

            const button = $(event.relatedTarget); // Tombol yang memicu modal
            const latitude = button.data('latitude'); // Ambil latitude dari data atribut
            const longitude = button.data('longitude'); // Ambil longitude dari data atribut
            const modalId = $(this).attr('id'); // Ambil ID modal yang sedang dibuka

            // Inisialisasi peta
            const map = L.map(`mapIn${button.data('id')}`).setView([latitude, longitude], 15);

            // Tambahkan layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Tambahkan marker di lokasi
            L.marker([latitude, longitude]).addTo(map)
                .bindPopup(`Lokasi: ${button.data('name')}`)
                .openPopup();
        });
        $('[id^="modalMapsOut_"]').on('shown.bs.modal', function(event) {
            const button = $(event.relatedTarget); // Tombol yang memicu modal
            const latitude = button.data('latitude'); // Ambil latitude dari data atribut
            const longitude = button.data('longitude'); // Ambil longitude dari data atribut
            const modalId = $(this).attr('id'); // Ambil ID modal yang sedang dibuka

            // Inisialisasi peta
            const map = L.map(`mapOut${button.data('id')}`).setView([latitude, longitude], 15);

            // Tambahkan layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Tambahkan marker di lokasi
            L.marker([latitude, longitude]).addTo(map)
                .bindPopup(`Lokasi: ${button.data('latitude')}`)
                .openPopup();
        });
        $('[id^="modalMapsOut_"]').on('hidden.bs.modal', function() {
            const modalId = $(this).attr('id');
            $(`#map${modalId.split('_')[1]}`).empty(); // Hapus konten peta saat modal ditutup
        });

        $(document).ready(function() {
            $('#filter_date').on('change', function() {
                reloadDataTable();
            })
        })

        function reloadDataTable() {
            let date = $('#filter_date').val();
            let url = "{{ route('admin.monitoring.index') }}";

            window.LaravelDataTables['monitoring-table'].ajax.url(`${url}?work_date=${date}`).load();
        }
        document.addEventListener('DOMContentLoaded', function() {
            const filterDateInput = document.getElementById('filter_date');
            const today = new Date().toISOString().split('T')[
                0]; // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
            filterDateInput.value = today;
        });

        $(document).ready(function() {
            $('#modalKaryawanCuti').on('show.bs.modal', function() {
                // Panggil endpoint untuk mendapatkan data karyawan yang sedang cuti
                $.ajax({
                    url: "{{ route('admin.karyawan.cuti') }}",
                    type: 'GET',
                    success: function(response) {
                        const listKaryawanCuti = $('#listKaryawanCuti');
                        listKaryawanCuti.empty();

                        if (response.length > 0) {
                            response.forEach(item => {
                                const listItem =
                                    `<li>${item.user.name} (NIK: ${item.user.nik}) -  ${item.keterangan} hingga ${item.end_date}</li>`;
                                listKaryawanCuti.append(listItem);
                            });
                        } else {
                            listKaryawanCuti.append(
                                '<li>Tidak ada karyawan yang sedang cuti.</li>');
                        }
                    },
                    error: function() {
                        $('#listKaryawanCuti').html(
                            '<li>Gagal memuat data karyawan cuti.</li>');
                    }
                });
            });
        });
    </script>
@endpush
@endsection
