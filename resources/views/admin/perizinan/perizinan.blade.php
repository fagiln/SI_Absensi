@extends('admin.template.app')
@section('content')
@section('title', 'Perizinan Karyawan')
@if (session('status'))
    <div class="my-3">
        <div id="success-alert" class="alert alert-success d-flex justify-content-between fade show" role="alert">
            {{ session('status') }}

        </div>
    </div>
@endif

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
                                <label for="pending">
                                    <input type="radio" id="pending" name="status" value="pending"
                                        {{ $item->status == 'pending' ? 'checked' : '' }}>
                                    Pending
                                </label>
                            </div>
                            <div>
                                <label for="diterima">
                                    <input type="radio" id="diterima" name="status" value="diterima"
                                        {{ $item->status == 'diterima' ? 'checked' : '' }}>
                                    Diterima
                                </label>
                            </div>
                            <div>
                                <label for="ditolak">
                                    <input type="radio" id="ditolak" name="status" value="ditolak"
                                        {{ $item->status == 'ditolak' ? 'checked' : '' }}>
                                    Ditolak
                                </label>
                            </div>
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
    </script>
@endpush
@endsection
