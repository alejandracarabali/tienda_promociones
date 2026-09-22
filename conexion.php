<?php
// Reemplaza estos datos con los de tu panel MySQL de InfinityFree
$host = 'sqlXXX.infinityfree.com'; // Tu Host Name de MySQL
$dbname = 'if0_42936396_xxx';     // Tu nombre de base de datos completa
$username = 'if0_42936396';        // Tu usuario de FTP / MySQL
$password = 'TU_CONTRASEÑA';       // Tu contraseña de InfinityFree

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>