<?php
// api/marcar_pagado.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('cajero');

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
    
    // Verificar que el ticket existe y está pendiente
    $check_query = "SELECT estado FROM tickets WHERE id = :id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':id', $input['id']);
    $check_stmt->execute();
    
    $ticket = $check_stmt->fetch();
    
    if (!$ticket) {
        echo json_encode(['success' => false, 'message' => 'Ticket no encontrado']);
        exit();
    }
    
    if ($ticket['estado'] !== 'pendiente') {
        echo json_encode(['success' => false, 'message' => 'Este ticket ya fue marcado como pagado']);
        exit();
    }
    
    // Marcar como pagado
    $query = "UPDATE tickets SET 
                estado = 'pagado',
                fecha_pago = NOW(),
                usuario_cajero = :usuario_cajero
              WHERE id = :id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':usuario_cajero', $_SESSION['usuario_id']);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Ticket marcado como pagado exitosamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al procesar el pago']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>