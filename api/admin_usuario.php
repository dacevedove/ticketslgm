<?php
// api/admin_usuario.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('admin');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de usuario inválido']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id, usuario, nombre, rol, activo FROM usuarios WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $usuario = $stmt->fetch();
    
    if ($usuario) {
        $usuario['activo'] = (bool)$usuario['activo'];
        echo json_encode(['success' => true, 'usuario' => $usuario]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>