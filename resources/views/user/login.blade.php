<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Login</title>
    <style>
        /* Tambahkan CSS yang telah dijelaskan di atas di sini */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        .login-container a {
            display: block;
            margin-top: 10px;
            text-align: center;
            color: #4CAF50;
            text-decoration: none;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        /* Media Query untuk tampilan Mobile (Potrait) */
        @media only screen and (max-width: 768px) {
            body {
                flex-direction: column;
                padding: 20px;
            }

            .login-container {
                width: 100%;
                padding: 15px;
            }

            h2 {
                font-size: 18px;
            }

            input, button {
                font-size: 16px;
            }
        }

        /* Media Query untuk tampilan Laptop/Komputer (Landscape) */
        @media only screen and (min-width: 769px) {
            body {
                flex-direction: row;
            }

            .login-container {
                max-width: 500px;
                padding: 25px;
            }

            h2 {
                font-size: 24px;
            }

            input, button {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form action="/login" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <a href="/forget-password">Forget Password?</a>
    </form>
</div>

</body>
</html>
