<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $apellido = htmlspecialchars($_POST["apellido"]);
    $correo = htmlspecialchars($_POST["correo"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);
    $genero = isset($_POST["genero"]) ? $_POST["genero"] : "No especificado";
    $lenguajes = isset($_POST["lenguajes"]) ? $_POST["lenguajes"] : [];
    $pais = $_POST["pais"];

    echo "===== DATOS ENVIADOS =====<br>";
    echo "Nombre: " . $nombre . "<br>";
    echo "Correo: " . $correo . "<br>";
    echo "Mensaje: " . $mensaje . "<br>";
    echo "Género: " . $genero . "<br>";

    echo "Lenguajes seleccionados: <br>";
    if (!empty($lenguajes)) {
        foreach ($lenguajes as $lenguaje) {
            echo "- " . $lenguaje . "<br>";
        }
    } else {
        echo "No seleccionó ningún lenguaje.<br>";
    }

    echo "País: " . $pais . "<br>";
    echo "<br>✅ Se enviaron los datos correctamente.";
}
?>
