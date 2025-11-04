<?php
require_once __DIR__ . '/../supabase.php';
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

  // ========================
  // 🔹 OBTENER VENTAS
  // ========================
  case 'GET':
    $res = supabaseRequest('/rest/v1/ventas_manuales?select=*', 'GET');
    echo $res['body'];
    break;

  // ========================
  // 🔹 CREAR VENTA
  // ========================
  case 'POST':
    $data = json_decode(file_get_contents('php://input'), true);

    // 1️⃣ Guardar venta
    $body = [[
        'cliente_nombre' => $data['razon_social'] ?? 'Consumidor Final',
        'cliente_nit' => $data['nit'] ?? null,
        'direccion' => $data['direccion'] ?? null,
        'productos' => json_encode($data['productos'] ?? []),
        'total' => floatval($data['total'] ?? 0),
        'fecha' => date('c'),
        'id_admin' => $data['usuario_id'] ?? null
    ]];

    $r = supabaseRequest('/rest/v1/ventas_manuales', 'POST', $body, true, ['Prefer: return=representation']);

    $ok = $r['status'] < 400;
    $respuesta = json_decode($r['body'], true);

    // 2️⃣ Actualizar stock si la venta fue exitosa
    if ($ok && isset($data['productos'])) {
        foreach ($data['productos'] as $item) {
            $producto_id = $item['id'];
            $cantidad_vendida = $item['cantidad'];

            // Obtener stock actual
            $res_stock = supabaseRequest("/rest/v1/productos?id=eq.$producto_id", 'GET');
            $producto = json_decode($res_stock['body'], true)[0] ?? null;

            if ($producto) {
                $nuevo_stock = max(0, $producto['stock'] - $cantidad_vendida);
                supabaseRequest("/rest/v1/productos?id=eq.$producto_id", 'PATCH', [['stock' => $nuevo_stock]]);
            }
        }
    }

    echo json_encode([
        'ok' => $ok,
        'mensaje' => $ok ? '✅ Venta registrada correctamente' : '⚠️ Error al registrar venta',
        'respuesta' => $respuesta
    ]);
    break;

  default:
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>
