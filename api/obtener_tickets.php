<?php
// api/obtener_tickets.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('atencion');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT t.*, u.nombre as nombre_creador 
              FROM tickets t 
              JOIN usuarios u ON t.usuario_creador = u.id 
              WHERE t.usuario_creador = :usuario_id 
              ORDER BY t.fecha_creacion DESC";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
    $stmt->execute();
    
    $tickets = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'tickets' => $tickets
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>