<?php
require 'db.php';
require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

$desde = $_GET['desde'] ?? '';
$hasta = $_GET['hasta'] ?? '';

if (!$desde || !$hasta) {
    die("Parámetros inválidos.");
}

$stmt = $pdo->prepare("
    SELECT e.nombre, e.curso, u.nombre AS nombre_docente, e.fecha_registro
    FROM estudiantes e
    JOIN usuarios u ON e.id_docente = u.id_usuario
    WHERE e.fecha_registro BETWEEN ? AND ?
    ORDER BY e.fecha_registro DESC
");
$stmt->execute([$desde, $hasta]);
$datos = $stmt->fetchAll();

$html = "
<h2 style='text-align: center;'>Reporte Semanal de Estudiantes</h2>
<p>Desde: <strong>$desde</strong> Hasta: <strong>$hasta</strong></p>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<thead>
<tr>
<th>Nombre</th>
<th>Curso</th>
<th>Docente</th>
<th>Fecha de Registro</th>
</tr>
</thead>
<tbody>";

foreach ($datos as $row) {
    $html .= "<tr>
        <td>{$row['nombre']}</td>
        <td>{$row['curso']}</td>
        <td>{$row['nombre_docente']}</td>
        <td>{$row['fecha_registro']}</td>
    </tr>";
}

$html .= "</tbody></table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_semanal.pdf", ["Attachment" => false]);
