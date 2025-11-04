<?php
// views/login.php
require_once "../includes/auth.php";
require_once "../includes/funciones.php";

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Buscar usuario en Supabase
    $resp = sb_get("usuarios?email=eq." . urlencode($email));
    if ($resp['success'] && count($resp['body']) > 0) {
        $usuario = $resp['body'][0];
        if (password_verify($password, $usuario['password'])) {
            iniciarSesion($usuario);
            redirect("dashboard.php");
        } else {
            $mensaje = "⚠️ Contraseña incorrecta.";
        }
    } else {
        $mensaje = "⚠️ Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Biblioteca</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="login-container">
    <h2>📚 Biblioteca Virtual</h2>
    <form method="post" class="login-form">
        <label>Correo electrónico</label>
        <input type="email" name="email" required>
        <label>Contraseña</label>
        <input type="password" name="password" required>
        <button type="submit">Iniciar sesión</button>
        <?php if ($mensaje): ?>
            <p class="mensaje"><?= h($mensaje) ?></p>
        <?php endif; ?>
    </form>
</div>
</body>
</html>
