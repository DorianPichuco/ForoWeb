<?php
// cerrar-sesion-doria.php
session_start();
session_unset();
session_destroy();
header("Location: login-doria.php");
exit;
?>
