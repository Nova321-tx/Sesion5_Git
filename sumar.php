<?php

function Sumar($a)
{
    while ($a > 0) {
        if ($a == 3) {
            break;
        }
        $a++;
    }
    echo "Valor final de a: " . $a . "\n";
}

// Ejemplo de uso
Sumar(1);

?>
