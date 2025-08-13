<?php
session_start();
require_once './includes/conexion.php';

// Obtener productos con JOIN para mostrar datos más legibles
$query = "SELECT p.*, s.nombre AS subcategoria, m.nombre AS marca 
          FROM productos p
          LEFT JOIN subcategorias s ON p.id_subcategoria = s.id
          LEFT JOIN marcas m ON p.id_marca = m.id
          ORDER BY p.id DESC";
$result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Lista de Productos</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 pt-36 p-8">
    <?php include 'menu_admin.php'; ?>

<h1 class="text-2xl font-bold mb-4">Productos</h1>

<!-- Tabla -->
<div class="overflow-x-auto">
<table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
    <thead class="bg-gray-200 text-gray-600 uppercase text-sm">
        <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">Nombre</th>
            <th class="py-3 px-4 text-left">Descripción</th>
            <th class="py-3 px-4 text-left">Características</th>
            <th class="py-3 px-4 text-left">Subcategoría</th>
            <th class="py-3 px-4 text-left">Marca</th>
            <th class="py-3 px-4 text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr class="border-b">
            <td class="py-2 px-4"><?= $row['id'] ?></td>
            <td class="py-2 px-4"><?= htmlspecialchars($row['nombre']) ?></td>
            <td class="py-2 px-4 text-center">
                <button class="text-blue-500 hover:underline ver-info" 
                        data-titulo="Descripción" 
                        data-contenido="<?= htmlspecialchars($row['descripcion']) ?>">👁️</button>
            </td>
            <td class="py-2 px-4 text-center">
                <button class="text-blue-500 hover:underline ver-info" 
                        data-titulo="Características" 
                        data-contenido="<?= htmlspecialchars($row['caracteristicas']) ?>">👁️</button>
            </td>
            <td class="py-2 px-4"><?= htmlspecialchars($row['subcategoria']) ?></td>
            <td class="py-2 px-4"><?= htmlspecialchars($row['marca']) ?></td>
            <td class="py-2 px-4 text-center">
                <button class="bg-yellow-500 text-white px-3 py-1 rounded editar" 
                        data-producto='<?= json_encode($row) ?>'>Editar</button>
                <button class="bg-red-500 text-white px-3 py-1 rounded eliminar" 
                        data-id="<?= $row['id'] ?>">Eliminar</button>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>

<!-- Modal de ver info -->
<div id="modalInfo" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg p-6 w-1/3">
        <h2 id="modalInfoTitulo" class="text-xl font-bold mb-4"></h2>
        <p id="modalInfoContenido" class="text-gray-700"></p>
        <button id="cerrarInfo" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
    </div>
</div>

<!-- Modal de editar -->
<div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg p-6 w-1/3">
        <h2 class="text-xl font-bold mb-4">Editar Producto</h2>
        <form id="formEditar">
            <input type="hidden" name="id" id="editId">
            <label class="block mb-2">Nombre:</label>
            <input type="text" name="nombre" id="editNombre" class="border w-full mb-2 p-2" required>

            <label class="block mb-2">Descripción:</label>
            <textarea name="descripcion" id="editDescripcion" class="border w-full mb-2 p-2"></textarea>

            <label class="block mb-2">Características:</label>
            <textarea name="caracteristicas" id="editCaracteristicas" class="border w-full mb-2 p-2"></textarea>

            <label class="block mb-2">Ficha Técnica (URL):</label>
            <input type="text" name="ficha_tecnica_url" id="editFicha" class="border w-full mb-4 p-2">

            <div class="flex justify-end">
                <button type="button" id="cerrarEditar" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancelar</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
// Ver info en modal
$(".ver-info").click(function(){
    $("#modalInfoTitulo").text($(this).data("titulo"));
    $("#modalInfoContenido").text($(this).data("contenido"));
    $("#modalInfo").removeClass("hidden");
});
$("#cerrarInfo").click(function(){ $("#modalInfo").addClass("hidden"); });

// Abrir modal de editar
$(".editar").click(function(){
    let prod = $(this).data("producto");
    $("#editId").val(prod.id);
    $("#editNombre").val(prod.nombre);
    $("#editDescripcion").val(prod.descripcion);
    $("#editCaracteristicas").val(prod.caracteristicas);
    $("#editFicha").val(prod.ficha_tecnica_url);
    $("#modalEditar").removeClass("hidden");
});
$("#cerrarEditar").click(function(){ $("#modalEditar").addClass("hidden"); });

// Guardar cambios
$("#formEditar").submit(function(e){
    e.preventDefault();
    $.post("editar_producto.php", $(this).serialize(), function(res){
        alert(res.mensaje);
        if(res.success) location.reload();
    }, "json");
});

// Eliminar producto
$(".eliminar").click(function(){
    if(confirm("¿Seguro que deseas eliminar este producto?")){
        $.post("eliminar_producto.php", { id: $(this).data("id") }, function(res){
            alert(res.mensaje);
            if(res.success) location.reload();
        }, "json");
    }
});
</script>

</body>
</html>
