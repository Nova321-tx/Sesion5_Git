<?php
class Autor {
    private $nombre;
    private $nacionalidad;

    public function __construct($nombre, $nacionalidad) {
        $this->nombre = $nombre;
        $this->nacionalidad = $nacionalidad;
    }

    public function getNombre() { return $this->nombre; }
    public function getNacionalidad() { return $this->nacionalidad; }
}
?>
