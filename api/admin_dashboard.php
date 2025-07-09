<?php
// api/admin_dashboard.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('admin');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Estadísticas generales
    $stats_query = "SELECT 
        (SELECT COUNT(*) FROM usuarios WHERE activo = 1) as total_usuarios,
        (SELECT COUNT(*) FROM tickets) as total_tickets,
        (SELECT COUNT(*) FROM tickets WHERE estado = 'pendiente') as tickets_pendientes,
        (SELECT COUNT(*) FROM tickets WHERE estado = 'pagado') as tickets_pagados";
    
    $stats_stmt = $db->prepare($stats_query);
    $stats_stmt->execute();
    $stats = $stats_stmt->fetch();
    
    // Actividad reciente de usuarios
    $actividad_query = "SELECT 
        u.id,
        u.nombre,
        u.ultimo_acceso,
        u.activo,
        COALESCE(t.tickets_hoy, 0) as tickets_hoy
    FROM usuarios u
    LEFT JOIN (
        SELECT usuario_creador, COUNT(*) as tickets_hoy
        FROM tickets 
        WHERE DATE(fecha_creacion) = CURDATE()
        GROUP BY usuario_creador
    ) t ON u.id = t.usuario_creador
    WHERE u.rol IN ('atencion', 'cajero')
    ORDER BY u.ultimo_acceso DESC
    LIMIT 10";
    
    $actividad_stmt = $db->prepare($actividad_query);
    $actividad_stmt->execute();
    $actividad = $actividad_stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'actividad' => $actividad
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>