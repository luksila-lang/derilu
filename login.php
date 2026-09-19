<?php
session_start();

// Si el administrador ya había iniciado sesión, lo mandamos directo al panel
if (isset($_SESSION['admin_logeado']) && $_SESSION['admin_logeado'] === true) {
    header("Location: panel_admin.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_correcto = "admin";
    $password_correcta = "Derilu2026"; // Cambia esta contraseña por la que prefieras

    $usuario_ingresado = $_POST['usuario'] ?? '';
    $password_ingresada = $_POST['password'] ?? '';

    if ($usuario_ingresado === $usuario_correcto && $password_ingresada === $password_correcta) {
        // Creamos la sesión segura
        $_SESSION['admin_logeado'] = true;
        $_SESSION['usuario'] = $usuario_ingresado;
        
        header("Location: panel_admin.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo - DERILU</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 360px; text-align: center; }
        h2 { color: #6a1b1a; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; text-align: left; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; font-size: 14px; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #6a1b1a; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        button:hover { background-color: #521413; }
        .error-msg { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>DERILU WEB</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Panel de Administración</p>

    <?php if (!empty($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="input-group">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required placeholder="Ingresa tu usuario">
        </div>
        <div class="input-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
        </div>
        <button type="submit">Iniciar Sesión</button>
    </form>
	<div style="text-align: center; margin-top: 20px;">
    <a href="index.html" style="text-decoration: none; color: #5e0d0d; font-weight: bold; font-size: 0.95rem; transition: color 0.3s;" onmouseover="this.style.color='#ffcc00'" onmouseout="this.style.color='#5e0d0d'">
        ← Volver a la página principal
    </a>
</div>

</div>

</body>
</html>
