@extends('user.layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.css') }}">
    <style>
        /* Custom CSS untuk notifikasi */
        
        .container {
            max-width: 100%;
            padding-left: 10px;
            padding-right: 10px;
            /* background-color: #f4f4f4; */
        }
        
        .notif-container {
            /* border: 2px solid #4CAF50; */
            border-radius: 10px;
            padding: 15px; 
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            max-width: 500px;
            margin: 15px auto;
            background-color: #efffdb;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .notif-left {
            display: flex;
            flex-direction: column;
        }

        .notif-left .message-berhasil {
            font-weight: bold;
            font-size: 18px;
            color: #4CAF50;
        }

        .notif-left .message-gagal {
            font-weight: bold;
            font-size: 18px;
            color: rgb(204, 37, 37);
        }

        .notif-left .date {
            font-size: 14px;
            color: rgb(98, 98, 98);
        }

        .notif-right {
            font-size: 14px;
            color: #9e9e9e;
            align-self: flex-end;
        }
        
    </style>
</head>
<body>

<div class="container">
    <div class="notif-container">
        <!-- Bagian kiri -->
        <div class="notif-left">
            <div class="message-berhasil">Berhasil Absen</div>
            <div class="date">03 Oktober 2024</div>
        </div>
        
        <!-- Bagian kanan bawah -->
        <div class="notif-right">
            Jam Absen: 09:45
        </div>
    </div>
</div>

<div class="container">
    <div class="notif-container">
        <!-- Bagian kiri -->
        <div class="notif-left">
            <div class="message-gagal">Gagal Absen</div>
            <div class="date">03 Oktober 2024</div>
        </div>
        
        <!-- Bagian kanan bawah -->
        <div class="notif-right">
            Jam Absen: 09:45
        </div>
    </div>
</div>

<!-- Bootstrap JS dan jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
