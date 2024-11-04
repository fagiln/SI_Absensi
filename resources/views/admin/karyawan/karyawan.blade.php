@extends('admin.template.app')
@section('title', 'Karyawan')
@section('content')


    <a href="" class="btn btn-custom mb-3" data-toggle="modal" data-target="#modal">
        <i class="fas fa-plus fs-2 mr-1"></i>
        Tambah Data
    </a>
    <!-- Modal Add -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <label for="username">Username</label>
                            <input class=" form-control" type="text" id="username" name="username"
                                placeholder="Masukkan Username" value="{{ old('username') }}">
                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 d-flex flex-column">
                            <label for="departement_id">Departemen</label>
                            <select class=" form-control" name="department_id" id="department_id"
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
                            <label for="jabatan">Jabatan</label>
                            <input class=" form-control" type="text" id="jabatan" name="jabatan"
                                placeholder="Masukkan Jabatan" value="{{ old('jabatam') }}">
                            @error('jabatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 d-flex flex-column">
                            <label for="password">Password</label>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditKaryawan" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">

                        <div class="form-group">
                            <label for="editNik">NIK</label>
                            <input type="text" class="form-control" name="edit_nik" id="editNik"
                                value="{{ old('edit_nik') }} " placeholder="Masukkan NIK">
                            @error('edit_nik')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="editJabatan">Jabatan</label>
                            <input type="text" class="form-control" name="edit_jabatan" id="editJabatan"
                                value="{{ old('edit_jabatan') }}" p placeholder="Masukkan Jabatan">
                            @error('edit_jabatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="form-group">
                            <label for="editDepartemen">Departemen</label>
                            <select name="department_id" class="form-control" id="editDepartemen">
                                <!-- Options dinamis dari backend -->

                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="editPassword">Password</label>
                            <input type="password" class="form-control" name="edit_password" id="editPassword"
                                placeholder="Masukkan Password (kosongkan jika tidak perlu)">
                            @error('edit_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-custom">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped my-2']) !!}
    </div>


    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            $(document).ready(function() {

                // Check if there are validation errors and show modal
                @if ($errors->has('nik') || $errors->has('username') || $errors->has('department_id') || $errors->has('password'))
                    ;
                    $('#modal').modal('show');
                @endif
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

            $(document).on('click', 'a[data-toggle="modal"]', function() {
                var userId = $(this).data('id'); // Ambil ID dari tombol edit
                var url = '/admin/karyawan/' + userId + '/edit'; // URL untuk ambil data
                var updateUrl = '/admin/karyawan/' + userId;
                // Request AJAX untuk mendapatkan data karyawan berdasarkan ID
                $.get(url, function(data) {
                    // Isi field modal dengan data yang didapat dari server
                    $('#editId').val(userId);
                    $('#editNik').val(data.nik);
                    $('#editJabatan').val(data.jabatan);

                    $('#formEditKaryawan').attr('action', updateUrl);
                });
            });
        </script>
    @endpush
@endsection
