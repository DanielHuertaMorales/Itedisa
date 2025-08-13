<?php
require_once './includes/conexion.php';

if (!isset($conexion)) {
    die("Error: No se pudo establecer la conexión con la base de datos.");
}

// Obtener todos los registros de la tabla temporal
$sqlTemp = "SELECT * FROM productos_temp";
$resultTemp = mysqli_query($conexion, $sqlTemp);

if (!$resultTemp || mysqli_num_rows($resultTemp) === 0) {
    echo "No hay productos para guardar.";
    exit;
}

// Ruta final de imágenes
$ruta_final = "C:/xampp/htdocs/industrial-website/assets/img/productos/";

while ($row = mysqli_fetch_assoc($resultTemp)) {

    $subcategoria_id = (int)$row['subcategoria_id'];
    $marca_id = (int)$row['marca_id'];
    $nombre = mysqli_real_escape_string($conexion, $row['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $row['descripcion']);
    $ficha_tecnica_url = mysqli_real_escape_string($conexion, $row['ficha_tecnica_url']);
    $imagen = mysqli_real_escape_string($conexion, $row['imagen']);

    // Copiar imagen si no está ya en destino
    $ruta_temp_img = $imagen;
    $nombre_img = basename($imagen);
    $ruta_destino_img = $ruta_final . $nombre_img;

    if (file_exists($ruta_temp_img) && !file_exists($ruta_destino_img)) {
        copy($ruta_temp_img, $ruta_destino_img);
        // unlink($ruta_temp_img); // Si quieres borrar el archivo original
    }

    // Insertar en productos
    $stmt = $conexion->prepare(
        "INSERT INTO productos (subcategoria_id, marca_id, nombre, descripcion, ficha_tecnica_url, imagen) 
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iissss", $subcategoria_id, $marca_id, $nombre, $descripcion, $ficha_tecnica_url, $nombre_img);
    $stmt->execute();
    $stmt->close();
}

// Limpiar tabla temporal
mysqli_query($conexion, "TRUNCATE TABLE productos_temp");

echo "Lote guardado exitosamente.";
?>
