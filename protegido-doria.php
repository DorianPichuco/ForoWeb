<?php
// protegido-doria.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login-doria.php");
    exit;
}

$nombre = $_SESSION['user_nombre'];
$sexo   = $_SESSION['user_sexo'];
$ciudad = $_SESSION['user_ciudad'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - Doria</title>
    <link rel="stylesheet" href="estilos-doria.css">
</head>
<body>
<div class="page-wrapper">
    <div class="dashboard-card">
        <div class="dashboard-header">
            <h1>Hola, <?php echo htmlspecialchars($nombre); ?> 👋</h1>
            <p class="dashboard-subtitle">
                Bienvenido a tu panel personal del sistema <strong>Doria</strong>.
            </p>
        </div>

        <div class="dashboard-info">
            <div class="info-item">
                <span class="info-label">Sexo</span>
                <span class="info-value"><?php echo htmlspecialchars($sexo); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Ciudad de origen</span>
                <span class="info-value"><?php echo htmlspecialchars($ciudad); ?></span>
            </div>
        </div>

        <div class="dashboard-actions">
            <a href="cerrar-sesion-doria.php" class="btn btn-outline">Cerrar sesión</a>
        </div>
    </div>
</div>
</body>
</html>
