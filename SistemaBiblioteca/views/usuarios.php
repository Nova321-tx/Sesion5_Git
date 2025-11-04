<?php
// views/usuarios.php
require_once "../includes/auth.php";
require_once "../includes/funciones.php";

// Verificar que sea admin
verificarAdmin();
$usuario = $_SESSION['usuario'];
$mensaje = "";

// ============================
// Agregar usuario
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $rol = trim($_POST['rol']);

    if ($nombre && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $data = [
            "nombre" => $nombre,
            "email" => $email,
            "password" => $hash,
            "rol" => $rol
        ];

        $resp = sb_post("usuarios", $data);
        $mensaje = $resp['success'] ? "✅ Usuario agregado correctamente." : "⚠️ Error: " . getErrorMessage($resp);
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    }
}

// ============================
// Obtener lista de usuarios
// ============================
$usuariosResp = sb_get("usuarios?select=*");
$usuarios = $usuariosResp['success'] ? $usuariosResp['body'] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuarios - Biblioteca</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="dashboard-container">
    <h2>👥 Gestión de Usuarios</h2>
    <a href="dashboard.php" class="menu-card logout" style="margin-bottom:15px;">← Volver al Dashboard</a>

    <?php if ($mensaje): ?>
        <p class="mensaje"><?= h($mensaje) ?></p>
    <?php endif; ?>

    <!-- Formulario agregar usuario -->
    <form method="post" class="form-agregar">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <select name="rol" required>
            <option value="lector">Lector</option>
            <option value="admin">Administrador</option>
        </select>
        <button type="submit">Agregar Usuario</button>
    </form>

    <!-- Tabla de usuarios -->
    <table>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
        </tr>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= h($u['nombre']) ?></td>
            <td><?= h($u['email']) ?></td>
            <td><?= h($u['rol']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
