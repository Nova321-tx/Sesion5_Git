<?php
// 6. str_replace y strpos
$frase = "Aprender PHP es divertido";

echo "<h3>Frase original:</h3>";
echo $frase . "<br><br>";

// Reemplazo
$fraseModificada = str_replace("divertido", "poderoso", $frase);
echo "<h3>Después de str_replace:</h3>";
echo $fraseModificada . "<br><br>";

// Posición de "PHP"
$posicion = strpos($frase, "PHP");
echo "<h3>Posición de 'PHP' usando strpos:</h3>";
echo "La palabra 'PHP' está en la posición: $posicion<br><br>";
?>