<?php
$SUPABASE_URL = getenv('SUPABASE_URL') ?: 'https://ixnrdkafymgrkkzttflp.supabase.co';
$SUPABASE_KEY = getenv('SUPABASE_KEY') ?: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Iml4bnJka2FmeW1ncmtrenR0ZmxwIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjE4ODI0MjIsImV4cCI6MjA3NzQ1ODQyMn0.Y_1LmIoLGLtb-zoxXt7FEVxNZbPaTyaSTCvoY0aF0Us';

// Tiempo de espera en segundos para cURL
$SB_CURL_TIMEOUT = 15;

/**
 * Realiza una petición HTTP a Supabase REST.
 *
 * @param string $endpoint Ruta después de /rest/v1/, por ejemplo "libros?select=*,autores(*)"
 * @param string $method GET|POST|PATCH|DELETE
 * @param array|null $data Datos a enviar (se convierten a JSON)
 * @param array $extra_headers Array con headers adicionales (ej: ['Prefer: return=representation'])
 * @return array Associative array: ['success'=>bool, 'status'=>int, 'body'=>mixed, 'error'=>string|null]
 */
function sb_request(string $endpoint, string $method = 'GET', $data = null, array $extra_headers = []) {
    global $SUPABASE_URL, $SUPABASE_KEY, $SB_CURL_TIMEOUT;

    $url = rtrim($SUPABASE_URL, '/') . '/rest/v1/' . ltrim($endpoint, '/');

    $ch = curl_init();

    $headers = [
        'Content-Type: application/json',
        "apikey: {$SUPABASE_KEY}",
        "Authorization: Bearer {$SUPABASE_KEY}",
        // Indica que queremos respuestas en JSON
        'Accept: application/json'
    ];

    // Agregar headers adicionales (por ejemplo Prefer)
    foreach ($extra_headers as $h) {
        $headers[] = $h;
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $SB_CURL_TIMEOUT);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

    if (!in_array(strtoupper($method), ['GET', 'HEAD']) && !is_null($data)) {
        $payload = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }

    // Ejecutar
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err = curl_error($ch);
    curl_close($ch);

    $decoded = null;
    if ($response !== false && $response !== '') {
        // Intentamos decodificar JSON, si falla dejamos el texto bruto
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $decoded = $response;
        }
    }

    $success = ($http_status >= 200 && $http_status < 300);

    return [
        'success' => $success,
        'status'  => $http_status,
        'body'    => $decoded,
        'error'   => $curl_err ?: ($success ? null : "HTTP {$http_status}")
    ];
}

/* ---------- Funciones auxiliares más legibles ---------- */

function sb_get(string $endpoint, array $extra_headers = []) {
    return sb_request($endpoint, 'GET', null, $extra_headers);
}

function sb_post(string $endpoint, $data = [], array $extra_headers = []) {
    // Por defecto pedimos que retorne la representación creada (útil para obtener id)
    $default = ['Prefer: return=representation'];
    return sb_request($endpoint, 'POST', $data, array_merge($default, $extra_headers));
}

function sb_patch(string $endpoint, $data = [], array $extra_headers = []) {
    // return=representation para recibir el objeto modificado
    $default = ['Prefer: return=representation'];
    return sb_request($endpoint, 'PATCH', $data, array_merge($default, $extra_headers));
}

function sb_delete(string $endpoint, array $extra_headers = []) {
    // Por defecto no devolvemos body, pero puedes pedir return=representation si quieres
    return sb_request($endpoint, 'DELETE', null, $extra_headers);
}

/* ---------- Funciones utilitarias para manejo de errores/respuesta ---------- */

/**
 * Extrae y devuelve el primer mensaje de error si procede.
 */
function sb_get_error_message(array $resp) {
    if (isset($resp['error']) && $resp['error']) return $resp['error'];
    if (isset($resp['body']) && is_array($resp['body']) && isset($resp['body']['message'])) {
        return $resp['body']['message'];
    }
    return null;
}
