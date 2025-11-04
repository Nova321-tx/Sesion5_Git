<?php
require 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("
  SELECT e.nombre, e.curso, u.nombre AS nombre_docente, a.fecha, a.descripcion
  FROM avances a
  JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
  JOIN usuarios u ON a.id_docente = u.id_usuario
  WHERE e.id_estudiante = ?
  ORDER BY a.fecha DESC
");
$stmt->execute([$id]);
echo json_encode($stmt->fetchAll());
?>