<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'cliente';
$usuario = 'root';
$contraseña = '';

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}

// Funciones CRUD
function crearUsuario($pdo, $nombre, $apellido, $telefono, $correo) {
    try {
        $sql = "INSERT INTO cliente (nombre, apellido, telefono, correo) VALUES (:nombre, :apellido, :telefono, :correo)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        echo "Usuario creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear usuario: " . $e->getMessage();
    }
}

function leerUsuario($pdo, $nombre, $apellido, $telefono, $correo) {
    try {
        $sql = "SELECT * FROM cliente WHERE nombre = :nombre OR apellido = :apellido OR telefono = :telefono OR correo = :correo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($usuarios) {
            foreach ($usuarios as $usuario) {
                echo "ID: " . $usuario['id'] . "<br>";
                echo "Nombre: " . $usuario['nombre'] . "<br>";
                echo "Apellido: " . $usuario['apellido'] . "<br>";
                echo "Teléfono: " . $usuario['telefono'] . "<br><br>";
                echo "Correo: " . $usuario['correo'] . "<br><br>";
            }
        } else {
            echo "No se encontraron usuarios con los datos proporcionados.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar usuario: " . $e->getMessage();
    }
}

function actualizarUsuario($pdo, $nombre, $apellido, $telefono, $correo) {
    try {
        $sql = "UPDATE cliente SET apellido = :apellido, telefono = :telefono WHERE nombre = :nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->execute();
        echo "Usuario actualizado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al actualizar usuario: " . $e->getMessage();
    }
}

function eliminarUsuario($pdo, $nombre, $apellido, $telefono, $correo) {
    try {
        $sql = "DELETE FROM cliente WHERE nombre = :nombre OR apellido = :apellido OR telefono = :telefono OR correo = :correo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        echo "Usuario eliminado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al eliminar usuario: " . $e->getMessage();
    }
}

// Manejo de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $apellido = htmlspecialchars(trim($_POST['apellido'] ?? ''));
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
    $correo = htmlspecialchars(trim($_POST['correo'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($nombre && $apellido && $telefono && $correo) {
                crearUsuario($pdo, $nombre, $apellido, $telefono, $correo);
            } else {
                echo "Error: todos los campos son obligatorios para crear un usuario.";
            }
            break;

        case 'read':
            leerUsuario($pdo, $nombre, $apellido, $telefono, $correo);
            break;

        case 'update':
            if ($nombre && ($apellido || $telefono || $correo)) {
                actualizarUsuario($pdo, $nombre, $apellido, $telefono, $correo);
            } else {
                echo "Error: el nombre y al menos un dato adicional son obligatorios para actualizar.";
            }
            break;

        case 'delete':
            eliminarUsuario($pdo, $nombre, $apellido, $telefono, $correo);
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>

