<?php
require_once "supabase.php";
$id = $_GET['id'] ?? null;

if (!$id) die("ID inválido.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $data = [
    'nombre' => $_POST['nombre'],
    'apellido' => $_POST['apellido'],
    'cargo' => $_POST['cargo'],
    'salario' => (float)$_POST['salario']
  ];
  $res = supabaseRequest("empleados?id=eq.$id", "PATCH", $data);
  header("Location: empleados.php");
  exit;
}

$res = supabaseRequest("empleados?id=eq.$id", "GET");
$emp = $res['body'][0] ?? null;
if (!$emp) die("Empleado no encontrado.");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Empleado</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>✏️ Editar Empleado</header>

<div class="container">
  <form method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($emp['nombre']) ?>" required>

    <label>Apellido:</label>
    <input type="text" name="apellido" value="<?= htmlspecialchars($emp['apellido']) ?>" required>

    <label>Cargo:</label>
    <input type="text" name="cargo" value="<?= htmlspecialchars($emp['cargo']) ?>" required>

    <label>Salario:</label>
    <input type="number" name="salario" value="<?= htmlspecialchars($emp['salario']) ?>" required>

    <button type="submit">Guardar Cambios</button>
  </form>
</div>
</body>
</html>
