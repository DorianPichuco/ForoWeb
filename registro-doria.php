<?php
// registro-doria.php
session_start();
require 'conexion-doria.php';

$errores = [];
$exito = "";

// Función para validar contraseña segura
function validarPassword($pass) {
    return preg_match('/[A-Z]/', $pass) &&   // Mayúscula
           preg_match('/[a-z]/', $pass) &&   // Minúscula
           preg_match('/[0-9]/', $pass) &&   // Número
           preg_match('/[\W]/', $pass) &&    // Caracter especial
           strlen($pass) >= 8;               // Longitud mínima
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $sexo   = trim($_POST['sexo'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $pass   = trim($_POST['password'] ?? '');
    $conf   = trim($_POST['confirmar'] ?? '');

    if ($nombre === '') $errores[] = "El nombre es obligatorio.";
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo inválido.";
    if ($sexo === '') $errores[] = "Debes seleccionar un sexo.";
    if ($ciudad === '') $errores[] = "La ciudad es obligatoria.";
    if (!validarPassword($pass)) {
        $errores[] = "La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un caracter especial.";
    }
    if ($pass !== $conf) $errores[] = "Las contraseñas no coinciden.";

    if (empty($errores)) {

        // Verificar si el correo ya existe
        $sql = "SELECT id FROM usuarios WHERE correo = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errores[] = "Este correo ya está registrado.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nombre, correo, sexo, ciudad, password) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $nombre, $correo, $sexo, $ciudad, $hash);

            if (mysqli_stmt_execute($stmt)) {
                $exito = "Registro exitoso. Ya puedes iniciar sesión.";
            } else {
                $errores[] = "Error al registrar: " . mysqli_error($conn);
            }
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Doria</title>
    <link rel="stylesheet" href="estilos-doria.css">
</head>
<body>
<div class="page-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">Crear cuenta</h1>
        <p class="auth-subtitle">Regístrate para acceder al sistema Doria</p>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errores as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($exito !== ""): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($exito); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="registro-doria.php" class="auth-form">

            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Ej: Ana López">
            </div>

            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" required placeholder="correo@ejemplo.com">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo" required>
                        <option value="">Seleccione...</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ciudad">Ciudad de origen</label>
                    <input type="text" id="ciudad" name="ciudad" required placeholder="Ej: Quito">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Contraseña segura">
                <small class="help-text">
                    Mínimo 8 caracteres, incluir mayúsculas, minúsculas, números y símbolo.
                </small>
            </div>

            <div class="form-group">
                <label for="confirmar">Confirmar contraseña</label>
                <input type="password" id="confirmar" name="confirmar" required placeholder="Repite la contraseña">
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                Registrarse
            </button>
        </form>

        <p class="auth-footer">
            ¿Ya tienes cuenta?
            <a href="login-doria.php">Inicia sesión aquí</a>
        </p>
    </div>
</div>
</body>
</html>
