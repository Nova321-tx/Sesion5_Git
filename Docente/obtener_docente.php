<?php
require 'db.php';

$stmt = $pdo->prepare("SELECT id_usuario, nombre FROM usuarios WHERE id_rol = 2");
$stmt->execute();
$docentes = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($docentes);
?>
