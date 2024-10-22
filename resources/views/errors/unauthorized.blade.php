<!-- resources/views/errors/unauthorized.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>403 - Unauthorized Access</title>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">

</head>

<body>
    <div class="container text-center">
        <h1>403 - Unauthorized Access</h1>
        <p>You do not have permission to access this page.</p>
        <a href="{{ url('/') }}" class="btn btn-custom">Go to Home</a>
    </div>
</body>

</html>
