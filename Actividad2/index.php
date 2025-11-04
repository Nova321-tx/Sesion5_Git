<?php
require_once "clases/Biblioteca.php";

session_start();

if (!isset($_SESSION['biblioteca'])) {
    $_SESSION['biblioteca'] = new Biblioteca();
}

$biblioteca = $_SESSION['biblioteca'];
$accion = $_GET['accion'] ?? '';

include "templates/header.php";

try {
    if ($accion === 'registrar') {
        include "templates/form_libro.php";
    } elseif ($accion === 'guardar') {
        $biblioteca->registrarLibro($_POST['titulo'], $_POST['autor'], $_POST['nacionalidad']);
        echo "<p class='success'>✅ Libro registrado correctamente.</p>";
        $libros = $biblioteca->listarLibros();
        include "templates/lista_libros.php";
    } elseif ($accion === 'prestar') {
        $biblioteca->prestarLibro($_GET['titulo']);
        echo "<p class='success'>📕 Libro prestado correctamente.</p>";
        $libros = $biblioteca->listarLibros();
        include "templates/lista_libros.php";
    } elseif ($accion === 'devolver') {
        $biblioteca->devolverLibro($_GET['titulo']);
        echo "<p class='success'>📗 Libro devuelto correctamente.</p>";
        $libros = $biblioteca->listarLibros();
        include "templates/lista_libros.php";
    } else {
        $libros = $biblioteca->listarLibros();
        include "templates/lista_libros.php";
    }
} catch (Exception $e) {
    echo "<p class='error'>⚠️ Error: " . $e->getMessage() . "</p>";
}

include "templates/footer.php";
?>
