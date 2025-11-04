<?php
// clases/Usuario.php
require_once __DIR__ . '/../supabase.php';

class Usuario {
    private $endpoint = '/rest/v1/usuarios';

    public function registrar($nombre, $email, $password, $rol = 'cliente') {
        if (empty($nombre) || empty($email) || empty($password)) {
            throw new InvalidArgumentException('Faltan campos');
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $body = [[
            'nombre' => $nombre,
            'email' => $email,
            'password' => $hash,
            'rol' => $rol,
            'created_at' => date('c')
        ]];
        $r = supabaseRequest($this->endpoint, 'POST', $body, true, ['Prefer: return=representation']);
        if ($r['status'] >= 400) throw new RuntimeException($r['body']);
        return json_decode($r['body'], true)[0];
    }

    public function login($email, $password) {
        $path = $this->endpoint . "?select=*&email=eq." . rawurlencode($email);
        $r = supabaseRequest($path, 'GET');
        if ($r['status'] >= 400) throw new RuntimeException($r['body']);
        $rows = json_decode($r['body'], true);
        if (empty($rows)) return null;
        $user = $rows[0];
        if (!password_verify($password, $user['password'])) return null;
        // remove password before returning
        unset($user['password']);
        return $user;
    }
    public function obtenerTodos() {
    $r = supabaseRequest($this->endpoint . "?select=*", 'GET');
    if ($r['status'] >= 400) throw new RuntimeException($r['body']);
    $rows = json_decode($r['body'], true);
    // No devolvemos las contraseñas
    return array_map(fn($u) => [
        'id' => $u['id'],
        'nombre' => $u['nombre'],
        'email' => $u['email'],
        'rol' => $u['rol'],
        'created_at' => $u['created_at']
    ], $rows);
    }
}
