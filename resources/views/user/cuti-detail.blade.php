@extends('user.layouts.app')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuti</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.css') }}">

    <style>
        
        .container{
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            margin: 0 auto;
        }

         /* Data table */
         table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid gray;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

    </style>

</head>
<body>
    <div class="container" style="margin-top: 20px">
        <p style="margin-top: 10px; font-size: 18px; font-weight: bold" >Detail Cuti</p>
        <div style="margin-top: 20px"></div>
        <div style="margin: 0; padding: 0;">
            <div style="display: flex; margin-bottom: 5px;">
                <p style="font-size: 15px; font-weight: bold; margin: 0;">Tanggal Pengajuan :</p>
                <p style="font-style: italic; margin: 0; margin-left: 5px;">{{ $cuti->created_at->format('d/m/Y') }}</p>
            </div>
            <div style="display: flex; margin-bottom: 5px;">
                <p style="font-size: 15px; font-weight: bold; margin: 0;">Periode Pengajuan :</p>
                <p style="font-style: italic; margin: 0; margin-left: 5px;">
                    {{ \Carbon\Carbon::parse($cuti->start_date)->format('d/m/Y') }} s.d {{ \Carbon\Carbon::parse($cuti->end_date)->format('d/m/Y') }}
                </p>
            </div>
            <div style="display: flex; margin-bottom: 20px;">
                <p style="font-size: 15px; font-weight: bold; margin: 0;">Status Pengajuan :</p>
                <p style="font-style: italic; margin: 0; margin-left: 5px; 
                    @if($cuti->status == 'ditolak') color: crimson;
                    @elseif($cuti->status == 'pending') color: orange; 
                    @elseif($cuti->status == 'diterima') color: green; @endif">
                    @if($cuti->status == 'ditolak') Ditolak 
                    @elseif($cuti->status == 'pending') Menunggu 
                    @elseif($cuti->status == 'diterima') Disetujui 
                    @endif
                </p>
            </div>
        </div>        
        <div style="margin-top: 20px"></div>
        <table>
            <thead>
                <tr>
                    <th>Jenis Cuti</th>
                    <th>Detail Cuti</th>
                    <th>Alasan Ditolak</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $cuti->reason }}</td>
                    <td>{{ $cuti->keterangan }}</td>
                    <td>{{ $cuti->keterangan_ditolak }}</td>
                </tr>
            </tbody>
        </table>
        <form action="{{ route('cuti') }}" method="GET" style="display: flex; justify-content: flex-end; margin-top: 15px; padding-right: 15px;">
            <button type="submit" style="padding: 10px 20px; background-color: orange; color: white; border: none; border-radius: 5px;">Tutup</button>
        </form>
        
    </div>
</body>
@endsection