@extends('user.layouts.app')

@section('content')
<head>
    <title>Home Page</title>
<style>

        .container {
            background-color: #ffff;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .location {
            background-color: #f8d7da;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .location-icon {
            margin-right: 5px;
            font-size: 18px;
        }

        .greeting {
            font-size: 28px;
            font-weight: bold;
            margin-top: 20px;
        }

        .greeting span {
            color: red;
        }

        .button-container {
            margin-top: 30px;
            display: flex;
            justify-content: space-around;
        }

        .button-container .btn {
            display: flex;
            align-items: center;
            font-size: 18px;
            padding: 12px 30px;
            border-radius: 8px;
        }

        .btn-masuk {
            border: 2px solid red;
            background-color: white;
            color: #CC0200;
            width:40%;
            justify-content: center;
            
        }

        .btn-masuk:hover {
            background-color: #CC0200;
            color: white;
        }

        .btn-pulang {
            background-color: #CC0200;
            color: white;
            width: 40%;
            justify-content: center;
        }

        .btn-pulang:hover {
            border: 2px solid red;
            background-color: white;
            color:  #CC0200;
        }

        .icon {
            margin-right: 10px;
        }

        .icon:hover{
            color:whte;
        }

        .work-time-card {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .work-time-card .icon {
            color: red;
            font-size: 24px;
            
        }
        .work-time-card .info {
            flex: 1;
            margin-left: 15px;
        }
        .work-time-card .info h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .work-time-card .details {
            display: flex;
            justify-content: space-between;
        }
        .work-time-card .details div {
            text-align: center;
        }
        .work-time-card .details div span {
            display: block;
            font-size: 14px;
            color: #666;
        }
        .work-time-card .details div p {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        /* Item untuk content untuk box preview */

        .attendance-box {
            display: flex;
            justify-content: space-around;
            margin: 20px;
    
        }
        .attendance-item {
            text-align: center;
            width: 120px;
        }
        .attendance-item img {
            width: 50px;
            height: 50px;
        }

        /*  */

        .badge-number {
            position: relative;
            top: -20px;
            right: -2px;
            background-color: #F7B731;
            border-radius: 50%;
            color: black;
            padding: 5px;
            font-size: 0.8em;
        }

        .terbaru-container {
            width: 100%;
            margin: 20px auto;
            padding: 10px;
            border-style: solid;
            border-color: #E4E5EB;

        }
        .terbaru {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .terbaru p {
            margin: 0;
            font-size: 16px;
        }
        .terbaru small {
            color: #888;
        }
        .date {
            font-size: 18px;
            font-weight: bold;
        }

        .time {
            font-size: 16px;
            color: #888;
        }

</style>
</head>
<body>
    <div class="container">
      
        <!-- Location Display -->
        <div class="location">
            <span class="location-icon">📍</span>
            <span>Pt Multi Power Abadi, Surabaya</span>
        </div>

        <!-- Greeting Section -->
        <div class="greeting">
            Selamat {{ $greeting }}, <br><span>{{ $user->name }}</span>
        </div>
        <!-- Action Buttons -->

        <div class="button-container">

            <button class="btn btn-masuk" onclick="window.location='{{ route('absen_masuk') }}'"
                @if($absenToday) disabled @endif>
                <span class="fas fa-camera" style="margin-right: 10px"></span>
                @if($absenToday)
                    Sudah absen
                @else
                    MASUK
                @endif
            </button>
            <button class="btn btn-pulang" onclick="window.location='{{ route('absen_pulang') }}'"
                @if($absenpulangToday) disabled @endif>
                <span class="fas fa-camera" style="margin-right: 10px"></span>
                @if($absenpulangToday)
                    Sudah absen
                @else
                    Pulang
                @endif
        </button>
    </div>        
    {{-- {{ dd(session()->all()) }} --}}
    

        <!-- Bootstrap Modal pop-up konfimasi -->
        <div class="d-flex justify-content-center">
            <div class="modal fade" id="absenModal" tabindex="-1" aria-labelledby="absenModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="justify-content: center">
                    <div class="modal-content" style="width: 60%">
                        <div class="modal-body text-center">
                            <!-- Gambar emoji absen berhasil -->
                            <img src="{{ asset('img/Emoji_galau.svg') }}" alt="Absen Berhasil" width="150" height="100"> 

                            <!-- Judul modal -->
                            <h4 class="mt-3">Oops!</h4>

                            <!-- Pesan -->
                            <p class="text-muted">Sepertinya Anda belum absen masuk. Silakan lakukan absen pagi terlebih dahulu.</p>

                            <!-- Tombol untuk menutup modal -->
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Siap</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('absenModal'));
                modal.show();
            });
        </script>
        @endif
        
        <br>
        <div class="work-time-card">
            <div class="info">
                <h3>
                    <i class="fas fa-city" style="color: crimson"></i>
                    Total Jam Kerja
                </h3>
                <div class="details">
                    <div>
                        <span>Hari ini</span>
                        <p style="font-size: 15px">{{ $jamKerjaFormatted }} </p>
                    </div>
                    <div>
                        <span>Total semua jam kerja</span>
                        <p>160 Jam</p>
                    </div>
                </div>
            </div>
        </div>
        
        <br>
        <h5>Rekap Presensi</h5>
        <div class="attendance-box">
            <div class="attendance-item">
                <img src="{{ asset('img/hadir.svg') }}" alt="Hadir">
                <span class="badge-number">10</span>
                <p>Hadir</p> 
            </div>
            <div class="attendance-item">
                <img src="{{ asset('img/izin.svg') }}" alt="Izin">
                <span class="badge-number">20</span>
                <p>Izin</p>
            </div>
            <div class="attendance-item">
                <img src="{{ asset('img/sakit.svg') }}" alt="Sakit">
                <span class="badge-number">124</span>
                <p>Sakit</p>
            </div>
            <div class="attendance-item">
                <img src="{{ asset('img/terlambat.svg') }}" alt="Terlambat">
                <span class="badge-number">1</span>
                <p>Terlambat</p>
            </div>
        </div>

        <h5>Terbaru</h5>
        <div class="terbaru-container">
            <div class="date">01 Oktober 2024</div>
            <div class="time">Masuk & Pulang</div>
            <div class="time">8 Jam</div>
            <div class="time">08:00 - 17:00</div>
        </div>
        <div class="terbaru-container">
            <div class="date">01 Oktober 2024</div>
            <div class="time">Masuk & Pulang</div>
            <div class="time">8 Jam</div>
            <div class="time">08:00 - 17:00</div>
        </div>
        <div class="terbaru-container">
            <div class="date">01 Oktober 2024</div>
            <div class="time">Masuk & Pulang</div>
            <div class="time">8 Jam</div>
            <div class="time">08:00 - 17:00</div>
        </div>
        <div class="terbaru-container">
            <div class="date">01 Oktober 2024</div>
            <div class="time">Masuk & Pulang</div>
            <div class="time">8 Jam</div>
            <div class="time">08:00 - 17:00</div>
        </div>
    </div>
</body>
@endsection
