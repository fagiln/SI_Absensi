<div class="d-flex justify-content-center align-items-center gap-2">
    <input type="hidden" name="id" value="{{ $perizinan->id }}">

    {{-- Tombol Edit --}}
    <a href="#" class="btn btn-info mr-3" data-id="{{ $perizinan->id }}"
        data-toggle="modal" data-target="#modalPerizinan_{{ $perizinan->id }}" data-name="{{ $perizinan->user->name }}"> <i
            class="fas fa-edit fs-2"></i>
    </a>
</div>
