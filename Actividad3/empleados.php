<?php
require_once "supabase.php";

// 🔹 Eliminar registro si se pasa ?delete=id
if (isset($_GET['delete'])) {
  supabaseRequest("empleados?id=eq." . $_GET['delete'], "DELETE");
  header("Location: empleados.php");
  exit;
}

$response = supabaseRequest("empleados", "GET");
$empleados = $response['body'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de Empleados</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<aside class="sidebar">
  <h2>Sistema</h2>
  <nav>
    <a href="index.php">🏠 Inicio</a>
    <a href="empleados.php" class="active">📋 Listado</a>
    <a href="registrar.php">➕ Registrar</a>
  </nav>
</aside>

<main class="main-content">
  <header><h1>Lista de Empleados</h1></header>

  <table>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Apellido</th>
      <th>Cargo</th>
      <th>Salario</th>
      <th>Acciones</th>
    </tr>
    <?php foreach ($empleados as $e): ?>
    <tr>
      <td><?= $e['id'] ?></td>
      <td><?= htmlspecialchars($e['nombre']) ?></td>
      <td><?= htmlspecialchars($e['apellido']) ?></td>
      <td><?= htmlspecialchars($e['cargo']) ?></td>
      <td>$<?= $e['salario'] ?></td>
      <td>
        <a class="btn" href="registrar.php?edit=<?= $e['id'] ?>">✏️ Editar</a>
        <a class="btn" href="empleados.php?delete=<?= $e['id'] ?>" onclick="return confirm('¿Eliminar este empleado?')">🗑️ Eliminar</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</main>
</body>
</html>
