<?php
require 'db.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $pdo->prepare("DELETE FROM estudiantes WHERE id_estudiante = ?");
  $stmt->execute([$id]);

  header("Location: estudiantes_listar.html");
  exit;
}
?>
