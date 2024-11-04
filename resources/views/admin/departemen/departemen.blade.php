@extends('admin.template.app')
@section('title', 'Departemen')
@section('content')
  
    <a href="" class="btn btn-custom mb-3" data-toggle="modal" data-target="#modalAdd">
        <i class="fas fa-plus fs-2 mr-1"></i>
        Tambah Data</a>
    <div class="table-responsive">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped my-2']) !!}
    </div>
    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Departemen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.departemen.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label class="" for="add_nama">Nama Depertemen</label>
                        <input type="text" class="form-control" name="add_nama" id="add_nama"
                            placeholder="Masukkan Nama Departemen" value="{{ old('add_nama') }}">
                        @error('add_nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <label class="mt-3" for="add_kode">Kode Depertemen</label>
                        <input type="text" class="form-control" name="add_kode" id="add_kode"
                            placeholder="Masukkan Kode Departemen" value="{{ old('add_kode') }}">
                        @error('add_kode')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-custom">Tambah</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Departemen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditDepartemen" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">

                        <div class="form-group">
                            <label for="edit_nama">Nama Departemen</label>
                            <input type="text" class="form-control" name="edit_nama" id="edit_nama"
                                value="{{ old('edit_nama') }} " placeholder="Masukkan nama">
                            @error('edit_nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="edit_kode">Kode Departemen</label>
                            <input type="text" class="form-control" name="edit_kode" id="edit_kode"
                                value="{{ old('edit_kode') }}" p placeholder="Masukkan kode">
                            @error('edit_kode')
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


    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            $(document).on('click', 'button[data-action="delete"]', function() {
                var url = $(this).data('url');
                var tableId = $(this).data('table-id');
                var name = $(this).data('name');

                if (confirm('Apa kamu yakin ingin menghapus departemen ' + name + '?')) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(result) {
                            console.log(result); // Cek respons dari server
                            $('#' + tableId).DataTable().ajax.reload(); // Reload DataTable
                            alert('Departemen ' + name + ' berhasil dihapus');
                        },
                        error: function(xhr) {
                            alert('Error deleting user');
                        }
                    });
                }
            });
            
          

            $(document).ready(function() {
                @if ($errors->has('add_nama') || $errors->has('add_kode'))
                    ;
                    $('#modalAdd').modal('show');
            
                @endif
            })

            

            $(document).on('click', 'a[data-toggle="modal"]', function() {
                var departemenId = $(this).data('id'); // Ambil ID dari tombol edit
                var url = '/admin/departemen/' + departemenId + '/edit'; // URL untuk ambil data
                var updateUrl = '/admin/departemen/' + departemenId;
                // Request AJAX untuk mendapatkan data karyawan berdasarkan ID
                $.get(url, function(data) {
                    // Isi field modal dengan data yang didapat dari server
                    $('#editId').val(departemenId);
                    $('#edit_nama').val(data.nama_departemen);
                    $('#edit_kode').val(data.kode_departemen);

                    $('#formEditDepartemen').attr('action', updateUrl);
                });
            });
        </script>
    @endpush
@endsection
