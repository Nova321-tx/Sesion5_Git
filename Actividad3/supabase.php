<?php
define("SUPABASE_URL", "https://phmnvvuohcsqyttsnevd.supabase.co");
define("SUPABASE_KEY", "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBobW52dnVvaGNzcXl0dHNuZXZkIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA0MDQxOTQsImV4cCI6MjA3NTk4MDE5NH0._rJHUnp1wEm0D7tcLwUXfIltVLAyxp0rxkpvhEvBs-M");

function supabaseRequest($table, $method = 'GET', $data = null, $id = null) {
    $url = SUPABASE_URL . "/rest/v1/" . $table;
    if ($id) $url .= "?id=eq." . $id;
    
    $opts = [
        "http" => [
            "method" => $method,
            "header" => "apikey: " . SUPABASE_KEY . "\r\n" .
                        "Authorization: Bearer " . SUPABASE_KEY . "\r\n" .
                        "Content-Type: application/json\r\n" .
                        "Prefer: return=representation\r\n"
        ]
    ];
    
    if ($data) {
        $opts["http"]["content"] = json_encode($data);
    }

    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);
    return [
        "body" => $response ? json_decode($response, true) : [],
        "status" => $http_response_header[0] ?? "Error"
    ];
}
?>
