<?php
session_start();
require_once './includes/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

$id = isset($_POST['subcategoria_id']) && $_POST['subcategoria_id'] !== '' ? intval($_POST['subcategoria_id']) : null;
$nombre = trim($_POST['nombre'] ?? '');
$categoria_id = intval($_POST['categoria_id'] ?? 0);

if (!$nombre || !$categoria_id) {
    echo json_encode(['success' => false, 'mensaje' => 'Complete todos los campos obligatorios']);
    exit;
}

// Manejo imagen (opcional)
$nombre_imagen = '';
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $ext_permitidas)) {
        echo json_encode(['success' => false, 'mensaje' => 'Extensión de imagen no permitida']);
        exit;
    }
    $nombre_imagen = uniqid('subcat_', true) . '.' . $ext;
    $ruta_destino = __DIR__ . "/assets/img/subcategorias/" . $nombre_imagen;
    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
        echo json_encode(['success' => false, 'mensaje' => 'Error al guardar la imagen']);
        exit;
    }
}

// Insertar o actualizar
if ($id) {
    // Actualizar
    if ($nombre_imagen) {
        $stmt = $conexion->prepare("UPDATE subcategorias SET nombre = ?, categoria_id = ?, imagen = ? WHERE id = ?");
        $stmt->bind_param("sisi", $nombre, $categoria_id, $nombre_imagen, $id);
    } else {
        $stmt = $conexion->prepare("UPDATE subcategorias SET nombre = ?, categoria_id = ? WHERE id = ?");
        $stmt->bind_param("sii", $nombre, $categoria_id, $id);
    }
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'mensaje' => 'Subcategoría actualizada correctamente']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar']);
    }
    $stmt->close();
} else {
    // Insertar
    $stmt = $conexion->prepare("INSERT INTO subcategorias (nombre, categoria_id, imagen) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $nombre, $categoria_id, $nombre_imagen);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'mensaje' => 'Subcategoría creada correctamente']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al crear']);
    }
    $stmt->close();
}

$conexion->close();
