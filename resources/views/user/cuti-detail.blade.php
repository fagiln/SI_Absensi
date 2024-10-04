@extends('user.layouts.app')

<!DOCTYPE html>
<html lang="en">
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
                <p style="font-size: 15px; font-weight: bold; margin: 0;">Tanggal Pengajuan</p>
                <p style="font-style: italic; margin: 0; margin-left: 5px;">10/20/2024</p>
            </div>
            <div style="display: flex; margin-bottom: 5px;">
                <p style="font-size: 15px; font-weight: bold; margin: 0;">Periode Pengajuan</p>
                <p style="font-style: italic; margin: 0; margin-left: 5px;">10/11/2024 sd 11/12/2024</p>
            </div>
            <div style="display: flex; margin-bottom: 20px;">
                <p style="font-size: 15px; font-weight: bold; margin: 0;">Status Pengajuan</p>
                <p style="font-style: italic; margin: 0; margin-left: 5px; color: crimson;">Ditolak</p>
            </div>
        </div>              
        <div style="margin-top: 20px"></div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal Cuti</th>
                    <th>Periode Cuti</th>
                    <th>Alasan Ditolak</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>10/11/2024</td>
                    <td>10/11/2024 sd 12/11/2024</td>
                    <td>Kerja dulu ada job nih</td>
                </tr>
            </tbody>
        </table>
        <form action="{{ route('cuti') }}" method="GET" style="display: flex; justify-content: flex-end; margin-top: 15px; padding-right: 15px;">
            <button type="submit" style="padding: 10px 20px; background-color: orange; color: white; border: none; border-radius: 5px;">Tutup</button>
        </form>
        
    </div>
</body>
</html>