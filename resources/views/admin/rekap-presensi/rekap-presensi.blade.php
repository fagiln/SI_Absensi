@extends('admin.template.app')
@section('title', 'Rekap Presensi')
@section('content')
<div class="container">

    <form action="{{ route('admin.rekap-presensi.export') }}" method="GET">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label for="month">Bulan:</label>
                <select name="month" id="month" class="form-control">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="year" class="mt-3 mt-md-0">Tahun:</label>
                <select name="year" id="year" class="form-control ">
                    @foreach (range(now()->year - 5, now()->year) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between justify-content-md-start  ">
            <button type="submit" id="exportButton" class="btn btn-success mr-md-3"><i class="fas fa-download"></i> Cetak
                ke Excel</button>
            <a href="#" id="printButton" class="btn btn-secondary"><i class="fas fa-print"></i> Print Rekap</a>
        </div>
    </form>
</div>

    @push('scripts')
        <script>
            document.getElementById('printButton').addEventListener('click', function(e) {
                e.preventDefault();

                // Ambil nilai dari input month, year, dan user_id
                const month = document.getElementById('month').value;
                const year = document.getElementById('year').value;

                // Redirect ke halaman print dengan parameter
                const url = `{{ route('admin.rekap-presensi.print') }}?month=${month}&year=${year}`;
                window.location.href = url;

            });

            document.getElementById('exportButton').addEventListener('click', function(e) {

                // Ambil nilai dari input user_id
                const userId = document.getElementById('user_id').value;

                // Cek apakah user_id sudah dipilih
                if (!userId) {
                    e.preventDefault();
                    alert('Silakan pilih karyawan terlebih dahulu.');
                } else {
                    // Jika sudah dipilih, submit form untuk export
                    document.getElementById('exportForm').submit();
                }
            });
        </script>
    @endpush
@endsection
