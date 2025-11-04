<?php
require_once __DIR__.'/../clases/Venta.php';
header('Content-Type: application/json; charset=utf-8');

$venta = new Venta();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // Coinciden con los nombres que usa el carrito
        $usuario_id = $data['cliente_id'] ?? $data['usuario_id'] ?? null;
        $items = $data['productos'] ?? $data['items'] ?? null;
        $total = $data['total'] ?? null;

        if (!$usuario_id || !$items || !$total) {
            throw new Exception("Faltan datos para registrar la venta");
        }

        // Registrar la venta
        $res = $venta->registrar($usuario_id, $total, $items);

        echo json_encode([
            'success' => true,
            'venta' => $res,
            'total' => number_format($total, 2)
        ]);
        exit;
    }

    if ($method === 'GET') {
        if (isset($_GET['resumen']) && $_GET['resumen'] === 'true') {
            echo json_encode($venta->obtenerResumen());
            exit;
        }

        if (isset($_GET['usuario_id'])) {
            echo json_encode($venta->obtenerPorUsuario($_GET['usuario_id']));
            exit;
        }

        echo json_encode($venta->obtenerTodas());
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
