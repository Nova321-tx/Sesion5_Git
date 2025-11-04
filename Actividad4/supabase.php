<?php
// 🔹 Configuración Supabase
const SUPABASE_URL = "https://fbanomxpufqaybvqaajh.supabase.co";  // cambia por el tuyo
const SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZiYW5vbXhwdWZxYXlidnFhYWpoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA5MDYyMTIsImV4cCI6MjA3NjQ4MjIxMn0.aLaY3wynzzens90mm_lnJ_p4r_kVxI1U-xM7K78Eg2E"; // cambia por tu clave API

function supabaseRequest($tabla, $metodo = "GET", $data = null) {
    $url = SUPABASE_URL . "/rest/v1/" . $tabla;

    $headers = [
        "apikey: " . SUPABASE_KEY,
        "Authorization: Bearer " . SUPABASE_KEY,
        "Content-Type: application/json",
        "Prefer: return=representation"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        "status" => $status,
        "body" => json_decode($response, true)
    ];
}
?>
