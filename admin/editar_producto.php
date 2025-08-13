<?php
require_once './includes/conexion.php';
header('Content-Type: application/json');

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$caracteristicas = $_POST['caracteristicas'];
$ficha = $_POST['ficha_tecnica_url'];

$stmt = $conexion->prepare("UPDATE productos SET nombre=?, descripcion=?, caracteristicas=?, ficha_tecnica_url=? WHERE id=?");
$stmt->bind_param("ssssi", $nombre, $descripcion, $caracteristicas, $ficha, $id);

if($stmt->execute()){
    echo json_encode(["success"=>true, "mensaje"=>"Producto actualizado correctamente"]);
} else {
    echo json_encode(["success"=>false, "mensaje"=>"Error al actualizar"]);
}
$stmt->close();
