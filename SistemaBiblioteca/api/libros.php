<?php
// api/libros.php
require_once "../supabase.php";
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $q = $_GET['q'] ?? '';
        $resp = sb_get("libros?select=*,autores(*)&titulo=ilike.%$q%");
        echo json_encode($resp);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $resp = sb_post("libros", $data);
        echo json_encode($resp);
        break;

    case 'PATCH':
        $id = intval($_GET['id']);
        $data = json_decode(file_get_contents('php://input'), true);
        $resp = sb_patch("libros?id=eq.$id", $data);
        echo json_encode($resp);
        break;

    case 'DELETE':
        $id = intval($_GET['id']);
        $resp = sb_delete("libros?id=eq.$id");
        echo json_encode($resp);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}
