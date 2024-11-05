<?php
session_start(); // Iniciar sesión para usar tokens CSRF

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "usuarios_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    error_log("Conexión fallida: " . $e->getMessage());
    die("Ocurrió un error al conectar con la base de datos."); // Mensaje genérico al usuario
}
?>
