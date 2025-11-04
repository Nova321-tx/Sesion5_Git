<?php
// views/prestamos.php
require_once "../includes/auth.php";
require_once "../includes/funciones.php";

// Verificar login
verificarLogin();
$usuario = $_SESSION['usuario'];
$mensaje = "";

// ============================
// Registrar nuevo préstamo
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'], $_POST['id_libro'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $id_libro = intval($_POST['id_libro']);
    $fecha_devolucion = $_POST['fecha_devolucion'] ?? null;

    // Crear préstamo
    $data = [
        "id_usuario" => $id_usuario,
        "id_libro" => $id_libro,
        "fecha_devolucion" => $fecha_devolucion,
        "devuelto" => false
    ];
    $resp = sb_post("prestamos", $data);

    // Marcar libro como no disponible
    sb_patch("libros?id=eq.$id_libro", ["disponible" => false]);

    $mensaje = $resp['success'] ? "✅ Préstamo registrado correctamente." : "⚠️ Error: " . getErrorMessage($resp);
}

// ============================
// Obtener usuarios, libros disponibles y préstamos
// ============================
$usuariosResp = sb_get("usuarios?select=*");
$usuarios = $usuariosResp['success'] ? $usuariosResp['body'] : [];

$librosResp = sb_get("libros?select=*&disponible=eq.true");
$libros = $librosResp['success'] ? $librosResp['body'] : [];

$prestamosResp = sb_get("prestamos?select=*,usuarios(*),libros(*)");
$prestamos = $prestamosResp['success'] ? $prestamosResp['body'] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Préstamos - Biblioteca</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="dashboard-container">
    <h2>📝 Gestión de Préstamos</h2>
    <a href="dashboard.php" class="menu-card logout" style="margin-bottom:15px;">← Volver al Dashboard</a>

    <?php if ($mensaje): ?>
        <p class="mensaje"><?= h($mensaje) ?></p>
    <?php endif; ?>

    <!-- Formulario nuevo préstamo -->
    <form method="post" class="form-agregar">
        <select name="id_usuario" required>
            <option value="">Selecciona un usuario</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= h($u['nombre']) ?> (<?= h($u['rol']) ?>)</option>
            <?php endforeach; ?>
        </select>

        <select name="id_libro" required>
            <option value="">Selecciona un libro disponible</option>
            <?php foreach ($libros as $l): ?>
                <option value="<?= $l['id'] ?>"><?= h($l['titulo']) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="date" name="fecha_devolucion" placeholder="Fecha de devolución">
        <button type="submit">Registrar Préstamo</button>
    </form>

    <!-- Tabla de préstamos -->
    <table>
        <tr>
            <th>Usuario</th>
            <th>Libro</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Devolución</th>
            <th>Devuelto</th>
        </tr>
        <?php foreach ($prestamos as $p): ?>
        <tr>
            <td><?= h($p['usuarios']['nombre'] ?? '') ?></td>
            <td><?= h($p['libros']['titulo'] ?? '') ?></td>
            <td><?= h($p['fecha_prestamo']) ?></td>
            <td><?= h($p['fecha_devolucion']) ?></td>
            <td><?= $p['devuelto'] ? 'Sí' : 'No' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
