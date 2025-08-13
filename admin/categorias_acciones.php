<?php
session_start();
require_once './includes/conexion.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if (!$action) {
    echo json_encode(['success' => false, 'mensaje' => 'Acción no especificada']);
    exit;
}

function limpiar($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES);
}

function subirImagen($campo) {
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $nombreTmp = $_FILES[$campo]['tmp_name'];
    $nombreOriginal = basename($_FILES[$campo]['name']);
    $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    $nuevoNombre = uniqid('cat_') . '.' . strtolower($ext);

    // Base path al proyecto industrial-website
    $basePath = realpath(__DIR__ . '/../../'); // Ajusta según la ubicación real de tu script

    $rutaDestino = $basePath 
        . DIRECTORY_SEPARATOR . 'assets' 
        . DIRECTORY_SEPARATOR . 'img' 
        . DIRECTORY_SEPARATOR . 'categorias' 
        . DIRECTORY_SEPARATOR . $nuevoNombre;

    if (!is_dir(dirname($rutaDestino))) {
        mkdir(dirname($rutaDestino), 0777, true);
    }

    if (move_uploaded_file($nombreTmp, $rutaDestino)) {
        return $nuevoNombre;
    }

    return null;
}


switch ($action) {
    case 'agregar':
        $nombre = limpiar($_POST['nombre'] ?? '');
        $imagen = subirImagen('imagen');

        if (!$nombre) {
            echo json_encode(['success' => false, 'mensaje' => 'El nombre es obligatorio']);
            exit;
        }

        $stmt = $conexion->prepare("INSERT INTO categorias (nombre, imagen) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $imagen);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'mensaje' => 'Categoría agregada correctamente']);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al agregar categoría']);
        }
        $stmt->close();
        break;

    case 'editar':
        $id = intval($_POST['id'] ?? 0);
        $nombre = limpiar($_POST['nombre'] ?? '');
        $imagen = subirImagen('imagen');

        if (!$id || !$nombre) {
            echo json_encode(['success' => false, 'mensaje' => 'Datos inválidos']);
            exit;
        }

        if ($imagen) {
            // Actualiza con imagen
            $stmt = $conexion->prepare("UPDATE categorias SET nombre = ?, imagen = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nombre, $imagen, $id);
        } else {
            // Actualiza sin cambiar imagen
            $stmt = $conexion->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
            $stmt->bind_param("si", $nombre, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'mensaje' => 'Categoría actualizada correctamente']);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar categoría']);
        }
        $stmt->close();
        break;

    case 'borrar':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'mensaje' => 'ID inválido']);
            exit;
        }
        $stmt = $conexion->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'mensaje' => 'Categoría eliminada correctamente']);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al eliminar categoría']);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
        break;
}
