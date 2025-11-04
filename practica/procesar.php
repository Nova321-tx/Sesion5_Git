<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];

    if (!$desde || !$hasta) {
        die("Debe seleccionar ambas fechas.");
    }

    $sql = "
    SELECT v.fecha, p.nombre AS producto, dv.cantidad, p.precio, dv.subtotal
    FROM ventas v
    JOIN detalle_ventas dv ON v.id = dv.venta_id
    JOIN productos p ON p.id = dv.producto_id
    WHERE v.fecha BETWEEN '$desde' AND '$hasta'
    ORDER BY v.fecha
    ";

    $resultado = $conexion->query($sql);

    echo "<h2>Reporte de ventas del $desde al $hasta</h2>";
    echo "<table border='1'>
    <tr>
      <th>N°</th>
      <th>Fecha</th>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Precio Unitario</th>
      <th>Subtotal</th>
    </tr>";

    $total_general = 0;
    $n = 1;

    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>
          <td>{$n}</td>
          <td>{$fila['fecha']}</td>
          <td>{$fila['producto']}</td>
          <td>{$fila['cantidad']}</td>
          <td>{$fila['precio']}</td>
          <td>{$fila['subtotal']}</td>
        </tr>";
        $total_general += $fila['subtotal'];
        $n++;
    }

    echo "<tr><td colspan='5'><strong>Total General</strong></td><td><strong>$total_general</strong></td></tr>";
    echo "</table>";

    echo "<form method='POST' action='generar_pdf.php' target='_blank'>
        <input type='hidden' name='desde' value='$desde'>
        <input type='hidden' name='hasta' value='$hasta'>
        <button type='submit'>Exportar a PDF</button>
    </form>";
}
?>
