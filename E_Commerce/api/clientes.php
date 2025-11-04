<?php
// api/clientes.php
require_once __DIR__ . '/../clases/Usuario.php';
header('Content-Type: application/json; charset=utf-8');

$u = new Usuario();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // Si no vienen parámetros, devolvemos todos los usuarios
        if (!isset($_GET['email']) && !isset($_GET['password'])) {
            $all = $u->obtenerTodos(); // Método que devuelve array de todos los usuarios
            echo json_encode($all);
            exit;
        }

        // Si vienen email y password, hacemos login
        if (isset($_GET['email']) && isset($_GET['password'])) {
            $user = $u->login($_GET['email'], $_GET['password']);
            if (!$user) {
                http_response_code(401);
                echo json_encode(['error' => 'Credenciales inválidas']);
                exit;
            }
            echo json_encode($user);
            exit;
        }
    }

    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $created = $u->registrar($data['nombre'], $data['email'], $data['password'], $data['rol'] ?? 'cliente');
        echo json_encode($created);
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
