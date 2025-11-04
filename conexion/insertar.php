<?php
// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];

    // Incluir la conexión
    require_once "conexion.php";

    // Insertar los datos usando prepare
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nombre, $email);

    if ($stmt->execute()) {
        echo "Usuario registrado correctamente.";
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Acceso inválido.";
}
?>
