<?php
// api/admin_crear_usuario.php
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
$required_fields = ['usuario', 'nombre', 'rol', 'password'];
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

// Validar longitud de usuario
if (strlen($_POST['usuario']) < 3) {
    echo json_encode(['success' => false, 'message' => 'El usuario debe tener al menos 3 caracteres']);
    exit();
}

// Validar longitud de contraseña
if (strlen($_POST['password']) < 6) {
    echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar que el usuario no exista
    $check_query = "SELECT id FROM usuarios WHERE usuario = :usuario";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':usuario', $_POST['usuario']);
    $check_stmt->execute();
    
    if ($check_stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'El usuario ya existe']);
        exit();
    }
    
    // Crear el usuario
    $query = "INSERT INTO usuarios (usuario, password, nombre, rol, activo, creado_por) 
              VALUES (:usuario, :password, :nombre, :rol, :activo, :creado_por)";
    
    $stmt = $db->prepare($query);
    
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 1;
    
    $stmt->bindParam(':usuario', $_POST['usuario']);
    $stmt->bindParam(':password', $password_hash);
    $stmt->bindParam(':nombre', $_POST['nombre']);
    $stmt->bindParam(':rol', $_POST['rol']);
    $stmt->bindParam(':activo', $activo);
    $stmt->bindParam(':creado_por', $_SESSION['usuario_id']);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'usuario_id' => $db->lastInsertId()
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el usuario']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>