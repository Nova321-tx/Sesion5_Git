<?php
// Validación sencilla y segura en PHP

// Verificar que se haya enviado el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar entradas
    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $edad = filter_var($_POST["edad"], FILTER_SANITIZE_NUMBER_INT);

    // Validaciones
    $errores = [];

    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido.";
    }

    if ($edad < 1 || $edad > 120) {
        $errores[] = "La edad debe estar entre 1 y 120.";
    }

    // Mostrar resultados
    if (empty($errores)) {
        echo "<h2>Datos recibidos correctamente</h2>";
        echo "Nombre: $nombre <br>";
        echo "Email: $email <br>";
        echo "Edad: $edad años <br>";
    } else {
        echo "<h2>Se encontraron errores:</h2>";
        foreach ($errores as $error) {
            echo "- $error <br>";
        }
        echo "<br><a href='formulario.php'>Volver</a>";
    }
} else {
    echo "Acceso no permitido.";
}
?>
