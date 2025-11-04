<?php
// api/productos.php
require_once __DIR__ . '/../supabase.php';
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

  // ========================
  // 🔹 OBTENER PRODUCTOS
  // ========================
  case 'GET':
    $q = $_GET['q'] ?? null;
    $endpoint = '/rest/v1/productos?select=*';

    if ($q) {
      $qEncoded = urlencode("%$q%");
      $endpoint .= "&or=(nombre.ilike.$qEncoded,descripcion.ilike.$qEncoded)";
    }

    $r = supabaseRequest($endpoint, 'GET');
    echo $r['body'];
    break;

  // ========================
  // 🔹 CREAR PRODUCTO
  // ========================
  case 'POST':
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    $body = [[
      'nombre' => $data['nombre'] ?? null,
      'descripcion' => $data['descripcion'] ?? null,
      'precio' => floatval($data['precio'] ?? 0),
      'stock' => intval($data['stock'] ?? 0),
      'categoria' => $data['categoria'] ?? null,
      'imagen_url' => $data['imagen_url'] ?? null,
      'created_at' => date('c')
    ]];

    $r = supabaseRequest('/rest/v1/productos', 'POST', $body, true, ['Prefer: return=representation']);

    echo json_encode([
      'ok' => $r['status'] < 400,
      'mensaje' => $r['status'] < 400 ? '✅ Producto guardado correctamente' : '⚠️ Error al guardar',
      'respuesta' => json_decode($r['body'], true)
    ]);
    break;

  // ========================
  // 🔹 ACTUALIZAR PRODUCTO
  // ========================
  case 'PATCH':
    if (!isset($_GET['id'])) {
      http_response_code(400);
      echo json_encode(['error' => 'Falta el ID']);
      exit;
    }

    $id = intval($_GET['id']);
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    $body = [[
      'nombre' => $data['nombre'] ?? null,
      'descripcion' => $data['descripcion'] ?? null,
      'precio' => floatval($data['precio'] ?? 0),
      'stock' => intval($data['stock'] ?? 0),
      'categoria' => $data['categoria'] ?? null,
      'imagen_url' => $data['imagen_url'] ?? null
    ]];

    $r = supabaseRequest("/rest/v1/productos?id=eq.$id", 'PATCH', $body);
    echo json_encode([
      'ok' => $r['status'] < 400,
      'mensaje' => $r['status'] < 400 ? '✅ Producto actualizado correctamente' : '⚠️ Error al actualizar',
      'respuesta' => json_decode($r['body'], true)
    ]);
    break;

  // ========================
  // 🔹 ELIMINAR PRODUCTO
  // ========================
  case 'DELETE':
    if (!isset($_GET['id'])) {
      http_response_code(400);
      echo json_encode(['error' => 'Falta el ID']);
      exit;
    }

    $id = intval($_GET['id']);
    $r = supabaseRequest("/rest/v1/productos?id=eq.$id", 'DELETE');
    echo $r['body'];
    break;

  default:
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>