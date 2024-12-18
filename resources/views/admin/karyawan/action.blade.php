<div class="d-flex justify-content-center align-items-center gap-2">
    <input type="hidden" name="id" value="{{ $user->id }}">

    {{-- Tombol Edit --}}
    <a href="javascript:void(0)" class="btn btn-warning mr-3" data-id="{{ $user->id }}" data-toggle="modal"
        data-target="#modalEdit">
        <i class="fas fa-pen fs-2"></i>
    </a>
    {{-- @can($globalModule['delete']) --}}
    <button data-url="{{ route('admin.karyawan.delete', $user->id) }}" data-action="delete"
        data-table-id="karyawan-table" data-name="{{ $user->username }}" class="btn btn-danger">
        <i class="fas fa-trash fs-2"></i></button>
    {{-- @endcan --}}
</div>
