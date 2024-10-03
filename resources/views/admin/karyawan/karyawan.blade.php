@extends('admin.template.app')
@section('title', 'Karyawan')
@section('content')

    @if (session('status'))
        <div class="mt-3">
            <div id="success-alert" class="alert alert-success d-flex justify-content-between fade show" role="alert">
                {{ session('status') }}

            </div>
        </div>
    @endif
    <a href="" class="btn btn-custom mb-3" data-toggle="modal" data-target="#modal">
        Tambah Data
    </a>
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.karyawan.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class=" d-flex flex-column">
                            <label for="nik">NIK</label>
                            <input class=" form-control" type="text" id="nik" name="nik"
                                placeholder="Masukkan Nomer Induk Karyawan" value="{{ old('nik') }}">
                            @error('nik')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 d-flex flex-column">
                            <label for="nik">Username</label>
                            <input class=" form-control" type="text" id="username" name="username"
                                placeholder="Masukkan Username" value="{{ old('username') }}">
                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 d-flex flex-column">
                            <label for="nik">Departemen</label>
                            <select class=" form-control" name="departement_id" id="departement_id"
                                placeholder="Masukkan Departemen">
                                <option value="">Pilih Departemen</option>
                                @foreach ($departemen as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_departemen }}</option>
                                @endforeach
                            </select>
                            @error('departemen')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 d-flex flex-column">
                            <label for="nik">Password</label>
                            <input class=" form-control" type="password" id="password" name="password"
                                placeholder="Masukkan Password">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-custom">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        {{ $dataTable->table() }}
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            $(document).ready(function() {
                // Check if there are validation errors and show modal
                @if ($errors->any())
                    $('#modal').modal('show');
                @endif
            });
            document.addEventListener('DOMContentLoaded', function() {
                var alert = document.getElementById('success-alert');
                if (alert) {
                    setTimeout(function() {
                        var bootstrapAlert = new bootstrap.Alert(alert);
                        bootstrapAlert.close();
                    }, 3000); // waktu dalam milidetik (5000 ms = 5 detik)
                }
            });

            $(document).on('click', 'button[data-action="delete"]', function() {
                var url = $(this).data('url');
                var tableId = $(this).data('table-id');
                var name = $(this).data('name');

                if (confirm('Apa kamu yakin ingin menghapus karyawan ' + name + '?')) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(result) {
                            console.log(result); // Cek respons dari server
                            $('#' + tableId).DataTable().ajax.reload(); // Reload DataTable
                            alert('User ' + name + ' berhasil dihapus');
                        },
                        error: function(xhr) {
                            alert('Error deleting user');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
