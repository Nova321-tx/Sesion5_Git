<?php
// views/autores.php
require_once "../includes/auth.php";
require_once "../includes/funciones.php";

// Verificar login
verificarLogin();
$mensaje = "";

// ============================
// Procesar formulario para agregar autor
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $nacionalidad = trim($_POST['nacionalidad']);

    if ($nombre) {
        $data = [
            "nombre" => $nombre,
            "nacionalidad" => $nacionalidad
        ];
        $resp = sb_post("autores", $data);
        $mensaje = $resp['success'] ? "✅ Autor agregado correctamente." : "⚠️ Error: " . getErrorMessage($resp);
    } else {
        $mensaje = "⚠️ El nombre del autor es obligatorio.";
    }
}

// ============================
// Obtener lista de autores
// ============================
$autoresResp = sb_get("autores?select=*");
$autores = $autoresResp['success'] ? $autoresResp['body'] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Autores - Biblioteca</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="dashboard-container">
    <h2>✍️ Gestión de Autores</h2>
    <a href="dashboard.php" class="menu-card logout" style="margin-bottom:15px;">← Volver al Dashboard</a>

    <?php if ($mensaje): ?>
        <p class="mensaje"><?= h($mensaje) ?></p>
    <?php endif; ?>

    <!-- Formulario agregar autor -->
    <form method="post" class="form-agregar">
        <input type="text" name="nombre" placeholder="Nombre del autor" required>
        <input type="text" name="nacionalidad" placeholder="Nacionalidad">
        <button type="submit">Agregar Autor</button>
    </form>

    <!-- Tabla de autores -->
    <table>
        <tr>
            <th>Nombre</th>
            <th>Nacionalidad</th>
        </tr>
        <?php foreach ($autores as $a): ?>
        <tr>
            <td><?= h($a['nombre']) ?></td>
            <td><?= h($a['nacionalidad']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
