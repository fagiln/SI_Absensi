@extends('admin.template.app')
@section('content')
@section('title', 'Perizinan Karyawan')


<div class="row">
    <div class="col-md-3">

        <div class="form-group">
            <label for="filter_date">Filter tanggal pembuatan :</label>
            <div class="d-flex">
                <input type="date" id="filter_date" name="filter_date" class="form-control filter" value="">
            </div>
        </div>
    </div>
    <div class="col-md-3">

        <div class="form-group">
            <label for="filter_start_date">Filter awal cuti :</label>
            <div class="d-flex">
                <input type="date" id="filter_start_date" name="filter_start_date" class="form-control filter"
                    value="">
            </div>
        </div>
    </div>
    <div class="col-md-3">

        <div class="form-group">
            <label for="filter_status">Filter status :</label>
            <div class="d-flex">
                <select name="filter_status" id="filter_status" class="form-control">
                    <option value="">Pilih Status</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="pending">Pending</option>
                    <option value="diterima">Diterima</option>

                </select>
            </div>
        </div>
    </div>

</div>

<div class="table-responsive">
    <div class="table-wrapper">

        {!! $dataTable->table(['class' => 'table table-bordered table-striped my-2']) !!}
    </div>
</div>

@foreach ($perizinan as $item)
    <div class="modal fade" id="modalPerizinan_{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Perizinan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.perizinan.status', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Status Persetujuan:</label>
                            <div>
                                <label for="pending_{{ $item->id }}">
                                    <input type="radio" id="pending_{{ $item->id }}" name="status"
                                        value="pending" {{ $item->status == 'pending' ? 'checked' : '' }}
                                        onclick="toggleReason({{ $item->id }})">
                                    Pending
                                </label>
                            </div>
                            <div>
                                <label for="diterima_{{ $item->id }}">
                                    <input type="radio" id="diterima_{{ $item->id }}" name="status"
                                        value="diterima" {{ $item->status == 'diterima' ? 'checked' : '' }}
                                        onclick="toggleReason({{ $item->id }})">
                                    Diterima
                                </label>
                            </div>
                            <div>
                                <label for="ditolak_{{ $item->id }}">
                                    <input type="radio" id="ditolak_{{ $item->id }}" name="status"
                                        value="ditolak" {{ $item->status == 'ditolak' ? 'checked' : '' }}
                                        onclick="toggleReason({{ $item->id }})">
                                    Ditolak
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="reason-container-{{ $item->id }}" style="display: none">
                            <label for="reason_{{ $item->id }}">Alasan ditolak</label>
                            <input type="text" id="reason_{{ $item->id }}" name="reason" class="form-control">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-custom">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalView_{{ $item->id }}" tabindex="-1" role="dialog">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Izin {{ $item->user->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        // Cek apakah file PNG atau PDF ada
                        $pngPath =
                            'storage/perizinan/' .
                            $item->user_id .
                            '_' .
                            \Carbon\Carbon::parse($item->created_at)->format('YmdHi') .
                            '.png';
                        $jpgPath =
                            'storage/perizinan/' .
                            $item->user_id .
                            '_' .
                            \Carbon\Carbon::parse($item->created_at)->format('YmdHi') .
                            '.jpg';
                        $jpegPath =
                            'storage/perizinan/' .
                            $item->user_id .
                            '_' .
                            \Carbon\Carbon::parse($item->created_at)->format('YmdHi') .
                            '.jpeg';
                        $pdfPath =
                            'storage/perizinan/' .
                            $item->user_id .
                            '_' .
                            \Carbon\Carbon::parse($item->created_at)->format('YmdHi') .
                            '.pdf';
                        $hasPng = file_exists(public_path($pngPath));
                        $hasJpg = file_exists(public_path($jpgPath));
                        $hasJpeg = file_exists(public_path($jpegPath));
                        $hasPdf = file_exists(public_path($pdfPath));
                    @endphp

                    @if ($hasPng)
                        <img id="buktiImage" src="{{ asset($pngPath) }}" alt="Bukti Izin" class="img-fluid">
                    @elseif($hasJpg)
                        <img id="buktiImage" src="{{ asset($jpgPath) }}" alt="Bukti Izin" class="img-fluid">
                    @elseif($hasJpeg)
                        <img id="buktiImage" src="{{ asset($jpegPath) }}" alt="Bukti Izin" class="img-fluid">
                    @elseif ($hasPdf)
                        <iframe id="buktiPdf" style="width:100%; height:400px;"
                            src="{{ asset($pdfPath) }}"></iframe>
                    @else
                        <p>Tidak ada bukti</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach


@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(function() {
                    var bootstrapAlert = new bootstrap.Alert(alert);
                    bootstrapAlert.close();
                }, 3000); // waktu dalam milidetik (5000 ms = 5 detik)
            }
        });

        $(document).ready(function() {
            $('#filter_date').on('change', function() {
                reloadDataTable();
            })
        })
        $(document).ready(function() {
            $('#filter_start_date').on('change', function() {
                console.log('test');
                reloadDataTable();
            })
        })
        $(document).ready(function() {
            $('#filter_status').on('change', function() {
                reloadDataTable();
            })
        })

        function reloadDataTable() {
            let date = $('#filter_date').val();
            let start_date = $('#filter_start_date').val();
            let status = $('#filter_status').val();
            let url = "{{ route('admin.perizinan.index') }}";
            console.log(status);

            window.LaravelDataTables['perizinan-table'].ajax.url(
                    `${url}?created_at=${date}&start_date=${start_date}&status=${status}`)
                .load();
        }
        document.addEventListener('DOMContentLoaded', function() {
            const filterDateInput = document.getElementById('filter_date');
            const today = new Date().toISOString().split('T')[
                0]; // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
            filterDateInput.value = today;
        });


        function toggleReason(id) {
            const reasonContainer = document.getElementById(`reason-container-${id}`);
            const isRejected = document.getElementById(`ditolak_${id}`).checked;
            reasonContainer.style.display = isRejected ? 'block' : 'none';
        }

        // Pastikan form menampilkan alasan jika status awalnya "ditolak"
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($perizinan as $item)
                toggleReason({{ $item->id }});
            @endforeach
        });

        $(document).on('click', 'a[data-toggle="modal"]', function() {
            var perizinanId = $(this).data('id'); // Ambil ID dari tombol edit
            var url = '/admin/perizinan/edit/' + perizinanId; // URL untuk ambil data
            // var updateUrl = '/admin/karyawan/' + userId;
            // Request AJAX untuk mendapatkan data karyawan berdasarkan ID
            console.log(perizinanId);
            $.get(url, function(data) {
                // Isi field modal dengan data yang didapat dari server
                $('#reason_' + perizinanId).val(data.keterangan_ditolak);


                // $('#formEditKaryawan').attr('action', updateUrl);
            });
        });
        // $(document).on('click', '#btn_view_modal', function() {
        //     const fileUrl = 'public/perizinan/pdf_izin_3.pdf';
        //     const fileType = 'pdf';
        //     var perizinanId = $(this).data('id');

        //     if (fileType === 'pdf') {
        //         $('#buktiImage').addClass('d-none');
        //         $('#buktiPdf').removeClass('d-none').attr('src', fileUrl);
        //     } else {
        //         $('#buktiPdf').addClass('d-none');
        //         $('#buktiImage').removeClass('d-none').attr('src', fileUrl);
        //     }

        //     $('#modalView_' + perizinanId).modal('show');

        // });
    </script>
@endpush
@endsection
