<?php
// includes/auth.php
session_start();

/**
 * Verifica si hay usuario logueado.
 */
function verificarLogin() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: ../views/login.php");
        exit;
    }
}

/**
 * Verifica si el usuario es admin.
 */
function verificarAdmin() {
    verificarLogin();
    if ($_SESSION['usuario']['rol'] !== 'admin') {
        header("Location: ../views/dashboard.php");
        exit;
    }
}

/**
 * Inicia sesión guardando datos del usuario en $_SESSION
 */
function iniciarSesion($usuario) {
    $_SESSION['usuario'] = $usuario;
}

/**
 * Cierra la sesión
 */
function cerrarSesion() {
    session_unset();
    session_destroy();
    header("Location: ../views/login.php");
    exit;
}
