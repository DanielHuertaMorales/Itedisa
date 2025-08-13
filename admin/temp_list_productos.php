<?php
session_start();
if(!isset($_SESSION['user_id'])){
    echo json_encode([]);
    exit;
}

require_once './includes/conexion.php';

$data = [];
if(isset($_SESSION['productos_temp'])){
    foreach($_SESSION['productos_temp'] as $p){
        $categoria = $conexion->query("SELECT nombre FROM categorias WHERE id=".$p['categoria_id'])->fetch_assoc()['nombre'];
        $marca = $conexion->query("SELECT nombre FROM marcas WHERE id=".$p['marca_id'])->fetch_assoc()['nombre'];
        $data[] = [
            'nombre' => $p['nombre'],
            'descripcion' => $p['descripcion'],
            'categoria' => $categoria,
            'marca' => $marca,
            'imagen' => $p['imagen']
        ];
    }
}
echo json_encode($data);
