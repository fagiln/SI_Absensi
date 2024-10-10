<div class="d-flex justify-content-center align-items-center gap-2">
    <input type="hidden" name="id" value="{{ $departemen->id }}">

    {{-- Tombol Edit --}}
    <a href="javascript:void(0)" class="btn btn-warning mr-3" data-id="{{ $departemen->id }}" data-toggle="modal"
        data-target="#modalEdit">
        <i class="fas fa-pen fs-2"></i>
    </a>
    {{-- @can($globalModule['delete']) --}}
    <button data-url="{{ route('admin.departemen.delete', $departemen->id) }}" data-action="delete"
        data-table-id="departemen-table" data-name="{{ $departemen->nama_departemen }}" class="btn btn-danger">
        <i class="fas fa-trash fs-2"></i></button>
    {{-- @endcan --}}
</div>
