<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'gestor';
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
function crearCliente($pdo, $nombre, $apellido, $tipo_documento, $documento, $telefono, $fecha_nacimiento) {
    try {
        $sql = "INSERT INTO factura (nombre, apellido, tipo_documento, documento, telefono, fecha_nacimiento) VALUES (:nombre, :apellido, :tipo_documento, :documento, :telefono, :fecha_nacimiento)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->execute();
        echo "Cliente creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear cliente: " . $e->getMessage();
    }
}

function leerCliente($pdo, $documento) {
    try {
        $sql = "SELECT * FROM factura WHERE documento = :documento";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':documento', $documento);
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($clientes) {
            foreach ($clientes as $cliente) {
                echo "ID: " . $cliente['id_cliente'] . "<br>";
                echo "Nombre: " . $cliente['nombre'] . "<br>";
                echo "Apellido: " . $cliente['apellido'] . "<br>";
                echo "Número de Documento: " . $cliente['documento'] . "<br>";
                echo "Teléfono: " . $cliente['telefono'] . "<br>";
                echo "Fecha de Nacimiento: " . $cliente['fecha_nacimiento'] . "<br><br>";
            }
        } else {
            echo "No se encontraron clientes con los datos proporcionados.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar cliente: " . $e->getMessage();
    }
}

function actualizarCliente($pdo, $documento, $nombre, $apellido, $tipo_documento, $telefono, $fecha_nacimiento) {
    try {
        $sql = "UPDATE factura SET nombre = :nombre, apellido = :apellido, tipo_documento = :tipo_documento, telefono = :telefono, fecha_nacimiento = :fecha_nacimiento WHERE documento = :documento";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':documento', $documento);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->execute();
        echo "Cliente actualizado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al actualizar cliente: " . $e->getMessage();
    }
}

function eliminarCliente($pdo, $documento) {
    try {
        $sql = "DELETE FROM factura WHERE documento = :documento";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':documento', $documento);
        $stmt->execute();
        echo "Cliente eliminado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al eliminar cliente: " . $e->getMessage();
    }
}

// Verificar la acción del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $apellido = htmlspecialchars(trim($_POST['apellido'] ?? ''));
    $tipo_documento = htmlspecialchars(trim($_POST['tipo_documento'] ?? ''));
    $documento = htmlspecialchars(trim($_POST['documento'] ?? ''));
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
    $fecha_nacimiento = htmlspecialchars(trim($_POST['fecha_nacimiento'] ?? ''));

    // Validación
    if ($nombre && $apellido && $tipo_documento && $documento && $telefono && $fecha_nacimiento) {
        if ($_POST['accion'] == 'create') {
            crearCliente($pdo, $nombre, $apellido, $tipo_documento, $documento, $telefono, $fecha_nacimiento);
        } elseif ($_POST['accion'] == 'read') {
            leerCliente($pdo, $documento);
        } elseif ($_POST['accion'] == 'update') {
            actualizarCliente($pdo, $documento, $nombre, $apellido, $tipo_documento, $telefono, $fecha_nacimiento);
        } elseif ($_POST['accion'] == 'delete') {
            eliminarCliente($pdo, $documento);
        }
    } else {
        echo "Error: todos los campos son obligatorios para crear un cliente.";
    }
}
?>
