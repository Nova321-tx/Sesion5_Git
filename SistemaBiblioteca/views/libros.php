<?php
// views/libros.php
require_once "../includes/auth.php";
require_once "../includes/funciones.php";

// Verificar login
verificarLogin();
$usuario = $_SESSION['usuario'];
$mensaje = "";

// ============================
// Procesar formulario para agregar libro
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $id_autor = intval($_POST['id_autor']);
    $genero = trim($_POST['genero']);
    $anio = intval($_POST['anio_publicacion']);

    if ($titulo && $id_autor) {
        $data = [
            "titulo" => $titulo,
            "id_autor" => $id_autor,
            "genero" => $genero,
            "anio_publicacion" => $anio,
            "disponible" => true
        ];
        $resp = sb_post("libros", $data);
        $mensaje = $resp['success'] ? "✅ Libro agregado correctamente." : "⚠️ Error: " . getErrorMessage($resp);
    } else {
        $mensaje = "⚠️ Título y Autor son obligatorios.";
    }
}

// ============================
// Obtener autores y libros
// ============================
$autoresResp = sb_get("autores?select=*");
$autores = $autoresResp['success'] ? $autoresResp['body'] : [];

$librosResp = sb_get("libros?select=*,autores(*)");
$libros = $librosResp['success'] ? $librosResp['body'] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Libros - Biblioteca</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="dashboard-container">
    <h2>📖 Gestión de Libros</h2>
    <a href="dashboard.php" class="menu-card logout" style="margin-bottom:15px;">← Volver al Dashboard</a>

    <?php if ($mensaje): ?>
        <p class="mensaje"><?= h($mensaje) ?></p>
    <?php endif; ?>

    <!-- Formulario para agregar libro -->
    <form method="post" class="form-agregar">
        <input type="text" name="titulo" placeholder="Título del libro" required>
        <select name="id_autor" required>
            <option value="">Selecciona un autor</option>
            <?php foreach ($autores as $a): ?>
                <option value="<?= $a['id'] ?>"><?= h($a['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="genero" placeholder="Género">
        <input type="number" name="anio_publicacion" placeholder="Año de publicación">
        <button type="submit">Agregar Libro</button>
    </form>

    <!-- Tabla de libros -->
    <table>
        <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Género</th>
            <th>Año</th>
            <th>Disponible</th>
        </tr>
        <?php foreach ($libros as $l): ?>
        <tr>
            <td><?= h($l['titulo']) ?></td>
            <td><?= h($l['autores']['nombre'] ?? '') ?></td>
            <td><?= h($l['genero']) ?></td>
            <td><?= h($l['anio_publicacion']) ?></td>
            <td><?= $l['disponible'] ? 'Sí' : 'No' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
