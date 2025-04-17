<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <style>/* login.css */
/* login.css */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-container {
    background: white;
    padding: 40px;
    border-radius: 15px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    animation: fadeIn 1s ease forwards;
    opacity: 0;
    transform: translateY(20px);
}

@keyframes fadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-form h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #444;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    transition: border 0.3s ease;
}

.input-group input:focus {
    border-color: #667eea;
    outline: none;
}

.btn-login {
    width: 100%;
    padding: 12px;
    background: #667eea;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-login:hover {
    background: #5a67d8;
}

.btn-register {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}

.btn-register:hover {
    color: #5a67d8;
}

.header-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    text-align: center;
}


.logo-img {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
    margin-right: 10px;
}

.logo-title {
    font-size: 20px;
    font-weight: 600;
    color: #333;
}

.header-content {
    display: flex;
    align-items: center;
}

.logo-img {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
    margin-right: 10px;
}

.logo-title {
    font-size: 20px;
    font-weight: 600;
    color: #333;
}
</style>
<div class="login-container fade-in">
    <form method="POST" action="{{ route('auth.login') }}" class="login-form">
        @csrf

        <!-- LOGO + TITULO -->
        <div class="header-logo">
            <div class="header-content">
                <img src="{{ asset('vendor/adminlte/dist/img/logo.jpg') }}" alt="Logo" class="logo-img">
                <span class="logo-title"><b>Sport</b> One</span>
            </div>
        </div>

        <h2>Bienvenido</h2>

        <div class="input-group">
            <label for="usuario">Usuario</label>
            <input id="usuario" type="text" name="usuario" required autofocus>
        </div>

        <div class="input-group">
            <label for="clave">Contraseña</label>
            <input id="clave" type="password" name="clave" required>
        </div>

        <button type="submit" class="btn-login">Iniciar Sesión</button>

        <a href="{{ route('register') }}" class="btn-register">¿No tienes cuenta? Regístrate</a>
    </form>
    @if ($errors->has('usuario'))
    <div style="color: red; font-weight: bold; text-transform: uppercase; font-size: 0.875rem; margin-bottom: 1rem;">
    {{ $errors->first('usuario') }}
</div>
@endif

</div>

</body>
</html>
