<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id_estudiante = $_POST['id_estudiante'];
  $descripcion = $_POST['descripcion'];
  $fecha = $_POST['fecha'];

  // Obtener id_docente desde estudiantes para mantener referencia (opcional)
  $stmtDocente = $pdo->prepare("SELECT id_docente FROM estudiantes WHERE id_estudiante = ?");
  $stmtDocente->execute([$id_estudiante]);
  $resultado = $stmtDocente->fetch();

  if (!$resultado) {
    http_response_code(400);
    echo "Estudiante no encontrado";
    exit;
  }

  $id_docente = $resultado['id_docente'];

  // Insertar avance con id_docente
  $stmt = $pdo->prepare("INSERT INTO avances (id_estudiante, descripcion, fecha, id_docente) VALUES (?, ?, ?, ?)");
  $stmt->execute([$id_estudiante, $descripcion, $fecha, $id_docente]);

  echo "ok";
}
?>
