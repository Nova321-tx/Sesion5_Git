<?php
// views/dashboard.php
require_once "../includes/auth.php";
require_once "../includes/funciones.php";

// Verificar que el usuario esté logueado
verificarLogin();
$usuario = $_SESSION['usuario'];
$rol = $usuario['rol'] ?? 'lector';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Biblioteca</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="dashboard-container">
    <h1>📚 Bienvenido, <?= h($usuario['nombre']) ?></h1>
    <p>Rol: <?= h($rol) ?></p>

    <div class="dashboard-menu">
        <a href="libros.php" class="menu-card">📖 Libros</a>
        <a href="autores.php" class="menu-card">✍️ Autores</a>
        <a href="prestamos.php" class="menu-card">📝 Préstamos</a>
        <?php if ($rol === 'admin'): ?>
            <a href="usuarios.php" class="menu-card">👥 Usuarios</a>
        <?php endif; ?>
        <a href="../logout.php" class="menu-card logout">🚪 Cerrar sesión</a>
    </div>
</div>
</body>
</html>
