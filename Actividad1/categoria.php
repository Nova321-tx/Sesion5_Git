<?php
require_once "producto.php";

class Categoria {
    private $nombre;
    private $productos = [];

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function agregarProducto(Producto $producto) {
        $this->productos[] = $producto;
    }

    public function listarProductos() {
        return $this->productos;
    }

    public function actualizarStock($nombre, $nuevoStock) {
        foreach ($this->productos as $producto) {
            if (strcasecmp($producto->getNombre(), $nombre) === 0) {
                $producto->setStock($nuevoStock);
                return;
            }
        }
        throw new Exception("El producto '$nombre' no existe en la categoría.");
    }

    public function getNombre() {
        return $this->nombre;
    }
}
?>