<?php
// fix_passwords.php
// Script simple para arreglar las contraseñas

// Mostrar errores para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><title>Arreglador de Contraseñas</title></head><body>";
echo "<h1>Arreglador de Contraseñas - Sistema Tickets</h1>";

// Paso 1: Verificar que podemos generar hashes
echo "<h2>Paso 1: Generando hash de prueba...</h2>";

$password = 'password123';
echo "Contraseña a usar: <strong>$password</strong><br>";

// Intentar generar hash
try {
    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    echo "✅ Hash generado exitosamente<br>";
    echo "Hash: <code style='background:#f0f0f0;padding:5px;'>$new_hash</code><br>";
    
    // Verificar que el hash funciona
    if (password_verify($password, $new_hash)) {
        echo "✅ Verificación del hash: EXITOSA<br>";
    } else {
        echo "❌ Verificación del hash: FALLÓ<br>";
        echo "ERROR: Tu servidor PHP tiene problemas con password_hash()<br>";
        exit();
    }
    
} catch (Exception $e) {
    echo "❌ Error generando hash: " . $e->getMessage() . "<br>";
    exit();
}

echo "<hr>";

// Paso 2: Conectar a la base de datos
echo "<h2>Paso 2: Conectando a la base de datos...</h2>";

// Verificar que existe el archivo de configuración
if (!file_exists('config/database.php')) {
    echo "❌ No se encuentra config/database.php<br>";
    echo "Asegúrate de que el archivo esté en la carpeta correcta.<br>";
    exit();
}

// Incluir configuración
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    echo "✅ Conexión a base de datos exitosa<br>";
} catch (Exception $e) {
    echo "❌ Error conectando a BD: " . $e->getMessage() . "<br>";
    echo "Verifica las credenciales en config/database.php<br>";
    exit();
}

echo "<hr>";

// Paso 3: Actualizar contraseñas
echo "<h2>Paso 3: Actualizando contraseñas...</h2>";

$usuarios_actualizar = ['atencion1', 'atencion2', 'cajero1', 'cajero2'];

foreach ($usuarios_actualizar as $usuario) {
    try {
        // Actualizar contraseña
        $query = "UPDATE usuarios SET password = ? WHERE usuario = ?";
        $stmt = $db->prepare($query);
        $result = $stmt->execute([$new_hash, $usuario]);
        
        if ($result) {
            echo "✅ Usuario <strong>$usuario</strong>: Contraseña actualizada<br>";
            
            // Verificar inmediatamente
            $check_query = "SELECT password FROM usuarios WHERE usuario = ?";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->execute([$usuario]);
            $stored = $check_stmt->fetch();
            
            if ($stored && password_verify($password, $stored['password'])) {
                echo "&nbsp;&nbsp;&nbsp;✅ Verificación: OK<br>";
            } else {
                echo "&nbsp;&nbsp;&nbsp;❌ Verificación: FALLÓ<br>";
            }
            
        } else {
            echo "❌ Usuario <strong>$usuario</strong>: Error al actualizar<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Usuario <strong>$usuario</strong>: " . $e->getMessage() . "<br>";
    }
}

echo "<hr>";

// Paso 4: Prueba final
echo "<h2>Paso 4: Prueba final de login...</h2>";

try {
    $test_user = 'atencion1';
    $test_pass = 'password123';
    
    $query = "SELECT id, usuario, password, nombre, rol, activo FROM usuarios WHERE usuario = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$test_user]);
    $user = $stmt->fetch();
    
    if ($user && $user['activo'] && password_verify($test_pass, $user['password'])) {
        echo "🎉 <strong style='color:green;'>¡ÉXITO! El login funcionará correctamente</strong><br>";
        echo "Usuario: $test_user<br>";
        echo "Contraseña: $test_pass<br>";
        echo "Rol: {$user['rol']}<br>";
        
        echo "<div style='background:#d4edda;border:1px solid #c3e6cb;padding:15px;margin:20px 0;border-radius:5px;'>";
        echo "<h3>✅ ¡Sistema Listo!</h3>";
        echo "<p>Ahora puedes:</p>";
        echo "<ol>";
        echo "<li>Ir a <a href='login.php'>login.php</a></li>";
        echo "<li>Usar: <strong>atencion1</strong> / <strong>password123</strong></li>";
        echo "<li>O usar: <strong>cajero1</strong> / <strong>password123</strong></li>";
        echo "</ol>";
        echo "<p><strong>Importante:</strong> Elimina este archivo (fix_passwords.php) por seguridad.</p>";
        echo "</div>";
        
    } else {
        echo "❌ La prueba de login falló<br>";
        if (!$user) {
            echo "Usuario no encontrado<br>";
        } elseif (!$user['activo']) {
            echo "Usuario inactivo<br>";
        } else {
            echo "Contraseña no coincide<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error en prueba final: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<p><em>Script completado. Si ves el mensaje de ÉXITO arriba, el problema está solucionado.</em></p>";
echo "</body></html>";
?>