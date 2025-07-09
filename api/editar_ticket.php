<?php
// api/editar_ticket.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('atencion');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de ticket inválido']);
    exit();
}

// Validar datos requeridos
$required_fields = ['tasaDia', 'fechaEmision', 'paciente', 'cedula', 'montoBs', 'montoUsd'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "El campo {$field} es requerido"]);
        exit();
    }
}

// Validar cédula
if (!validarCedula($_POST['cedula'])) {
    echo json_encode(['success' => false, 'message' => 'Número de cédula inválido']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar que el ticket existe, pertenece al usuario y está pendiente
    $check_query = "SELECT estado FROM tickets WHERE id = :id AND usuario_creador = :usuario_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':id', $_POST['id']);
    $check_stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
    $check_stmt->execute();
    
    $ticket = $check_stmt->fetch();
    
    if (!$ticket) {
        echo json_encode(['success' => false, 'message' => 'Ticket no encontrado']);
        exit();
    }
    
    if ($ticket['estado'] !== 'pendiente') {
        echo json_encode(['success' => false, 'message' => 'No se puede editar un ticket que ya fue pagado']);
        exit();
    }
    
    // Actualizar el ticket
    $query = "UPDATE tickets SET 
                tasa_dia = :tasa_dia,
                fecha_emision = :fecha_emision,
                paciente = :paciente,
                cedula = :cedula,
                historia = :historia,
                numero_historia = :numero_historia,
                monto_bs = :monto_bs,
                monto_usd = :monto_usd,
                factura = :factura,
                procedimiento = :procedimiento
              WHERE id = :id AND usuario_creador = :usuario_id";
    
    $stmt = $db->prepare($query);
    
    // Limpiar cédula (solo números)
    $cedula = preg_replace('/[^0-9]/', '', $_POST['cedula']);
    
    $stmt->bindParam(':tasa_dia', $_POST['tasaDia']);
    $stmt->bindParam(':fecha_emision', $_POST['fechaEmision']);
    $stmt->bindParam(':paciente', $_POST['paciente']);
    $stmt->bindParam(':cedula', $cedula);
    $stmt->bindParam(':historia', $_POST['historia']);
    $stmt->bindParam(':numero_historia', $_POST['numeroHistoria']);
    $stmt->bindParam(':monto_bs', $_POST['montoBs']);
    $stmt->bindParam(':monto_usd', $_POST['montoUsd']);
    $stmt->bindParam(':factura', $_POST['factura']);
    $stmt->bindParam(':procedimiento', $_POST['procedimiento']);
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Ticket actualizado exitosamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el ticket']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>