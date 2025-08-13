<?php
require_once './includes/conexion.php';
header('Content-Type: application/json');

$id = $_POST['id'];

$stmt = $conexion->prepare("DELETE FROM productos WHERE id=?");
$stmt->bind_param("i", $id);

if($stmt->execute()){
    echo json_encode(["success"=>true, "mensaje"=>"Producto eliminado correctamente"]);
} else {
    echo json_encode(["success"=>false, "mensaje"=>"Error al eliminar"]);
}
$stmt->close();
