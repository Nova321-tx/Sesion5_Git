<?php
// clases/Carrito.php
require_once __DIR__ . '/../clases/Producto.php';

class Carrito {
    private $productos = [];

    public function agregar($producto, $cantidad = 1) {
        $id = $producto['id'];
        if (isset($this->productos[$id])) {
            $this->productos[$id]['cantidad'] += $cantidad;
        } else {
            $this->productos[$id] = [
                'id' => $id,
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad
            ];
        }
    }

    public function obtener() {
        return array_values($this->productos);
    }

    public function total() {
        $t = 0;
        foreach ($this->productos as $p) {
            $t += $p['precio'] * $p['cantidad'];
        }
        return $t;
    }

    public function limpiar() {
        $this->productos = [];
    }
}
?>