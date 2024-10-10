@extends('admin.template.app')
@section('content')
@section('title', 'Data Izin atau Sakit')
@if (session('status'))
    <div class="my-3">
        <div id="success-alert" class="alert alert-success d-flex justify-content-between fade show" role="alert">
            {{ session('status') }}

        </div>
    </div>
@endif

<div class="row">
    <div class="col-md-4">

        <div class="form-group">
            <label for="filter_date">Filter awal cuti :</label>
            <div class="d-flex">
                <input type="date" id="filter_date" name="filter_date" class="form-control filter" value="">
            </div>
        </div>
    </div>
    
</div>

<div class="table-responsive">

    {!! $dataTable->table(['class' => 'table table-bordered table-striped my-2']) !!}
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

        function reloadDataTable() {
            let date = $('#filter_date').val();
            let url = "{{ route('admin.perizinan.index') }}";

            window.LaravelDataTables['perizinan-table'].ajax.url(`${url}?created_at=${date}`).load();
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
