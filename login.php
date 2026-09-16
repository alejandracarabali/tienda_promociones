<?php
session_start();
require_once 'conexion.php';

// Si el usuario ya está autenticado, redirigir al catálogo
if (isset($_SESSION['usuario_id'])) {
    header("Location: catalogo.php");
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validar campos obligatorios
    if (empty($correo) || empty($password)) {
        $errores[] = "Por favor, ingresa tu correo y contraseña.";
    } else {
        // Consultar el usuario por correo electrónico
        $stmt = $pdo->prepare("SELECT id, nombre, apellidos, password, correo FROM usuarios WHERE correo = :correo");
        $stmt->execute([':correo' => $correo]);
        $usuario = $stmt->fetch();

        // Verificar si existe el usuario y la contraseña coincide
        if ($usuario && password_verify($password, $usuario['password'])) {
            // Guardar datos clave en la sesión
            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellidos'];
            $_SESSION['usuario_correo'] = $usuario['correo'];

            // Redirigir al catálogo
            header("Location: catalogo.php");
            exit;
        } else {
            $errores[] = "Correo electrónico o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0 fw-bold">Iniciar Sesión</h4>
                </div>
                <div class="card-body p-4">

                    <!-- Muestra errores de autenticación -->
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errores as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>" placeholder="ejemplo@correo.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Tu contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Ingresar</button>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">¿No tienes una cuenta aún? <a href="registro.php" class="text-primary fw-bold">Regístrate aquí</a></small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>