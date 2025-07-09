<?php
// api/obtener_ticket.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de ticket inválido']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT t.*, 
                     u_creador.nombre as nombre_creador,
                     u_cajero.nombre as nombre_cajero
              FROM tickets t 
              JOIN usuarios u_creador ON t.usuario_creador = u_creador.id 
              LEFT JOIN usuarios u_cajero ON t.usuario_cajero = u_cajero.id 
              WHERE t.id = :id";
    
    // Si es usuario de atención, solo puede ver sus propios tickets
    if ($_SESSION['rol'] === 'atencion') {
        $query .= " AND t.usuario_creador = :usuario_id";
    }
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);
    
    if ($_SESSION['rol'] === 'atencion') {
        $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
    }
    
    $stmt->execute();
    $ticket = $stmt->fetch();
    
    if ($ticket) {
        echo json_encode([
            'success' => true,
            'ticket' => $ticket
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ticket no encontrado']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>