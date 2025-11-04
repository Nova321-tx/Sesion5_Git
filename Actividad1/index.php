<?php
session_start(); // Activa las sesiones en PHP
require_once "producto.php";
require_once "categoria.php";

// Creamos la categoría (siempre la misma para este ejemplo)
$categoria = new Categoria("Panaderia");

// Recuperar productos desde la sesión (si existen)
if (isset($_SESSION["productos"])) {
    foreach ($_SESSION["productos"] as $p) {
        $producto = new Producto($p['nombre'], $p['precio'], $p['stock']);
        $categoria->agregarProducto($producto);
    }
}

// Variable para mostrar mensajes
$mensaje = "";

try {
    // Agregar producto
    if (isset($_POST["accion"]) && $_POST["accion"] === "agregar") {
        $nombre = $_POST["nombre"];
        $precio = floatval($_POST["precio"]);
        $stock = intval($_POST["stock"]);

        $producto = new Producto($nombre, $precio, $stock);
        $categoria->agregarProducto($producto);

        $mensaje = "&#x2705; Producto agregado correctamente.";
    }

    // Actualizar stock
    if (isset($_POST["accion"]) && $_POST["accion"] === "actualizar") {
        $nombre = $_POST["nombre"];
        $nuevoStock = intval($_POST["stock"]);

        $categoria->actualizarStock($nombre, $nuevoStock);
        $mensaje = "&#x2705; Stock actualizado correctamente.";
    }

} catch (Exception $e) {
    $mensaje = " Error: " . $e->getMessage();
}

// 🔹 Guardar de nuevo los productos en la sesión
$_SESSION["productos"] = array_map(function($p) {
    return [
        "nombre" => $p->getNombre(),
        "precio" => $p->getPrecio(),
        "stock"  => $p->getStock()
    ];
}, $categoria->listarProductos());
?>

<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Inventario</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Script para mostrar el formulario seleccionado desde el menú
        function mostrarFormulario(id) {
            document.getElementById("form-agregar").style.display = "none";
            document.getElementById("form-actualizar").style.display = "none";
            document.getElementById(id).style.display = "block";
        }
    </script>
</head>
<body>

<div class="sidebar">
    <h2>&#x1F4E6; Inventario</h2>
    <ul>
        <li><a href="#" onclick="mostrarFormulario('form-agregar')">&#x2795; Agregar Producto</a></li>
        <li><a href="#" onclick="mostrarFormulario('form-actualizar')">&#x1F501; Actualizar Stock</a></li>
    </ul>
</div>

<div class="main">
    <h1>Sistema de Inventario - <?= $categoria->getNombre() ?></h1>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?= $mensaje ?></div>
    <?php endif; ?>

    <!-- Formulario para agregar producto -->
    <form method="POST" id="form-agregar" class="formulario">
        <h3>Agregar Producto</h3>
        <input type="hidden" name="accion" value="agregar">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" required>
        <label>Stock:</label>
        <input type="number" name="stock" required>
        <button type="submit">Agregar</button>
    </form>

    <!-- Formulario para actualizar stock -->
    <form method="POST" id="form-actualizar" class="formulario" style="display:none;">
        <h3>Actualizar Stock</h3>
        <input type="hidden" name="accion" value="actualizar">
        <label>Nombre del producto:</label>
        <input type="text" name="nombre" required>
        <label>Nuevo Stock:</label>
        <input type="number" name="stock" required>
        <button type="submit">Actualizar</button>
    </form>

    <!-- Tabla de productos -->
    <h3>Listado de Productos</h3>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Precio (Bs)</th>
            <th>Stock</th>
        </tr>
        <?php foreach ($categoria->listarProductos() as $producto): ?>
            <tr>
                <td><?= htmlspecialchars($producto->getNombre()) ?></td>
                <td><?= number_format($producto->getPrecio(), 2) ?></td>
                <td><?= $producto->getStock() ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
