<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id_usuario"];
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $id_rol = $_POST["id_rol"];

    $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, correo=?, id_rol=? WHERE id_usuario=?");
    $stmt->execute([$nombre, $correo, $id_rol, $id]);

    header("Location: usuarios_listar.html");
    exit;
}
?>
