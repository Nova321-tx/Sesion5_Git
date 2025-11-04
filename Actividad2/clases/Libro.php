<?php
class Libro {
    private $titulo;
    private $autor;
    private $estado;

    public function __construct($titulo, Autor $autor) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->estado = "Disponible";
    }

    public function getTitulo() { return $this->titulo; }
    public function getAutor() { return $this->autor; }
    public function getEstado() { return $this->estado; }

    public function prestar() {
        if ($this->estado === "Prestado") {
            throw new Exception("El libro ya está prestado.");
        }
        $this->estado = "Prestado";
    }

    public function devolver() {
        if ($this->estado === "Disponible") {
            throw new Exception("El libro ya está disponible.");
        }
        $this->estado = "Disponible";
    }
}
?>
