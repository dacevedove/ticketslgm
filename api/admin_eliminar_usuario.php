<?php
// api/admin_eliminar_usuario.php
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

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar que no sea admin
    $check_query = "SELECT rol FROM usuarios WHERE id = :id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':id', $input['id']);
    $check_stmt->execute();
    $usuario = $check_stmt->fetch();
    
    if (!$usuario) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        exit();
    }
    
    if ($usuario['rol'] === 'admin') {
        echo json_encode(['success' => false, 'message' => 'No se puede eliminar un administrador']);
        exit();
    }
    
    // Verificar si tiene tickets asociados
    $tickets_query = "SELECT COUNT(*) as total FROM tickets WHERE usuario_creador = :id OR usuario_cajero = :id";
    $tickets_stmt = $db->prepare($tickets_query);
    $tickets_stmt->bindParam(':id', $input['id']);
    $tickets_stmt->execute();
    $tickets_count = $tickets_stmt->fetch();
    
    if ($tickets_count['total'] > 0) {
        echo json_encode(['success' => false, 'message' => 'No se puede eliminar el usuario porque tiene tickets asociados']);
        exit();
    }
    
    // Eliminar usuario
    $query = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $input['id']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar usuario']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>