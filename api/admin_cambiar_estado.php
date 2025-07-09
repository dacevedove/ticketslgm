<?php
// api/admin_cambiar_estado.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || !is_numeric($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de usuario inválido']);
    exit();
}

if (!isset($input['activo'])) {
    echo json_encode(['success' => false, 'message' => 'Estado requerido']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "UPDATE usuarios SET activo = :activo WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':activo', $input['activo']);
    $stmt->bindParam(':id', $input['id']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Estado actualizado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar estado']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>