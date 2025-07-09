<?php
// config/database.php
class Database {
    private $host = "localhost";
    private $db_name = "cajaboard";
    private $username = "cajalgm";
    private $password = "9uojFIFaqa2bJGXdIMgy";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                  $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Función para verificar si el usuario está autenticado
function verificarAutenticacion() {
    session_start();
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit();
    }
}

// Función para verificar el rol del usuario
function verificarRol($rol_requerido) {
    if ($_SESSION['rol'] !== $rol_requerido) {
        header('Location: index.php');
        exit();
    }
}

// Función para formatear moneda
function formatearMoneda($monto) {
    return number_format($monto, 2, ',', '.');
}

// Función para validar cédula venezolana
function validarCedula($cedula) {
    // Remover espacios y caracteres especiales
    $cedula = preg_replace('/[^0-9]/', '', $cedula);
    
    // Verificar longitud (7-8 dígitos)
    if (strlen($cedula) < 7 || strlen($cedula) > 8) {
        return false;
    }
    
    return true;
}
?>
