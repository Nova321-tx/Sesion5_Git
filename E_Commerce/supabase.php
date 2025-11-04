<?php
// supabase.php — CONFIGURACIÓN CENTRAL (editar con tus claves)
define('SUPABASE_URL', 'https://phmnvvuohcsqyttsnevd.supabase.co');
define('SUPABASE_API_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBobW52dnVvaGNzcXl0dHNuZXZkIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA0MDQxOTQsImV4cCI6MjA3NTk4MDE5NH0._rJHUnp1wEm0D7tcLwUXfIltVLAyxp0rxkpvhEvBs-M');
define('SUPABASE_SERVICE_ROLE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBobW52dnVvaGNzcXl0dHNuZXZkIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2MDQwNDE5NCwiZXhwIjoyMDc1OTgwMTk0fQ.wteBLIvYdEcKdCr0Ag9V5U0MX3VunOu5Ouyw-I2aNto');
define('SUPABASE_STORAGE_BUCKET', 'imagenes');

// función reutilizable
function supabaseRequest($path, $method = 'GET', $body = null, $useServiceKey = false, $extraHeaders = []) {
    $url = rtrim(SUPABASE_URL, '/') . $path;
    $headers = [
        'apikey: ' . SUPABASE_API_KEY,
        'Authorization: Bearer ' . ($useServiceKey ? SUPABASE_SERVICE_ROLE_KEY : SUPABASE_API_KEY),
    ];
    $headers = array_merge($headers, $extraHeaders);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ($body !== null) {
        // si body es array u object, asumimos JSON
        if (is_array($body) || is_object($body)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, ['Content-Type: application/json']));
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
    }
    $resp = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException("Curl error: $err");
    }
    curl_close($ch);
    return ['status' => $status, 'body' => $resp];
}
