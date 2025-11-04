<?php
// index.php
session_start();

// Si ya hay sesión, enviar al panel principal
if (isset($_SESSION['usuario'])) {
    header("Location: views/dashboard.php");
    exit;
}

// Si no hay sesión, redirigir al login
header("Location: views/login.php");
exit;
?>
