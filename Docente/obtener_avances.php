<?php
require 'db.php';

if (!isset($_GET['id_estudiante'])) {
  echo json_encode([]);
  exit;
}

$id_estudiante = $_GET['id_estudiante'];

$stmt = $pdo->prepare("SELECT descripcion, fecha FROM avances WHERE id_estudiante = ? ORDER BY fecha DESC");
$stmt->execute([$id_estudiante]);
$avances = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($avances);
?>
