<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $contraseña = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);
    $id_rol = $_POST["id_rol"];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contraseña, id_rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $correo, $contraseña, $id_rol]);

    header("Location: usuarios_listar.html");
    exit;
}
?>
