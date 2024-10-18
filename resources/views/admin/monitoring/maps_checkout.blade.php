<div class="d-flex justify-content-center align-items-center gap-2">
    <input type="hidden" name="id" value="{{ $kehadiran->id }}">
    @php

    @endphp
    {{-- Tombol Edit --}}
    <a href="#" class="btn btn-info mr-3" data-id="{{ $kehadiran->id }}" data-toggle="modal"
        data-target="#modalMapsOut_{{ $kehadiran->id }}" data-latitude="{{ $kehadiran->check_out_latitude }}"
        data-name="{{ $kehadiran->user->name }}" data-longitude="{{ $kehadiran->check_out_longitude }}">
        <i class="fas fa-map fs-2"></i>
    </a>
</div>
