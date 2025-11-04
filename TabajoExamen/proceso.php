<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombreCompleto"];
    $edad = $_POST["edad"];
    $genero = $_POST["genero"];
    $lenguajes = isset($_POST["lenguajes"]) ? $_POST["lenguajes"] : [];
    $pais = $_POST["pais"];

    echo "<h2>Formulario completo:</h2>";

    // Edad
    if ($edad >= 18) {
        echo "<p>Hola <strong>$nombre</strong>, tu genero es <strong>$genero</strong> y tienes <strong>$edad</strong> años y eres mayor de edad.</p>";
    } else {
        echo "<p>Hola <strong>$nombre</strong>, tu genero es <strong>$genero</strong> y tienes <strong>$edad</strong> años y eres menor de edad.</p>";
    }

    // Lenguaje
    if (!empty($lenguajes)) {
        echo "<p>Lenguajes de programacion que conoce:</p><ol>";
        $contador = 1;
        foreach ($lenguajes as $leng) {
            echo "<li>$leng</li>";
            $contador++;
        }
        echo "</ol>";
    } else {
        echo "<p>No seleccionaste ningun lenguaje de programacion.</p>";
    }


    // Pais
    if (!empty($pais)) {
        echo "<p>Pais donde resides: <strong>$pais</strong></p>";
    } else {
        echo "<p>No selecciono ningun pais de residencia.</p>";
    }
}
?>
