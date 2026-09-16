<?php
// Configuración de la base de datos
$host     = 'localhost';
$dbname   = 'tienda_promociones';
$username = 'root';
$password = ''; 

try {
    // Creación de la conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retornar arreglos asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Usar prepared statements reales
    ]);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>