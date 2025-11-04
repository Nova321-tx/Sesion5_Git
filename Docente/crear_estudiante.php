<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $curso = $_POST['curso'];
    $id_docente = $_POST['id_docente'];
    $fecha_registro = date('Y-m-d'); // Obtener la fecha actual

    $stmt = $pdo->prepare("INSERT INTO estudiantes (nombre, curso, id_docente, fecha_registro) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $curso, $id_docente, $fecha_registro]);

    header("Location: estudiantes_listar.html");
    exit;
}
?>
