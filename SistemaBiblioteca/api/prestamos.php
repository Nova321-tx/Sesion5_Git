<?php
// api/prestamos.php
require_once "../supabase.php";
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $resp = sb_get("prestamos?select=*,usuarios(*),libros(*)");
        echo json_encode($resp);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $resp = sb_post("prestamos", $data);

        // Marcar libro como no disponible
        if ($resp['success']) {
            sb_patch("libros?id=eq.".$data['id_libro'], ["disponible" => false]);
        }
        echo json_encode($resp);
        break;

    case 'PATCH':
        $id = intval($_GET['id']);
        $data = json_decode(file_get_contents('php://input'), true);
        $resp = sb_patch("prestamos?id=eq.$id", $data);

        // Si se marca como devuelto, actualizar libro
        if (isset($data['devuelto']) && $data['devuelto'] === true) {
            sb_patch("libros?id=eq.".$data['id_libro'], ["disponible" => true]);
        }
        echo json_encode($resp);
        break;

    case 'DELETE':
        $id = intval($_GET['id']);
        $resp = sb_delete("prestamos?id=eq.$id");
        echo json_encode($resp);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}
