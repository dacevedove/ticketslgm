<?php
// test_database.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test de Configuración - Sistema de Tickets</h1>";
echo "<hr>";

// Test 1: Verificar archivo de configuración
echo "<h2>1. Verificando archivo de configuración...</h2>";
if (file_exists('config/database.php')) {
    echo "✅ Archivo config/database.php encontrado<br>";
    require_once 'config/database.php';
} else {
    echo "❌ Archivo config/database.php NO encontrado<br>";
    echo "➡️ Asegúrate de que esté en la carpeta config/<br><br>";
    exit();
}

// Test 2: Verificar conexión a base de datos
echo "<h2>2. Probando conexión a base de datos...</h2>";
try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ Conexión a base de datos exitosa<br>";
        echo "ℹ️ Host: " . (new ReflectionClass($database))->getProperty('host')->getValue($database) . "<br>";
        echo "ℹ️ Base de datos: " . (new ReflectionClass($database))->getProperty('db_name')->getValue($database) . "<br>";
    } else {
        echo "❌ Error al conectar a la base de datos<br>";
        exit();
    }
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
    echo "➡️ Verifica las credenciales en config/database.php<br><br>";
    exit();
}

// Test 3: Verificar existencia de tablas
echo "<h2>3. Verificando estructura de base de datos...</h2>";

$tablas_requeridas = ['usuarios', 'tickets'];
foreach ($tablas_requeridas as $tabla) {
    try {
        $query = "SHOW TABLES LIKE '$tabla'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result) {
            echo "✅ Tabla '$tabla' existe<br>";
        } else {
            echo "❌ Tabla '$tabla' NO existe<br>";
            echo "➡️ Ejecuta el script SQL de creación de tablas<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error verificando tabla '$tabla': " . $e->getMessage() . "<br>";
    }
}

// Test 4: Verificar usuarios de prueba
echo "<h2>4. Verificando usuarios de prueba...</h2>";
try {
    $query = "SELECT usuario, nombre, rol, activo FROM usuarios ORDER BY usuario";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $usuarios = $stmt->fetchAll();
    
    if (count($usuarios) > 0) {
        echo "✅ Usuarios encontrados:<br>";
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Usuario</th><th>Nombre</th><th>Rol</th><th>Activo</th></tr>";
        
        foreach ($usuarios as $usuario) {
            $activo_texto = $usuario['activo'] ? 'Sí' : 'No';
            echo "<tr>";
            echo "<td>{$usuario['usuario']}</td>";
            echo "<td>{$usuario['nombre']}</td>";
            echo "<td>{$usuario['rol']}</td>";
            echo "<td>{$activo_texto}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ No se encontraron usuarios<br>";
        echo "➡️ Ejecuta los INSERT del script SQL para crear usuarios de prueba<br>";
    }
} catch (Exception $e) {
    echo "❌ Error verificando usuarios: " . $e->getMessage() . "<br>";
}

// Test 5: Verificar contraseñas
echo "<h2>5. Probando autenticación de usuarios...</h2>";
$usuarios_prueba = [
    ['usuario' => 'atencion1', 'password' => 'password123'],
    ['usuario' => 'atencion2', 'password' => 'password123'],
    ['usuario' => 'cajero1', 'password' => 'password123'],
    ['usuario' => 'cajero2', 'password' => 'password123']
];

foreach ($usuarios_prueba as $credencial) {
    try {
        $query = "SELECT id, usuario, password, nombre, rol, activo FROM usuarios WHERE usuario = :usuario";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':usuario', $credencial['usuario']);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if ($user) {
            if ($user['activo']) {
                if (password_verify($credencial['password'], $user['password'])) {
                    echo "✅ {$credencial['usuario']}: Autenticación exitosa<br>";
                } else {
                    echo "❌ {$credencial['usuario']}: Contraseña incorrecta<br>";
                    echo "ℹ️ Hash almacenado: " . substr($user['password'], 0, 30) . "...<br>";
                    echo "➡️ Ejecuta el comando para actualizar contraseñas (ver abajo)<br>";
                }
            } else {
                echo "⚠️ {$credencial['usuario']}: Usuario inactivo<br>";
            }
        } else {
            echo "❌ {$credencial['usuario']}: Usuario no encontrado<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error probando {$credencial['usuario']}: " . $e->getMessage() . "<br>";
    }
}

// Test 6: Verificar estructura de tabla tickets
echo "<h2>6. Verificando estructura de tabla tickets...</h2>";
try {
    $query = "DESCRIBE tickets";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $columnas = $stmt->fetchAll();
    
    if (count($columnas) > 0) {
        echo "✅ Estructura de tabla tickets:<br>";
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Default</th></tr>";
        
        foreach ($columnas as $columna) {
            echo "<tr>";
            echo "<td>{$columna['Field']}</td>";
            echo "<td>{$columna['Type']}</td>";
            echo "<td>{$columna['Null']}</td>";
            echo "<td>{$columna['Key']}</td>";
            echo "<td>{$columna['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "❌ Error verificando estructura tickets: " . $e->getMessage() . "<br>";
}

// Test 7: Verificar archivos del sistema
echo "<h2>7. Verificando archivos del sistema...</h2>";
$archivos_requeridos = [
    'login.php',
    'login_process.php',
    'atencion.php',
    'cajero.php',
    'logout.php',
    'api/crear_ticket.php',
    'api/obtener_tickets.php',
    'api/obtener_tickets_cajero.php',
    'api/obtener_ticket.php',
    'api/editar_ticket.php',
    'api/eliminar_ticket.php',
    'api/marcar_pagado.php'
];

foreach ($archivos_requeridos as $archivo) {
    if (file_exists($archivo)) {
        echo "✅ $archivo<br>";
    } else {
        echo "❌ $archivo<br>";
    }
}

// Comandos de reparación
echo "<h2>8. Comandos de reparación (si es necesario)</h2>";
echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h3>Para arreglar contraseñas:</h3>";
echo "<pre>";
echo "UPDATE usuarios SET password = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE usuario IN ('atencion1', 'atencion2', 'cajero1', 'cajero2');";
echo "</pre>";

echo "<h3>Para crear usuario de prueba manual:</h3>";
echo "<pre>";
echo "INSERT INTO usuarios (usuario, password, nombre, rol) VALUES ";
echo "('test', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuario Test', 'atencion');";
echo "</pre>";

echo "<h3>Para verificar manualmente:</h3>";
echo "<pre>";
echo "SELECT usuario, nombre, rol, activo FROM usuarios;";
echo "SELECT COUNT(*) as total_tickets FROM tickets;";
echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<h2>Resultado del Test</h2>";
echo "<p><strong>Si todos los elementos muestran ✅, el sistema está listo para usar.</strong></p>";
echo "<p><strong>Si hay elementos con ❌, sigue las instrucciones ➡️ para corregirlos.</strong></p>";
echo "<br>";
echo "<p>Una vez que todo esté en ✅, elimina este archivo (test_database.php) por seguridad.</p>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
}

h1, h2 {
    color: #333;
}

table {
    background: white;
    font-size: 14px;
}

th {
    background: #667eea;
    color: white;
}

pre {
    background: white;
    padding: 10px;
    border-left: 4px solid #667eea;
    overflow-x: auto;
}

hr {
    margin: 30px 0;
    border: none;
    border-top: 2px solid #667eea;
}
</style>