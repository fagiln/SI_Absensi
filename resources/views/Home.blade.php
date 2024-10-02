@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h5 class="card-title">Selamat Pagi, {{ 'John Doe' }}</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <p class="text-muted">Lokasi: {{ 'PT Multi Power Abadi, Surabaya' }}</p>
                    </div>
                    <div class="btn-group mb-3">
                        <button class="btn btn-primary">MASUK</button>
                        <button class="btn btn-danger">PULANG</button>
                    </div>
                    <div class="mb-3">
                        <p><strong>Total Jam Kerja</strong></p>
                        <p>Hari ini: {{ '8' }} Jam</p>
                        <p>Total semua jam kerja: {{'160' }} Jam</p>
                    </div>
                    <div class="rekap-presensi">
                        <h6>Rekap Presensi</h6>
                        <ul class="list-group">
                            {{-- @foreach(
                                // $attendance as $day
                                ) --}}
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        {{-- <strong>{{ ['date'] }}</strong><br>
                                        <small>{{ ['hours'] }} Jam</small> --}}
                                    </div>
                                    <div>
                                        {{-- <small>{{ ['start_time'] }} --- {{ ['end_time'] }}</small> --}}
                                    </div>
                                </li>
                            {{-- @endforeach --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
