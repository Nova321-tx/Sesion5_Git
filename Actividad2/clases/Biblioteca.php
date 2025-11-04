<?php
require_once "Autor.php";
require_once "Libro.php";
require_once "Excepciones.php";

class Biblioteca {
    private $libros = [];

    public function registrarLibro($titulo, $nombreAutor, $nacionalidad) {
        // Validar campos vacíos
        if (empty($titulo) || empty($nombreAutor)) {
            throw new DatosInvalidosException("Debe llenar todos los campos.");
        }

        // Verificar si el libro ya existe (comparación insensible a mayúsculas/minúsculas)
        foreach ($this->libros as $libro) {
            if (strtolower($libro->getTitulo()) === strtolower($titulo)) {
                throw new DatosInvalidosException("El libro '{$titulo}' ya está registrado.");
            }
        }

        // Crear autor y libro
        $autor = new Autor($nombreAutor, $nacionalidad);
        $libro = new Libro($titulo, $autor);
        $this->libros[] = $libro;
    }

    public function listarLibros() {
        return $this->libros;
    }

    public function prestarLibro($titulo) {
        foreach ($this->libros as $libro) {
            if (strtolower($libro->getTitulo()) === strtolower($titulo)) {
                $libro->prestar();
                return;
            }
        }
        throw new LibroNoEncontradoException("Libro no encontrado.");
    }

    public function devolverLibro($titulo) {
        foreach ($this->libros as $libro) {
            if (strtolower($libro->getTitulo()) === strtolower($titulo)) {
                $libro->devolver();
                return;
            }
        }
        throw new LibroNoEncontradoException("Libro no encontrado.");
    }
}
?>
