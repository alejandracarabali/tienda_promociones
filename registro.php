<?php
session_start();
require_once 'conexion.php';

$errores = [];
$exito = '';

// Procesar cuando el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitización e higienización de los datos recibidos
    $numero_documento = trim($_POST['numero_documento'] ?? '');
    $nombre           = trim($_POST['nombre'] ?? '');
    $apellidos        = trim($_POST['apellidos'] ?? '');
    $correo           = trim($_POST['correo'] ?? '');
    $ciudad           = trim($_POST['ciudad'] ?? '');
    $pais             = trim($_POST['pais'] ?? '');
    $password         = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // --- VALIDACIONES ---
    if (empty($numero_documento) || empty($nombre) || empty($apellidos) || empty($correo) || empty($ciudad) || empty($pais) || empty($password)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del correo electrónico no es válido.";
    }

    if ($password !== $password_confirm) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    if (strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    // Si no hay errores de validación inicial, verificar duplicados en BD
    if (empty($errores)) {
        // Validar si el documento o correo ya existen
        $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE numero_documento = :doc OR correo = :correo");
        $stmt_check->execute([':doc' => $numero_documento, ':correo' => $correo]);
        
        if ($stmt_check->fetch()) {
            $errores[] = "El número de documento o el correo ya se encuentran registrados.";
        } else {
            // Cifrar la contraseña
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);

            // Inserción en la base de datos mediante Consulta Preparada
            $sql = "INSERT INTO usuarios (numero_documento, nombre, apellidos, correo, password, ciudad, pais) 
                    VALUES (:numero_documento, :nombre, :apellidos, :correo, :password, :ciudad, :pais)";
            
            $stmt = $pdo->prepare($sql);
            $guardado = $stmt->execute([
                ':numero_documento' => $numero_documento,
                ':nombre'           => $nombre,
                ':apellidos'        => $apellidos,
                ':correo'           => $correo,
                ':password'         => $password_hashed,
                ':ciudad'           => $ciudad,
                ':pais'             => $pais
            ]);

            if ($guardado) {
                $exito = "¡Usuario registrado con éxito! Ya puedes <a href='login.php' class='alert-link'>iniciar sesión</a>.";
            } else {
                $errores[] = "Ocurrió un error al intentar guardar los datos.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- CSS de Bootstrap 5 para un diseño rápido y limpio -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Registro de Usuario</h4>
                </div>
                <div class="card-body p-4">

                    <!-- Alertas de Errores -->
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errores as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Alerta de Éxito -->
                    <?php if ($exito): ?>
                        <div class="alert alert-success">
                            <?= $exito ?>
                        </div>
                    <?php endif; ?>

                    <form action="registro.php" method="POST" novalidate>
                        
                        <div class="mb-3">
                            <label for="numero_documento" class="form-label">Número de Documento</label>
                            <input type="text" class="form-control" id="numero_documento" name="numero_documento" value="<?= htmlspecialchars($_POST['numero_documento'] ?? '') ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?= htmlspecialchars($_POST['ciudad'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="pais" value="<?= htmlspecialchars($_POST['pais'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>

                    </form>
                    
                    <div class="text-center mt-3">
                        <small>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>