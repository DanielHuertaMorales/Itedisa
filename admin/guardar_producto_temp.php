<?php
session_start();
require_once './includes/conexion.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Guardar configuración persistente (categoría, subcategoría, marca)
if (isset($_POST['guardar_configuracion']) && $_POST['guardar_configuracion'] == '1') {
    $categoria_id = intval($_POST['categoria_id'] ?? 0);
    $subcategoria_id = $_POST['subcategoria_id'] !== '' ? intval($_POST['subcategoria_id']) : null;
    $marca_id = intval($_POST['marca_id'] ?? 0);

    $_SESSION['config_producto_fija'] = [
        'categoria_id' => $categoria_id,
        'subcategoria_id' => $subcategoria_id,
        'marca_id' => $marca_id
    ];
    echo json_encode(['success' => true, 'mensaje' => 'Configuración guardada.']);
    exit;
}

// Aplicar configuración guardada si existe
if (isset($_SESSION['config_producto_fija'])) {
    if (!isset($_POST['categoria_id']) || $_POST['categoria_id'] == 0) {
        $_POST['categoria_id'] = $_SESSION['config_producto_fija']['categoria_id'];
    }
    if (!isset($_POST['subcategoria_id']) || $_POST['subcategoria_id'] === '') {
        $_POST['subcategoria_id'] = $_SESSION['config_producto_fija']['subcategoria_id'];
    }
    if (!isset($_POST['marca_id']) || $_POST['marca_id'] == 0) {
        $_POST['marca_id'] = $_SESSION['config_producto_fija']['marca_id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'mensaje' => 'Acceso no válido']);
    exit;
}

// Obtener datos del formulario (después de aplicar configuración)
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$categoria_id = intval($_POST['categoria_id'] ?? 0);
$subcategoria_id = $_POST['subcategoria_id'] !== '' ? intval($_POST['subcategoria_id']) : null;
$marca_id = intval($_POST['marca_id'] ?? 0);
$caracteristicas = trim($_POST['caracteristicas'] ?? '');
$index_editar = isset($_POST['index_editar']) && $_POST['index_editar'] !== '' ? intval($_POST['index_editar']) : null;

// Validaciones básicas
if (!$nombre || !$descripcion || !$categoria_id || !$marca_id) {
    echo json_encode(['success' => false, 'mensaje' => 'Complete todos los campos obligatorios']);
    exit;
}

// ========== 📷 Subir imagen ==========
$nombre_imagen = '';
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext_archivo = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext_archivo, $ext_permitidas)) {
        echo json_encode(['success' => false, 'mensaje' => 'Extensión de imagen no permitida']);
        exit;
    }

    $nombre_imagen = uniqid('prod_', true) . '.' . $ext_archivo;
    $ruta_relativa = '../assets/img/productos/' . $nombre_imagen;
    $ruta_absoluta = __DIR__ . '/../assets/img/productos/' . $nombre_imagen;

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_absoluta)) {
        echo json_encode(['success' => false, 'mensaje' => 'Error al guardar la imagen']);
        exit;
    }
} else {
    if ($index_editar !== null && isset($_SESSION['productos_temp'][$index_editar])) {
        $nombre_imagen = $_SESSION['productos_temp'][$index_editar]['imagen'];
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Debe subir una imagen']);
        exit;
    }
}

// ========== 📄 Subir ficha técnica (PDF) ==========
$ficha_tecnica_url = null;

if (isset($_FILES['ficha_tecnica_file']) && $_FILES['ficha_tecnica_file']['error'] === UPLOAD_ERR_OK) {
    $extension = strtolower(pathinfo($_FILES['ficha_tecnica_file']['name'], PATHINFO_EXTENSION));
    if ($extension !== 'pdf') {
        error_log("Archivo inválido: no es PDF.");
    } else {
        $nombreArchivoFicha = time() . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($_FILES['ficha_tecnica_file']['name']));
        $directorioDestino = __DIR__ . '/../assets/fichas/';
        $rutaDestinoFicha = $directorioDestino . $nombreArchivoFicha;

        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        if (move_uploaded_file($_FILES['ficha_tecnica_file']['tmp_name'], $rutaDestinoFicha)) {
            // ✅ GUARDAMOS SOLO EL NOMBRE DEL ARCHIVO
            $ficha_tecnica_url = $nombreArchivoFicha;
        } else {
            error_log("Error al mover la ficha técnica.");
        }
    }
}

// ========== 📥 Obtener nombres para mostrar ==========
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

// ========== 💾 Guardar en sesión ==========
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
    $_SESSION['productos_temp'][$index_editar] = $nuevo_producto;
    echo json_encode(['success' => true, 'mensaje' => 'Producto actualizado correctamente']);
} else {
    $_SESSION['productos_temp'][] = $nuevo_producto;
    echo json_encode(['success' => true, 'mensaje' => 'Producto agregado a la lista temporal']);
}
exit;
