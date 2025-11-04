<?php
// clases/Producto.php
require_once __DIR__ . '/../supabase.php';

class Producto {
    private $endpoint = '/rest/v1/productos';

    public function obtenerTodos($limit = 100) {
        $path = $this->endpoint . "?select=*&order=id.desc&limit=" . intval($limit);
        $r = supabaseRequest($path, 'GET');
        if ($r['status'] >= 400) throw new RuntimeException($r['body']);
        return json_decode($r['body'], true);
    }

    public function insertar($data) {
        if (empty($data['nombre']) || !isset($data['precio'])) {
            throw new InvalidArgumentException('nombre y precio obligatorios');
        }
        $body = [[
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? '',
            'precio' => floatval($data['precio']),
            'stock' => intval($data['stock'] ?? 0),
            'categoria' => $data['categoria'] ?? '',
            'imagen_url' => $data['imagen_url'] ?? '',
            'created_at' => date('c'),
            'updated_at' => date('c')
        ]];
        $r = supabaseRequest($this->endpoint, 'POST', $body, true, ['Prefer: return=representation']);
        if ($r['status'] >= 400) throw new RuntimeException($r['body']);
        return json_decode($r['body'], true)[0];
    }

    public function buscar($q, $limit = 10) {
        $qEnc = rawurlencode($q);
        $path = $this->endpoint . "?select=*&or=(nombre.ilike.*$qEnc*,categoria.ilike.*$qEnc*)&limit=" . intval($limit);
        $r = supabaseRequest($path, 'GET');
        if ($r['status'] >= 400) throw new RuntimeException($r['body']);
        return json_decode($r['body'], true);
    }

    public function actualizar($id, $data) {
        $path = $this->endpoint . "?id=eq." . intval($id);
        $data['updated_at'] = date('c');
        $r = supabaseRequest($path, 'PATCH', $data, true, ['Prefer: return=representation']);
        if ($r['status'] >= 400) throw new RuntimeException($r['body']);
        return json_decode($r['body'], true)[0] ?? null;
    }

    public function eliminar($id) {
        $path = $this->endpoint . "?id=eq." . intval($id);
        $r = supabaseRequest($path, 'DELETE', null, true);
        if ($r['status'] >= 400) throw new RuntimeException($r['body']);
        return true;
    }

    // subir imagen con Service Role (binary PUT)
    public function uploadImage($tmpPath, $destPath) {
        $urlPath = '/storage/v1/object/' . SUPABASE_STORAGE_BUCKET . '/' . $destPath;
        $url = rtrim(SUPABASE_URL, '/') . $urlPath;
        $fp = fopen($tmpPath, 'rb');
        $size = filesize($tmpPath);

        $ch = curl_init($url);
        $headers = [
            'Authorization: Bearer ' . SUPABASE_SERVICE_ROLE_KEY,
            'apiKey: ' . SUPABASE_SERVICE_ROLE_KEY,
            'Content-Type: application/octet-stream',
            'Content-Length: ' . $size
        ];
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, $size);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $resp = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        fclose($fp);
        curl_close($ch);
        if ($status >= 400) throw new RuntimeException("Error upload: $resp");
        // public URL if bucket public:
        return rtrim(SUPABASE_URL, '/') . "/storage/v1/object/public/" . SUPABASE_STORAGE_BUCKET . "/" . $destPath;
    }
}
?>