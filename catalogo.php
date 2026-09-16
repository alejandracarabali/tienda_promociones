<?php
session_start();
require_once 'conexion.php';

// Protección de la ruta: Validar si existe la sesión activa del usuario
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener la información del usuario en sesión
$nombre_usuario = $_SESSION['usuario_nombre'] ?? 'Usuario';

// Consultar los productos en promoción
try {
    $stmt = $pdo->query("SELECT * FROM productos WHERE destacado = TRUE ORDER BY id DESC");
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al consultar el catálogo: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Promociones</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 220px;
            object-fit: cover;
        }
        .precio-antes {
            text-decoration: line-through;
            color: #888;
        }
        .badge-descuento {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">🔥 Tienda Promociones</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">¡Hola, <strong><?= htmlspecialchars($nombre_usuario) ?></strong>!</span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        
        <!-- Header Banner -->
        <div class="p-4 mb-4 bg-white rounded shadow-sm border">
            <h1 class="display-6 fw-bold text-primary mb-1">Catálogo de Productos en Oferta</h1>
            <p class="text-muted mb-0">Explora nuestras mejores ofertas exclusivas para usuarios registrados.</p>
        </div>

        <!-- Grilla de Productos -->
        <?php if (count($productos) > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($productos as $producto): ?>
                    <?php 
                        // Cálculo del porcentaje de descuento
                        $descuento = round((($producto['precio_regular'] - $producto['precio_oferta']) / $producto['precio_regular']) * 100);
                    ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 position-relative">
                            
                            <!-- Insignia de Descuento -->
                            <span class="badge bg-danger badge-descuento fs-6">-<?= $descuento ?>%</span>

                            <img src="<?= htmlspecialchars($producto['imagen_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <p class="card-text text-secondary flex-grow-1"><?= htmlspecialchars($producto['descripcion']) ?></p>
                                
                                <div class="mt-3">
                                    <div class="d-flex align-items-baseline gap-2">
                                        <span class="fs-4 fw-bold text-success">$<?= number_format($producto['precio_oferta'], 2) ?></span>
                                        <span class="precio-antes fs-6">$<?= number_format($producto['precio_regular'], 2) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0 pb-3">
                                <button class="btn btn-primary w-100 fw-semibold">Aprovechar Oferta</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No hay promociones disponibles en este momento.
            </div>
        <?php endif; ?>

    </div>

</body>
</html>