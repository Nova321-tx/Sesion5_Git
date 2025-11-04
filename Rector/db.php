<?php
// Conexión a la base de datos centro_apoyo

$host = 'localhost';
$db   = 'centro_apoyo';
$user = 'root';
$pass = ''; // Deja vacío si no tienes contraseña en Laragon
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mostrar errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retornar resultados como arreglos asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva emulación de sentencias preparadas
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
