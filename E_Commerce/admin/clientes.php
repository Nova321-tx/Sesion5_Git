<?php
// admin/clientes.php
require_once __DIR__ . '/../clases/Usuario.php';
header('Content-Type: text/html; charset=utf-8');
$u = new Usuario();

// Obtenemos todos los usuarios
$usuarios = $u->obtenerTodos(); // Necesitamos crear este método en Usuario.php
?>
<h2>Clientes registrados</h2>
<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Fecha registro</th>
    </tr>
<?php foreach ($usuarios as $user): ?>
    <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['nombre']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['rol']) ?></td>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
    </tr>
<?php endforeach; ?>
</table>
