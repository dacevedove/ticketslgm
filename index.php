<?php
// index.php
require_once 'config/database.php';

session_start();

// Si el usuario ya está autenticado, redirigir según su rol
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['rol'] === 'atencion') {
        header('Location: atencion.php');
    } else {
        header('Location: cajero.php');
    }
    exit();
}

// Si no está autenticado, redirigir al login
header('Location: login.php');
exit();
?>