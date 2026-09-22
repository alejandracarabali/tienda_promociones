<?php
$host = 'sqlXXX.infinityfree.com'; // Sustituye por tu MySQL Host Name
$user = 'if0_38123456';             // Sustituye por tu MySQL User Name
$pass = 'TU_CONTRASEÑA';            // Sustituye por tu contraseña
$dbname = 'if0_38123456_tienda';   // Sustituye por tu Database Name

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
}
?>