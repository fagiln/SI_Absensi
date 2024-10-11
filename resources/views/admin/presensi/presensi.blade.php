@extends('admin.template.app')
@section('title', 'Presensi Karyawan')
@section('content')
    <div class="container">

        <form action="{{ route('admin.presensi.export') }}" method="GET">
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
                    <label for="year">Tahun:</label>
                    <select name="year" id="year" class="form-control">
                        @foreach (range(now()->year - 5, now()->year) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="user_id">Nama Karyawan:</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">Pilih Karyawan</option>
                        @foreach ($karyawan as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" id="exportButton" class="btn btn-success"><i class="fas fa-download"></i> Cetak ke Excel</button>
                <a href="#" id="printButton" class="btn btn-secondary"><i class="fas fa-print"></i>           Print Laporan</a>
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
                const userId = document.getElementById('user_id').value;

                // Redirect ke halaman print dengan parameter
                if (userId) {
                    const url = `{{ route('admin.presensi.print') }}?user_id=${userId}&month=${month}&year=${year}`;
                    window.location.href = url;
                } else {
                    alert('Silakan pilih karyawan terlebih dahulu.');
                }
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
