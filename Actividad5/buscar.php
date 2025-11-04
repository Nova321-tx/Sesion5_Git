<?php
header('Content-Type: application/json; charset=utf-8');

// Conexión segura
$mysqli = new mysqli("localhost", "root", "", "buscador_productos");
if ($mysqli->connect_errno) {
    echo json_encode([
        "status" => "error",
        "message" => "Error de conexión a la base de datos"
    ]);
    exit;
}

$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($busqueda === '') {
    echo json_encode([
        "status" => "ok",
        "results" => []
    ]);
    exit;
}

// Consulta segura con prepared statement
$stmt = $mysqli->prepare("SELECT nombre FROM productos WHERE nombre LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param("s", $busqueda);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row['nombre'];
}

echo json_encode([
    "status" => "ok",
    "results" => $productos
]);

$stmt->close();
$mysqli->close();
?>
