<?php
// Clase principal (padre)
class Mascota {
    public $nombre;
    public $edad;
    public $tipo;

    // Constructor: sirve para dar valor a las variables cuando se crea una mascota
    function __construct($nombre, $edad, $tipo) {
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->tipo = $tipo;
    }

    // Muestra los datos de la mascota
    function mostrarInfo() {
        echo "Nombre: " . $this->nombre . "<br>";
        echo "Edad: " . $this->edad . " años<br>";
        echo "Tipo: " . $this->tipo . "<br>";
    }

    // Sonido general (se puede cambiar en las clases hijas)
    function hacerSonido() {
        echo $this->nombre . " hace un sonido...<br>";
    }
}

// Clase Perro (hija de Mascota)
class Perro extends Mascota {
    function __construct($nombre, $edad) {
        // parent llama al constructor de la clase padre
        parent::__construct($nombre, $edad, "Perro");
    }

    // Sobrescribe el sonido del padre
    function hacerSonido() {
        echo $this->nombre . " dice: Guau Guau 🐶<br>";
    }

    function correr() {
        echo $this->nombre . " está corriendo 🐾<br>";
    }
}

// Clase Gato (hija de Mascota)
class Gato extends Mascota {
    function __construct($nombre, $edad) {
        parent::__construct($nombre, $edad, "Gato");
    }

    function hacerSonido() {
        echo $this->nombre . " dice: Miau 🐱<br>";
    }

    function dormir() {
        echo $this->nombre . " está durmiendo 😴<br>";
    }
}

// Crear objetos
$perro = new Perro("Firulais", 3);
$gato = new Gato("Misu", 2);

// Mostrar resultados
echo "<h2>🐶 Información del perro:</h2>";
$perro->mostrarInfo();
$perro->hacerSonido();
$perro->correr();

echo "<hr>";

echo "<h2>🐱 Información del gato:</h2>";
$gato->mostrarInfo();
$gato->hacerSonido();
$gato->dormir();
?>