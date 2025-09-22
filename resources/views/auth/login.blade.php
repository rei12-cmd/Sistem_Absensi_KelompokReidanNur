{{-- resources/views/auth/login.blade.php --}}
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Learning SMK Assalam Samarang - Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f2f5;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .form-content { transition: transform 0.6s ease-in-out; }
        .login-form, .register-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .register-form {
            position: absolute;
            top: 40px;
            left: 0;
            width: 100%;
            padding: 0 40px;
            transform: translateX(100%);
        }
        .container.active .login-form { transform: translateX(-100%); }
        .container.active .register-form { transform: translateX(0); }
        .login-logo { width:100px; margin-bottom:20px; }
        .form-group { text-align: left; }
        .form-group label { font-weight:500; color:#555; display:block; margin-bottom:5px; }
        .form-group input, .form-group select {
            width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; font-size:16px;
        }
        .form-button {
            background-color:#4a86e8; color:#fff; padding:12px; border:none; border-radius:5px; font-size:16px; font-weight:600; cursor:pointer;
        }
        .form-button:hover { background-color:#3b6ac2; }
        .toggle-link { font-size:14px; color:#4a86e8; margin-top:10px; cursor:pointer; }
        .password-container { position:relative; }
        .password-container input { padding-right:40px; }
        .toggle-password { position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#888; font-size:1.1em; }
        .toggle-password:hover { color:#333; }
        .alert { background:#fdecea; color:#611; padding:10px; border-radius:6px; margin-bottom:12px; text-align:left; }
        .field-error { color:#b00020; font-size:0.9rem; margin-top:6px; }
    </style>
</head>
<body>
    <div class="container" id="formContainer">
        <img src="{{ asset('images/logo.png') }}" alt="logo" class="login-logo">

        <div class="form-content">
            {{-- LOGIN FORM --}}
            <form class="login-form" id="loginForm" method="POST" action="">
                @csrf
                <h2>Login</h2>

                {{-- error message --}}
                @if($errors->any())
                    <div class="alert">
                        <ul style="margin:0;padding-left:18px;">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="login-field">Username / Email</label>
                    <input id="login-field" name="login" type="text" placeholder="Username atau Email" value="{{ old('login') }}" required autofocus>
                    @error('login')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group password-container">
                    <label for="login-password">Password</label>
                    <input id="login-password" name="password" type="password" placeholder="Password" required>
                    <i class="fas fa-eye-slash toggle-password" data-target="#login-password"></i>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="form-button">Login</button>
                <p class="toggle-link" id="showRegister">Belum punya akun? Registrasi di sini.</p>
            </form>


        </div>
    </div>


</body>
</html>
