<?php
require 'db.php';
require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

$id = $_GET['id'] ?? '';

if (!$id) {
    die("Estudiante no válido.");
}

$stmt = $pdo->prepare("
    SELECT e.nombre, e.curso, u.nombre AS nombre_docente, a.fecha, a.descripcion
    FROM avances a
    JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
    JOIN usuarios u ON a.id_docente = u.id_usuario
    WHERE e.id_estudiante = ?
    ORDER BY a.fecha DESC
");
$stmt->execute([$id]);
$datos = $stmt->fetchAll();

if (!$datos) {
    die("No se encontraron avances.");
}

$nombreEstudiante = $datos[0]['nombre'];
$curso = $datos[0]['curso'];

$html = "
<h2 style='text-align: center;'>Reporte Individual de Avances</h2>
<p><strong>Estudiante:</strong> $nombreEstudiante<br>
<strong>Curso:</strong> $curso</p>
<table border='1' cellpadding='5' cellspacing='0' width='100%'>
<thead>
<tr>
<th>Docente</th>
<th>Fecha</th>
<th>Descripción</th>
</tr>
</thead>
<tbody>";

foreach ($datos as $r) {
    $html .= "<tr>
        <td>{$r['nombre_docente']}</td>
        <td>{$r['fecha']}</td>
        <td>{$r['descripcion']}</td>
    </tr>";
}

$html .= "</tbody></table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_individual.pdf", ["Attachment" => false]);
