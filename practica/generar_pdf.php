<?php
require 'conexion.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$desde = $_POST['desde'];
$hasta = $_POST['hasta'];

$sql = "
SELECT v.fecha, p.nombre AS producto, dv.cantidad, p.precio, dv.subtotal
FROM ventas v
JOIN detalle_ventas dv ON v.id = dv.venta_id
JOIN productos p ON p.id = dv.producto_id
WHERE v.fecha BETWEEN '$desde' AND '$hasta'
ORDER BY v.fecha
";

$result = $conexion->query($sql);

$html = '
<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: Arial; font-size: 12px; margin: 40px; }
    table { width: 100%; border-collapse: collapse; margin-top: 50px; }
    th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
    th { background-color: #eee; }

    header {
      position: fixed;
      top: -40px;
      text-align: center;
      font-size: 14px;
    }

    footer {
      position: fixed;
      bottom: -20px;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 10px;
      color: gray;
    }
  </style>
</head>
<body>

<header>
  <strong>Reporte de Ventas</strong><br>
  Del ' . $desde . ' al ' . $hasta . '
</header>

<footer>
  Página {PAGE_NUM} de {PAGE_COUNT}
</footer>

<table>
  <thead>
    <tr>
      <th>N°</th>
      <th>Fecha</th>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Precio Unitario</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>';

$total_general = 0;
$n = 1;

while ($fila = $result->fetch_assoc()) {
    $html .= "<tr>
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

$html .= "<tr><td colspan='5'><strong>Total General</strong></td><td><strong>$total_general</strong></td></tr>";
$html .= '</tbody></table></body></html>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_ventas.pdf", ["Attachment" => false]);
