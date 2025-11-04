<?php
require_once __DIR__ . '/../clases/Venta.php';
$venta = new Venta();

$ventas = $venta->obtenerTodas(); // o un filtro según necesites
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Facturación - Admin</title>
<style>
table{border-collapse:collapse;width:100%}
th, td{border:1px solid #ddd;padding:8px;text-align:center}
</style>
</head>
<body>
<h2>Facturación</h2>
<table>
<tr>
  <th>ID</th>
  <th>Cliente</th>
  <th>Fecha</th>
  <th>Total</th>
  <th>Productos</th>
</tr>
<?php foreach($ventas as $v): 
    $fecha = $v['fecha'] ?? 'N/A';
    $total = $v['total'] ?? 0;
    $cliente = $v['usuarios']['nombre'] ?? 'Desconocido';
    $items = $v['venta_detalle'] ?? []; // si no existe, vacío
?>
<tr>
  <td><?= $v['id'] ?? '-' ?></td>
  <td><?= htmlspecialchars($cliente) ?></td>
  <td><?= htmlspecialchars($fecha) ?></td>
  <td>$<?= number_format($total,2) ?></td>
  <td>
    <?php if(!empty($items)): ?>
      <ul>
      <?php foreach($items as $it): 
          $nombreProd = $it['producto_id'] ?? 'ID '.$it['producto_id'] ?? 'N/A';
          $cant = $it['cantidad'] ?? 0;
          $sub = $it['subtotal'] ?? 0;
      ?>
        <li><?= htmlspecialchars($nombreProd) ?> × <?= $cant ?> = $<?= number_format($sub,2) ?></li>
      <?php endforeach; ?>
      </ul>
    <?php else: ?>
      N/A
    <?php endif; ?>
  </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
