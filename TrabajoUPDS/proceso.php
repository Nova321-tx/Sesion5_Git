<?php
// Respuestas correctas
$respuestas = [
    "mision1" => "emprendedores socialmente responsables",
    "mision2" => "desafíos emergentes",
    "vision1" => "formación académica de excelencia",
    "vision2" => "investigación científica",
    "vision3" => "internacionalización",
    "valor1" => "Respeto",
    "valor2" => "Resiliencia",
    "valor3" => "Integridad",
    "valor4" => "Compromiso",
    "valor5" => "Trabajo en equipo"
];

$puntos = 0;
$total = count($respuestas);

echo "<h1>Resultados del juego</h1>";

foreach ($respuestas as $campo => $respuestaCorrecta) {
    $respuestaUsuario = trim($_POST[$campo]);
    if (strcasecmp($respuestaUsuario, $respuestaCorrecta) == 0) {
        echo "<p><strong>$campo:</strong> ✔️ Correcto</p>";
        $puntos++;
    } else {
        echo "<p><strong>$campo:</strong> ❌ Incorrecto — Tu respuesta: '$respuestaUsuario' | Correcta: '$respuestaCorrecta'</p>";
    }
}

echo "<h2>Puntaje final: $puntos / $total</h2>";
?>
