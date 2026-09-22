<?php
$host     = 'sql207.infinityfree.com';          // Host exacto de tu captura
$dbname   = 'if0_42936396_tienda_promociones'; // BD exacta de tu captura
$username = 'if0_42936396';                    // Tu usuario de InfinityFree
$password = '949ct6THmM';   // La contraseña de tu cuenta

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>