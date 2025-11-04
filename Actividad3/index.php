<?php
require_once "supabase.php";
$response = supabaseRequest("empleados", "GET");
$empleados = $response['body'] ?? [];

$total = count($empleados);
$cargos = [];
$salarioTotal = 0;

foreach ($empleados as $e) {
  $cargo = $e['cargo'];
  $cargos[$cargo] = ($cargos[$cargo] ?? 0) + 1;
  $salarioTotal += $e['salario'];
}

$promedio = $total > 0 ? round($salarioTotal / $total, 2) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Empleados</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<aside class="sidebar">
  <h2>Sistema de Empleados</h2>
  <nav>
    <a href="index.php" class="active">🏠 Inicio</a>
    <a href="empleados.php">📋 Listado</a>
    <a href="registrar.php">➕ Registrar</a>
  </nav>
</aside>

<main class="main-content">
  <header><h1>Estadistica de Empleados</h1></header>

  <div class="dashboard">
    <div class="stats">
      <div class="card"><h3><?= $total ?></h3><p>Total de Empleados</p></div>
      <div class="card"><h3><?= count($cargos) ?></h3><p>Cargos Registrados</p></div>
      <div class="card"><h3>$<?= $promedio ?></h3><p>Promedio Salarial</p></div>
    </div>

    <div class="chart-container"><canvas id="graficoCargos"></canvas></div>
  </div>
</main>

<script>
const ctx = document.getElementById('graficoCargos').getContext('2d');
new Chart(ctx, {
  type: 'pie',
  data: {
    labels: <?= json_encode(array_keys($cargos)) ?>,
    datasets: [{
      data: <?= json_encode(array_values($cargos)) ?>,
      backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1']
    }]
  },
  options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
</body>
</html>
