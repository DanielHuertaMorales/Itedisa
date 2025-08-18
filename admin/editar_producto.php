<?php
require_once './includes/conexion.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$caracteristicas = $_POST['caracteristicas'] ?? '';
$ficha = $_POST['ficha_tecnica_url'] ?? '';
$id_subcategoria = $_POST['id_subcategoria'] ?? null;
$id_marca = $_POST['id_marca'] ?? null;

if (!$id || !$nombre || !$id_subcategoria || !$id_marca) {
    echo json_encode(["success" => false, "mensaje" => "Faltan datos obligatorios"]);
    exit;
}

// Función para subir imagen y devolver nombre o null
function subirImagen($campo) {
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $nombreTmp = $_FILES[$campo]['tmp_name'];
    $nombreOriginal = basename($_FILES[$campo]['name']);
    $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    $nuevoNombre = uniqid('prod_') . '.' . strtolower($ext);

    $basePath = realpath(__DIR__ . '/../assets/img/productos'); // Ajusta ruta según tu estructura

    if (!is_dir($basePath)) {
        mkdir($basePath, 0777, true);
    }

    $rutaDestino = $basePath . DIRECTORY_SEPARATOR . $nuevoNombre;

    if (move_uploaded_file($nombreTmp, $rutaDestino)) {
        return $nuevoNombre;
    }

    return null;
}

// Procesar imagen
$imagenNueva = subirImagen('imagen');

if ($imagenNueva) {
    // Actualiza con imagen
    $stmt = $conexion->prepare("UPDATE productos SET nombre=?, descripcion=?, caracteristicas=?, ficha_tecnica_url=?, id_subcategoria=?, id_marca=?, imagen=? WHERE id=?");
    $stmt->bind_param("sssssssi", $nombre, $descripcion, $caracteristicas, $ficha, $id_subcategoria, $id_marca, $imagenNueva, $id);
} else {
    // Actualiza sin cambiar imagen
    $stmt = $conexion->prepare("UPDATE productos SET nombre=?, descripcion=?, caracteristicas=?, ficha_tecnica_url=?, id_subcategoria=?, id_marca=? WHERE id=?");
    $stmt->bind_param("ssssssi", $nombre, $descripcion, $caracteristicas, $ficha, $id_subcategoria, $id_marca, $id);
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "mensaje" => "Producto actualizado correctamente"]);
} else {
    echo json_encode(["success" => false, "mensaje" => "Error al actualizar producto"]);
}
$stmt->close();
