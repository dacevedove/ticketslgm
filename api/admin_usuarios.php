<?php
// api/admin_usuarios.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('admin');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT 
        id, usuario, nombre, rol, activo, fecha_creacion, ultimo_acceso
    FROM usuarios 
    ORDER BY fecha_creacion DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $usuarios = $stmt->fetchAll();
    
    // Convertir activo de 1/0 a boolean
    foreach ($usuarios as &$usuario) {
        $usuario['activo'] = (bool)$usuario['activo'];
    }
    
    echo json_encode([
        'success' => true,
        'usuarios' => $usuarios
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>