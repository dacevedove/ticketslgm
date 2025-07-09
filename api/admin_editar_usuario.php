<?php
// api/admin_editar_usuario.php
require_once '../config/database.php';

header('Content-Type: application/json');

verificarAutenticacion();
verificarRol('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

// Validar datos requeridos
$required_fields = ['id', 'usuario', 'nombre', 'rol'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "El campo {$field} es requerido"]);
        exit();
    }
}

// Validar rol
$roles_validos = ['admin', 'atencion', 'cajero'];
if (!in_array($_POST['rol'], $roles_validos)) {
    echo json_encode(['success' => false, 'message' => 'Rol inválido']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar que el usuario existe
    $check_query = "SELECT id, usuario FROM usuarios WHERE id = :id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':id', $_POST['id']);
    $check_stmt->execute();
    $usuario_existente = $check_stmt->fetch();
    
    if (!$usuario_existente) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        exit();
    }
    
    // Verificar que el nombre de usuario no esté en uso por otro usuario
    if ($usuario_existente['usuario'] !== $_POST['usuario']) {
        $check_username_query = "SELECT id FROM usuarios WHERE usuario = :usuario AND id != :id";
        $check_username_stmt = $db->prepare($check_username_query);
        $check_username_stmt->bindParam(':usuario', $_POST['usuario']);
        $check_username_stmt->bindParam(':id', $_POST['id']);
        $check_username_stmt->execute();
        
        if ($check_username_stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'El usuario ya existe']);
            exit();
        }
    }
    
    // Construir la consulta de actualización
    $query = "UPDATE usuarios SET 
                usuario = :usuario,
                nombre = :nombre,
                rol = :rol,
                activo = :activo";
    
    $params = [
        ':usuario' => $_POST['usuario'],
        ':nombre' => $_POST['nombre'],
        ':rol' => $_POST['rol'],
        ':activo' => isset($_POST['activo']) ? (int)$_POST['activo'] : 1,
        ':id' => $_POST['id']
    ];
    
    // Si se proporciona una nueva contraseña, incluirla
    if (!empty($_POST['password'])) {
        if (strlen($_POST['password']) < 6) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
            exit();
        }
        
        $query .= ", password = :password";
        $params[':password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    
    $query .= " WHERE id = :id";
    
    $stmt = $db->prepare($query);
    
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value);
    }
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>