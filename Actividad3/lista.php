<?php
require_once "supabase.php";
$response = supabaseRequest("empleados", "GET");
$empleados = $response['body'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Empleados</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="table-container">
  <h2>Lista de Empleados Registrados</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Apellido</th>
      <th>Cargo</th>
      <th>Salario</th>
    </tr>

    <?php foreach ($empleados as $emp): ?>
    <tr>
      <td><?= htmlspecialchars($emp["id"]) ?></td>
      <td><?= htmlspecialchars($emp["nombre"]) ?></td>
      <td><?= htmlspecialchars($emp["apellido"]) ?></td>
      <td><?= htmlspecialchars($emp["cargo"]) ?></td>
      <td><?= htmlspecialchars($emp["salario"]) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <p style="text-align:center; margin-top:10px;">
    <a href="index.html">Registrar nuevo empleado</a>
  </p>
</div>

</body>
</html>
