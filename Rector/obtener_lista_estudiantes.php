<?php
require 'db.php';

$stmt = $pdo->query("SELECT id_estudiante, nombre FROM estudiantes ORDER BY nombre");
echo json_encode($stmt->fetchAll());
