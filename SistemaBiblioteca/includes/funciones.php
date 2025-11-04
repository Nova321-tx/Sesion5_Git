<?php
// includes/funciones.php

require_once "../supabase.php";

/**
 * Sanitiza un valor para mostrar en HTML
 */
function h($string) {
    return htmlspecialchars($string);
}

/**
 * Obtiene mensaje de error de la respuesta de Supabase
 */
function getErrorMessage($resp) {
    if (isset($resp['error']) && $resp['error']) return $resp['error'];
    if (isset($resp['body']['message'])) return $resp['body']['message'];
    return null;
}

/**
 * Función para hacer redirect seguro
 */
function redirect($url) {
    header("Location: $url");
    exit;
}
