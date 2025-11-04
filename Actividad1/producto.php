<?php
class ProductoA {
    private $nombre;
    private $precio;
    private $stock;

    public function __construct($nombre, $precio, $stock) {
        if ($precio < 0) {
            throw new Exception("El precio no puede ser negativo.");
        }
        if ($stock < 0) {
            throw new Exception("El stock no puede ser negativo.");
        }
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
    }

    public function getNombre() { return $this->nombre; }
    public function getPrecio() { return $this->precio; }
    public function getStock() { return $this->stock; }

    public function setStock($nuevoStock) {
        if ($nuevoStock < 0) {
            throw new Exception("El stock no puede ser negativo.");
        }
        $this->stock += $nuevoStock;
    }
}
?>