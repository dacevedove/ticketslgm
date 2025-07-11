<?php
// api/crear_ticket.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('atencion');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
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

// Validar montos
if (!is_numeric($_POST['montoBs']) || !is_numeric($_POST['montoUsd']) || !is_numeric($_POST['tasaDia'])) {
    echo json_encode(['success' => false, 'message' => 'Los montos deben ser números válidos']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO tickets (
        tasa_dia, fecha_emision, paciente, cedula, historia, numero_historia, 
        monto_bs, monto_usd, factura, procedimiento, usuario_creador
    ) VALUES (
        :tasa_dia, :fecha_emision, :paciente, :cedula, :historia, :numero_historia,
        :monto_bs, :monto_usd, :factura, :procedimiento, :usuario_creador
    )";
    
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
    $stmt->bindParam(':usuario_creador', $_SESSION['usuario_id']);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Ticket creado exitosamente',
            'ticket_id' => $db->lastInsertId()
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el ticket']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>