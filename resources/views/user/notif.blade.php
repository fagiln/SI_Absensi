@extends('user.layouts.app')

@section('content')
<head>
    <title>Notifikasi</title>
    <style>
        /* Custom CSS untuk notifikasi */

        .terbaru-container {
            width: 100%;
            max-width: 450px;
            margin: 15px auto; /* Center the container */
            padding: 10px;
            border: 2px solid #b9b9b9; /* Border color */
            border-radius: 10px;
            background-color: #fff; /* Background color */
            /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); Optional: Add a subtle shadow */
        }
        .date {
            font-weight: bold; /* Make the date stand out */
            margin-bottom: 10px; /* Space between date and content */
        }
        .details {
            display: flex;
            justify-content: space-between; 
        }

    </style>
</head>
<body>
    @foreach($dataGabungan as $item)
    <div class="terbaru-container">
        <div class="date">{{ $item['type'] }}: {{ $item['message'] }}</div>
        <div class="details">
            <div>{{ $item['created_at']->translatedFormat('d F Y') }}</div>
            <div>{{ $item['created_at']->translatedFormat('H:i') }}</div>
        </div>
    </div>
@endforeach
</body>
@endsection
