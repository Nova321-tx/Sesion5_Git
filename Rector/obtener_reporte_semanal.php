<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];

    $stmt = $pdo->prepare("
        SELECT e.nombre, e.curso, u.nombre AS nombre_docente, e.fecha_registro
        FROM estudiantes e
        JOIN usuarios u ON e.id_docente = u.id_usuario
        WHERE e.fecha_registro BETWEEN ? AND ?
        ORDER BY e.fecha_registro DESC
    ");
    $stmt->execute([$desde, $hasta]);
    echo json_encode($stmt->fetchAll());
}
?>