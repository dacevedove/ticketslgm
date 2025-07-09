<?php
// login_process.php
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($usuario) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Usuario y contraseña son requeridos']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id, usuario, password, nombre, rol, activo FROM usuarios WHERE usuario = :usuario";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    
    $user = $stmt->fetch();
    
    if ($user && $user['activo'] && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        
        $redirect = 'index.php';
        if ($user['rol'] === 'admin') {
            $redirect = 'admin.php';
        } elseif ($user['rol'] === 'atencion') {
            $redirect = 'atencion.php';
        } else {
            $redirect = 'cajero.php';
        }
        
        // Actualizar último acceso
        $update_query = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':id', $user['id']);
        $update_stmt->execute();
        
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'redirect' => $redirect
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario o contraseña incorrectos'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>