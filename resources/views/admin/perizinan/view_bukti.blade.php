<div class="d-flex justify-content-center align-items-center gap-2">
    <input type="hidden" name="id" value="{{ $perizinan->id }}">

    {{-- Tombol Edit --}}
    <a href="#" class="btn btn-warning mr-3" data-id="{{ $perizinan->id }}" id="btn_view_modal"
        data-toggle="modal" data-target="#modalView_{{ $perizinan->id }}" data-name="{{ $perizinan->user->name }}"> <i
            class="fas fa-eye fs-2"></i>
    </a>
</div>