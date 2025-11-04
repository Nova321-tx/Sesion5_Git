<?php
$nombre="Juan";
$edad=25;
$altura=1.75;
$esEstudiante=true;

echo "Hola ".$nombre.", tienes " . $edad . " anios.";
?>

<?php
$suma=10+5;
$resta=10-5;
$multiplicacion=10*5;
$division=10/5;
$modulo=10%3;

$esMayor=10>5;
$esIgual=10=="10";
$esIdentico=10==="10";

?>
<?php
for($i=1;$i<=20;$i++)
    if($i%2==0){
         echo "El numero ", $i, " es par <br>";

    };
?>