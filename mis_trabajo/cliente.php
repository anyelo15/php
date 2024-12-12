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

// Crear cliente
function crearCliente($pdo, $numero_id_cliente, $nombre, $apellido, $numero_documento, $telefono, $fecha_nacimiento) {
    try {
        $sql = "INSERT INTO factura (numero_id_cliente, nombre, apellido, numero_documento, telefono, fecha_nacimiento) VALUES (:numero_id_cliente, :nombre, :apellido, :numero_documento, :telefono, :fecha_nacimiento)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_id_cliente', $numero_id_cliente);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':numero_documento', $numero_documento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->execute();
        echo "Cliente creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear cliente: " . $e->getMessage();
    }
}

// Leer cliente
function leerCliente($pdo, $numero_id_cliente) {
    try {
        $sql = "SELECT * FROM factura WHERE numero_id_cliente = :numero_id_cliente";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_id_cliente', $numero_id_cliente);
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($clientes) {
            foreach ($clientes as $cliente) {
                echo "ID: " . $cliente['numero_id_cliente'] . "<br>";
                echo "Nombre: " . $cliente['nombre'] . "<br>";
                echo "Apellido: " . $cliente['apellido'] . "<br>";
                echo "Número de Documento: " . $cliente['numero_documento'] . "<br>";
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

// Actualizar cliente
function actualizarCliente($pdo, $numero_id_cliente, $nombre, $apellido, $numero_documento, $telefono, $fecha_nacimiento) {
    try {
        $sql = "UPDATE factura SET nombre = :nombre, apellido = :apellido, numero_documento = :numero_documento, telefono = :telefono, fecha_nacimiento = :fecha_nacimiento WHERE numero_id_cliente = :numero_id_cliente";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_id_cliente', $numero_id_cliente);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':numero_documento', $numero_documento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);

        if ($stmt->execute()) {
            echo "Cliente actualizado exitosamente.";
        } else {
            echo "Error al actualizar cliente.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar cliente: " . $e->getMessage();
    }
}

// Eliminar cliente
function eliminarCliente($pdo, $numero_id_cliente) {
    try {
        $sql = "DELETE FROM factura WHERE numero_id_cliente = :numero_id_cliente";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_id_cliente', $numero_id_cliente);

        if ($stmt->execute()) {
            echo "Cliente eliminado exitosamente.";
        } else {
            echo "Error al eliminar cliente.";
        }
    } catch (PDOException $e) {
        echo "Error al eliminar cliente: " . $e->getMessage();
    }
}

// Manejo de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $numero_id_cliente = htmlspecialchars(trim($_POST['numero_id_cliente'] ?? ''));
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $apellido = htmlspecialchars(trim($_POST['apellido'] ?? ''));
    $numero_documento = htmlspecialchars(trim($_POST['numero_documento'] ?? ''));
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
    $fecha_nacimiento = htmlspecialchars(trim($_POST['fecha_nacimiento'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($numero_id_cliente && $nombre && $apellido && $numero_documento && $telefono && $fecha_nacimiento) {
                crearCliente($pdo, $numero_id_cliente, $nombre, $apellido, $numero_documento, $telefono, $fecha_nacimiento);
            } else {
                echo "Error: todos los campos son obligatorios para crear un cliente.";
            }
            break;

        case 'read':
            if ($numero_id_cliente) {
                leerCliente($pdo, $numero_id_cliente);
            } else {
                echo "Error: el número de cliente es obligatorio para buscar.";
            }
            break;

        case 'update':
            if ($numero_id_cliente && ($nombre || $apellido || $numero_documento || $telefono || $fecha_nacimiento)) {
                actualizarCliente($pdo, $numero_id_cliente, $nombre, $apellido, $numero_documento, $telefono, $fecha_nacimiento);
            } else {
                echo "Error: el número de ID y al menos un dato adicional son obligatorios para actualizar.";
            }
            break;

        case 'delete':
            if ($numero_id_cliente) {
                eliminarCliente($pdo, $numero_id_cliente);
            } else {
                echo "Error: el número de cliente es obligatorio para eliminar.";
            }
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>
