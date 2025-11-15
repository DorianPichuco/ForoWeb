<?php
// login-doria.php
session_start();
require 'conexion-doria.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo = trim($_POST['correo'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($correo === '' || $password === '') {
        $errores[] = "Debes ingresar correo y contraseña.";
    } else {
        $sql = "SELECT id, nombre, sexo, ciudad, password FROM usuarios WHERE correo = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($resultado);

        if ($usuario && password_verify($password, $usuario["password"])) {
            $_SESSION['user_id']     = $usuario['id'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            $_SESSION['user_sexo']   = $usuario['sexo'];
            $_SESSION['user_ciudad'] = $usuario['ciudad'];

            header("Location: protegido-doria.php");
            exit;
        } else {
            $errores[] = "Correo o contraseña incorrectos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Doria</title>
    <link rel="stylesheet" href="estilos-doria.css">
</head>
<body>
<div class="page-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">Bienvenido de nuevo</h1>
        <p class="auth-subtitle">Ingresa tus credenciales para continuar</p>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errores as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="login-doria.php" class="auth-form">

            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" required placeholder="correo@ejemplo.com">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Tu contraseña">
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                Iniciar sesión
            </button>
        </form>

        <p class="auth-footer">
            ¿Aún no tienes cuenta?
            <a href="registro-doria.php">Regístrate aquí</a>
        </p>
    </div>
</div>
</body>
</html>
