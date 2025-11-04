<?php
require_once "supabase.php";

// Si viene editando un empleado
$empleado = ['id'=>'','nombre'=>'', 'apellido'=>'', 'cargo'=>'', 'salario'=>''];

if (isset($_GET['edit'])) {
    $resp = supabaseRequest("empleados?id=eq." . $_GET['edit'], "GET");
    $empleado = $resp['body'][0] ?? $empleado;
}

// Guardar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $cargo = trim($_POST['cargo']);
    $salario = (float)$_POST['salario'];

    $errores = [];

    // Validación de solo texto
    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/", $nombre)) $errores[] = "Nombre inválido";
    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/", $apellido)) $errores[] = "Apellido inválido";
    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/", $cargo)) $errores[] = "Cargo inválido";

    // Salario positivo
    if ($salario <= 0) $errores[] = "Salario debe ser positivo";

    // Comprobar duplicado
    $response = supabaseRequest("empleados", "GET");
    $empleados = $response['body'] ?? [];
    foreach ($empleados as $e) {
        if (strtolower($e['nombre']) === strtolower($nombre) &&
            strtolower($e['apellido']) === strtolower($apellido) &&
            ($empleado['id'] ?? '') !== ($e['id'] ?? '')) {
            $errores[] = "Empleado ya registrado";
            break;
        }
    }

    if (empty($errores)) {
        $data = [
            "nombre" => $nombre,
            "apellido" => $apellido,
            "cargo" => $cargo,
            "salario" => $salario
        ];

        if (!empty($_POST['id'])) {
            supabaseRequest("empleados?id=eq." . $_POST['id'], "PATCH", $data);
        } else {
            supabaseRequest("empleados", "POST", $data);
        }

        header("Location: empleados.php");
        exit;
    } else {
        $errorJS = implode("\\n",$errores);
        echo "<script>alert('".$errorJS."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($_GET['edit']) ? "Editar Empleado" : "Registrar Empleado" ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<aside class="sidebar">
    <h2>Sistema de Empleados</h2>
    <nav>
        <a href="index.php">🏠 Inicio</a>
        <a href="empleados.php">📋 Listado</a>
        <a href="registrar.php" class="active">➕ Registrar</a>
    </nav>
</aside>

<main class="main-content">
    <div class="registro-container">
        <header>
            <h1><?= isset($_GET['edit']) ? "Editar Empleado" : "Registrar Empleado" ?></h1>
        </header>

        <form method="POST" class="registro-form">
            <input type="hidden" name="id" value="<?= $empleado['id'] ?? '' ?>">

            <label>Nombre:</label>
            <input type="text" name="nombre" required value="<?= htmlspecialchars($empleado['nombre']) ?>">

            <label>Apellido:</label>
            <input type="text" name="apellido" required value="<?= htmlspecialchars($empleado['apellido']) ?>">

            <label>Cargo:</label>
            <input type="text" name="cargo" required value="<?= htmlspecialchars($empleado['cargo']) ?>">

            <label>Salario:</label>
            <input type="number" name="salario" required step="0.01" value="<?= $empleado['salario'] ?>">

            <button type="submit" class="btn"><?= isset($_GET['edit']) ? "Actualizar" : "Registrar" ?></button>
        </form>
    </div>
</main>

<script>
const form = document.querySelector('.registro-form');

form.addEventListener('submit', function(e) {
    const nombre = form.nombre.value.trim();
    const apellido = form.apellido.value.trim();
    const cargo = form.cargo.value.trim();
    const salario = parseFloat(form.salario.value);

    let errores = [];
    const textoRegex = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;

    if (!textoRegex.test(nombre)) errores.push("El nombre solo puede contener letras y espacios.");
    if (!textoRegex.test(apellido)) errores.push("El apellido solo puede contener letras y espacios.");
    if (!textoRegex.test(cargo)) errores.push("El cargo solo puede contener letras y espacios.");
    if (isNaN(salario) || salario <= 0) errores.push("El salario debe ser un número positivo.");

    if (errores.length > 0) {
        e.preventDefault();
        alert(errores.join("\n"));
        return false;
    }
});
</script>

</body>
</html>
