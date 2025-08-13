<?php
session_start();
require_once './includes/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'mensaje' => 'Acceso no válido']);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$categoria_id = intval($_POST['categoria_id'] ?? 0);
$subcategoria_id = isset($_POST['subcategoria_id']) && $_POST['subcategoria_id'] !== '' ? intval($_POST['subcategoria_id']) : null;
$marca_id = intval($_POST['marca_id'] ?? 0);
$caracteristicas = trim($_POST['caracteristicas'] ?? '');
$ficha_tecnica_url = trim($_POST['ficha_tecnica_url'] ?? '');
$index_editar = isset($_POST['index_editar']) && $_POST['index_editar'] !== '' ? intval($_POST['index_editar']) : null;

if (!$nombre || !$descripcion || !$categoria_id || !$marca_id) {
    echo json_encode(['success' => false, 'mensaje' => 'Complete todos los campos obligatorios']);
    exit;
}

// Validar URL ficha técnica si no está vacía (opcional)
if ($ficha_tecnica_url !== '' && !filter_var($ficha_tecnica_url, FILTER_VALIDATE_URL)) {
    echo json_encode(['success' => false, 'mensaje' => 'La URL de la ficha técnica no es válida']);
    exit;
}

// Manejo imagen: si es edición y no sube imagen, se conserva la anterior
$nombre_imagen = '';
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext_archivo = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext_archivo, $ext_permitidas)) {
        echo json_encode(['success' => false, 'mensaje' => 'Extensión de imagen no permitida']);
        exit;
    }
    $nombre_imagen = uniqid('prod_', true) . '.' . $ext_archivo;
    $ruta_destino = "C:/xampp/htdocs/industrial-website/assets/img/productos/" . $nombre_imagen;
    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
        echo json_encode(['success' => false, 'mensaje' => 'Error al guardar la imagen']);
        exit;
    }
} else {
    if ($index_editar !== null && isset($_SESSION['productos_temp'][$index_editar])) {
        // conservar la imagen previa
        $nombre_imagen = $_SESSION['productos_temp'][$index_editar]['imagen'];
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Debe subir una imagen']);
        exit;
    }
}

// Obtener nombres para mostrar
$categoria_nombre = '';
$subcategoria_nombre = '';
$marca_nombre = '';

$resCat = $conexion->query("SELECT nombre FROM categorias WHERE id = $categoria_id");
if ($resCat && $rowCat = $resCat->fetch_assoc()) {
    $categoria_nombre = $rowCat['nombre'];
}

if ($subcategoria_id) {
    $resSubcat = $conexion->query("SELECT nombre FROM subcategorias WHERE id = $subcategoria_id");
    if ($resSubcat && $rowSubcat = $resSubcat->fetch_assoc()) {
        $subcategoria_nombre = $rowSubcat['nombre'];
    }
}

$resMarca = $conexion->query("SELECT nombre FROM marcas WHERE id = $marca_id");
if ($resMarca && $rowMarca = $resMarca->fetch_assoc()) {
    $marca_nombre = $rowMarca['nombre'];
}

if (!isset($_SESSION['productos_temp'])) {
    $_SESSION['productos_temp'] = [];
}

$nuevo_producto = [
    'nombre' => $nombre,
    'descripcion' => $descripcion,
    'categoria_id' => $categoria_id,
    'categoria_nombre' => $categoria_nombre,
    'subcategoria_id' => $subcategoria_id,
    'subcategoria_nombre' => $subcategoria_nombre,
    'marca_id' => $marca_id,
    'marca_nombre' => $marca_nombre,
    'caracteristicas' => $caracteristicas,
    'ficha_tecnica_url' => $ficha_tecnica_url,
    'imagen' => $nombre_imagen
];

if ($index_editar !== null && isset($_SESSION['productos_temp'][$index_editar])) {
    // actualizar producto existente
    $_SESSION['productos_temp'][$index_editar] = $nuevo_producto;
    echo json_encode(['success' => true, 'mensaje' => 'Producto actualizado correctamente']);
} else {
    // agregar nuevo producto
    $_SESSION['productos_temp'][] = $nuevo_producto;
    echo json_encode(['success' => true, 'mensaje' => 'Producto agregado a la lista temporal']);
}

exit;
