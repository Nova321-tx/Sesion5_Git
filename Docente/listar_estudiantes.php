<?php
require 'db.php';

$stmt = $pdo->prepare("
  SELECT e.id_estudiante, e.nombre, e.curso, e.id_docente, u.nombre AS nombre_docente
  FROM estudiantes e
  JOIN usuarios u ON e.id_docente = u.id_usuario
");
$stmt->execute();
$estudiantes = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($estudiantes);
