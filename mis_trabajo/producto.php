<?php
// Datos de conexión a la base de datos
$host = 'localhost';
$dbname = 'gestor';
$usuario = 'root';
$contraseña = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}

// Funciones para las operaciones CRUD

// Crear producto
function crearProducto($pdo, $nombre_producto, $lote_producto, $precio) {
    try {
        $sql = "INSERT INTO productos (nombre_producto, lote_producto, precio) VALUES (:nombre_producto, :lote_producto, :precio)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':lote_producto', $lote_producto);
        $stmt->bindParam(':precio', $precio);
        $stmt->execute();
        echo "Producto creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear producto: " . $e->getMessage();
    }
}

// Leer producto
function leerProducto($pdo, $id_producto) {
    try {
        $sql = "SELECT * FROM productos WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            echo "ID Producto: " . $producto['id_producto'] . "<br>";
            echo "Nombre Producto: " . $producto['nombre_producto'] . "<br>";
            echo "Lote Producto: " . $producto['lote_producto'] . "<br>";
            echo "Precio: " . $producto['precio'] . "<br>";
        } else {
            echo "No se encontró el producto.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar producto: " . $e->getMessage();
    }
}

// Actualizar producto
function actualizarProducto($pdo, $id_producto, $nombre_producto, $lote_producto, $precio) {
    try {
        $sql = "UPDATE productos SET nombre_producto = :nombre_producto, lote_producto = :lote_producto, precio = :precio WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':lote_producto', $lote_producto);
        $stmt->bindParam(':precio', $precio);
        $stmt->execute();
        echo "Producto actualizado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al actualizar producto: " . $e->getMessage();
    }
}

// Eliminar producto
function eliminarProducto($pdo, $id_producto) {
    try {
        $sql = "DELETE FROM productos WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        echo "Producto eliminado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al eliminar producto: " . $e->getMessage();
    }
}

// Manejo de los datos enviados por el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $id_producto = htmlspecialchars(trim($_POST['id_producto'] ?? ''));
    $nombre_producto = htmlspecialchars(trim($_POST['nombre_producto'] ?? ''));
    $lote_producto = htmlspecialchars(trim($_POST['lote_producto'] ?? ''));
    $precio = htmlspecialchars(trim($_POST['precio'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($nombre_producto && $lote_producto && $precio) {
                crearProducto($pdo, $nombre_producto, $lote_producto, $precio);
            } else {
                echo "Error: todos los campos son obligatorios para crear un producto.";
            }
            break;

        case 'read':
            if ($id_producto) {
                leerProducto($pdo, $id_producto);
            } else {
                echo "Error: El ID del producto es obligatorio para buscar.";
            }
            break;

        case 'update':
            if ($id_producto && $nombre_producto && $lote_producto && $precio) {
                actualizarProducto($pdo, $id_producto, $nombre_producto, $lote_producto, $precio);
            } else {
                echo "Error: Todos los campos son obligatorios para actualizar el producto.";
            }
            break;

        case 'delete':
            if ($id_producto) {
                eliminarProducto($pdo, $id_producto);
            } else {
                echo "Error: El ID del producto es obligatorio para eliminar.";
            }
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>

