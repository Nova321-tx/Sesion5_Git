<?php
// api/autores.php
require_once "../supabase.php";
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $resp = sb_get("autores?select=*");
        echo json_encode($resp);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $resp = sb_post("autores", $data);
        echo json_encode($resp);
        break;

    case 'PATCH':
        $id = intval($_GET['id']);
        $data = json_decode(file_get_contents('php://input'), true);
        $resp = sb_patch("autores?id=eq.$id", $data);
        echo json_encode($resp);
        break;

    case 'DELETE':
        $id = intval($_GET['id']);
        $resp = sb_delete("autores?id=eq.$id");
        echo json_encode($resp);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}
