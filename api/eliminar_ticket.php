<?php
// api/eliminar_ticket.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('atencion');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || !is_numeric($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de ticket inválido']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar que el ticket existe, pertenece al usuario y está pendiente
    $check_query = "SELECT estado FROM tickets WHERE id = :id AND usuario_creador = :usuario_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':id', $input['id']);
    $check_stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
    $check_stmt->execute();
    
    $ticket = $check_stmt->fetch();
    
    if (!$ticket) {
        echo json_encode(['success' => false, 'message' => 'Ticket no encontrado']);
        exit();
    }
    
    if ($ticket['estado'] !== 'pendiente') {
        echo json_encode(['success' => false, 'message' => 'No se puede eliminar un ticket que ya fue pagado']);
        exit();
    }
    
    // Eliminar el ticket
    $query = "DELETE FROM tickets WHERE id = :id AND usuario_creador = :usuario_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Ticket eliminado exitosamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el ticket']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>