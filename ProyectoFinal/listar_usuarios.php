<?php
require 'db.php';

$stmt = $pdo->query("SELECT usuarios.id_usuario, usuarios.nombre, usuarios.correo, roles.nombre_rol 
                     FROM usuarios JOIN roles ON usuarios.id_rol = roles.id_rol");

echo json_encode($stmt->fetchAll());
?>
