<?php
require_once "supabase.php";
date_default_timezone_set('America/La_Paz'); // 👈 AGREGA ESTA LÍNEA

// Validaciones servidor
$nombre = trim($_POST['nombre_usuario'] ?? '');
$comentario = trim($_POST['comentario'] ?? '');

if (!$nombre || !$comentario) {
  echo json_encode(["success" => false, "error" => "Datos incompletos"]);
  exit;
}

if (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/', $nombre)) {
  echo json_encode(["success" => false, "error" => "Nombre inválido"]);
  exit;
}

$data = [
  "nombre_usuario" => htmlspecialchars($nombre),
  "comentario" => htmlspecialchars($comentario),
  "fecha" => date("Y-m-d H:i:s")
];

$response = supabaseRequest("comentarios", "POST", $data);

echo json_encode(["success" => $response['status'] === 201]);
?>
