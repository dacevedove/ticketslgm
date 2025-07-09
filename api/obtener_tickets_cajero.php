<?php
// api/obtener_tickets_cajero.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('cajero');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $estado = $_GET['estado'] ?? 'pendientes';
    $cedula = $_GET['cedula'] ?? '';
    
    // Convertir estado del tab a estado de BD
    $estado_bd = ($estado === 'pendientes') ? 'pendiente' : 'pagado';
    
    // Construir consulta base
    $query = "SELECT t.*, 
                     u_creador.nombre as nombre_creador,
                     u_cajero.nombre as nombre_cajero
              FROM tickets t 
              JOIN usuarios u_creador ON t.usuario_creador = u_creador.id 
              LEFT JOIN usuarios u_cajero ON t.usuario_cajero = u_cajero.id 
              WHERE t.estado = :estado";
    
    $params = [':estado' => $estado_bd];
    
    // Agregar filtro por cédula si se proporciona
    if (!empty($cedula)) {
        $cedula_limpia = preg_replace('/[^0-9]/', '', $cedula);
        $query .= " AND t.cedula LIKE :cedula";
        $params[':cedula'] = "%{$cedula_limpia}%";
    }
    
    $query .= " ORDER BY t.fecha_emision DESC, t.fecha_creacion DESC";
    
    $stmt = $db->prepare($query);
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value);
    }
    $stmt->execute();
    
    $tickets = $stmt->fetchAll();
    
    // Obtener conteos para los badges
    $count_query = "SELECT 
                        SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                        SUM(CASE WHEN estado = 'pagado' THEN 1 ELSE 0 END) as pagados
                    FROM tickets";
    
    if (!empty($cedula)) {
        $count_query .= " WHERE cedula LIKE :cedula";
    }
    
    $count_stmt = $db->prepare($count_query);
    if (!empty($cedula)) {
        $count_stmt->bindValue(':cedula', "%{$cedula_limpia}%");
    }
    $count_stmt->execute();
    $counts = $count_stmt->fetch();
    
    echo json_encode([
        'success' => true,
        'tickets' => $tickets,
        'counts' => $counts
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>