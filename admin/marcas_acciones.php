<?php
session_start();
require_once './includes/conexion.php';
header('Content-Type: application/json');

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

$accion = $_POST['action'] ?? '';

if (!$accion) {
    echo json_encode(['success' => false, 'mensaje' => 'Acción no especificada']);
    exit;
}

switch ($accion) {
    case 'listar':
        $query = "SELECT * FROM marcas ORDER BY id ASC";
        $result = mysqli_query($conexion, $query);
        $marcas = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $marcas[] = $row;
        }

        echo json_encode(['success' => true, 'data' => $marcas]);
        break;

    case 'agregar':
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $nombreImagen = '';

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Ajusta la ruta según dónde está este archivo
        $directorio = __DIR__ . "/../assets/img/marcas/";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $nombreImagen = time() . '_' . basename($_FILES['imagen']['name']);
        $rutaDestino = $directorio . $nombreImagen;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            echo json_encode(['success' => false, 'mensaje' => 'Error al mover la imagen al destino']);
            exit;
        }

        // 👇 Aquí agregas esta línea de prueba:
        error_log("Imagen guardada en: " . $rutaDestino);
    }

    $stmt = $conexion->prepare("INSERT INTO marcas (nombre, descripcion, imagen) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $descripcion, $nombreImagen);
    $stmt->execute();

    echo json_encode(['success' => true]);
    break;

    case 'editar':
        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $imagen = $_FILES['imagen'] ?? null;

        if ($id <= 0 || empty($nombre)) {
            echo json_encode(['success' => false, 'mensaje' => 'Datos inválidos']);
            exit;
        }

        $nombreImagen = $_POST['imagen_actual'] ?? '';
        if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
            $directorio = __DIR__ . "/assets/img/marcas/";
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }

            $nombreImagen = time() . '_' . basename($imagen['name']);
            $rutaDestino = $directorio . $nombreImagen;

            if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                echo json_encode(['success' => false, 'mensaje' => 'Error al guardar la nueva imagen']);
                exit;
            }
        }

        $stmt = $conexion->prepare("UPDATE marcas SET nombre = ?, imagen = ?, descripcion = ? WHERE id = ?");
        if (!$stmt) {
            echo json_encode(['success' => false, 'mensaje' => 'Error al preparar la consulta']);
            exit;
        }

        $stmt->bind_param("sssi", $nombre, $nombreImagen, $descripcion, $id);
        $stmt->execute();

        echo json_encode(['success' => true, 'mensaje' => 'Marca actualizada correctamente']);
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'mensaje' => 'ID inválido']);
            exit;
        }

        $stmt = $conexion->prepare("DELETE FROM marcas WHERE id = ?");
        if (!$stmt) {
            echo json_encode(['success' => false, 'mensaje' => 'Error al preparar la consulta']);
            exit;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(['success' => true, 'mensaje' => 'Marca eliminada correctamente']);
        break;

    default:
        echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
        break;
}
