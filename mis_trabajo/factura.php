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

// Funciones CRUD para Factura

// Crear factura
function crearFactura($pdo, $id_factura, $id_cliente, $fecha, $total) {
    try {
        $sql = "INSERT INTO factura (id_factura, id_cliente, fecha, total) VALUES (:id_factura, :id_cliente, :fecha, :total)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_factura', $id_factura);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':total', $total);
        $stmt->execute();
        echo "Factura creada exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear factura: " . $e->getMessage();
    }
}

// Leer factura
function leerFactura($pdo, $id_factura) {
    try {
        $sql = "SELECT * FROM factura WHERE id_factura = :id_factura";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_factura', $id_factura);
        $stmt->execute();
        $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($facturas) {
            foreach ($facturas as $factura) {
                echo "ID Factura: " . $factura['id_factura'] . "<br>";
                echo "ID Cliente: " . $factura['id_cliente'] . "<br>";
                echo "Fecha: " . $factura['fecha'] . "<br>";
                echo "Total: $" . $factura['total'] . "<br><br>";
            }
        } else {
            echo "No se encontraron facturas con el ID proporcionado.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar factura: " . $e->getMessage();
    }
}

// Actualizar factura
function actualizarFactura($pdo, $id_factura, $id_cliente, $fecha, $total) {
    try {
        $sql = "UPDATE factura SET id_cliente = :id_cliente, fecha = :fecha, total = :total WHERE id_factura = :id_factura";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_factura', $id_factura);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':total', $total);

        if ($stmt->execute()) {
            echo "Factura actualizada exitosamente.";
        } else {
            echo "Error al actualizar factura.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar factura: " . $e->getMessage();
    }
}

// Eliminar factura
function eliminarFactura($pdo, $id_factura) {
    try {
        $sql = "DELETE FROM factura WHERE id_factura = :id_factura";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_factura', $id_factura);

        if ($stmt->execute()) {
            echo "Factura eliminada exitosamente.";
        } else {
            echo "Error al eliminar factura.";
        }
    } catch (PDOException $e) {
        echo "Error al eliminar factura: " . $e->getMessage();
    }
}

// Manejo de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $id_factura = htmlspecialchars(trim($_POST['id_factura'] ?? ''));
    $id_cliente = htmlspecialchars(trim($_POST['id_cliente'] ?? ''));
    $fecha = htmlspecialchars(trim($_POST['fecha'] ?? ''));
    $total = htmlspecialchars(trim($_POST['total'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($id_factura && $id_cliente && $fecha && $total) {
                crearFactura($pdo, $id_factura, $id_cliente, $fecha, $total);
            } else {
                echo "Error: todos los campos son obligatorios para crear una factura.";
            }
            break;

        case 'read':
            if ($id_factura) {
                leerFactura($pdo, $id_factura);
            } else {
                echo "Error: el ID de la factura es obligatorio para buscar.";
            }
            break;

        case 'update':
            if ($id_factura && ($id_cliente || $fecha || $total)) {
                actualizarFactura($pdo, $id_factura, $id_cliente, $fecha, $total);
            } else {
                echo "Error: el ID de factura y al menos un dato adicional son obligatorios para actualizar.";
            }
            break;

        case 'delete':
            if ($id_factura) {
                eliminarFactura($pdo, $id_factura);
            } else {
                echo "Error: el ID de la factura es obligatorio para eliminar.";
            }
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>
