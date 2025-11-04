<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id_estudiante'];
    $nombre = $_POST['nombre'];
    $curso = $_POST['curso'];
    $id_docente = $_POST['id_docente']; // si lo vas a permitir modificar

    $stmt = $pdo->prepare("UPDATE estudiantes SET nombre=?, curso=?, id_docente=? WHERE id_estudiante=?");
    $stmt->execute([$nombre, $curso, $id_docente, $id]);

    header("Location: estudiantes_listar.html");
    exit;
}
?>

