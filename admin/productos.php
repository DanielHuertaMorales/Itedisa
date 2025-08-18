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

// Obtener subcategorías para el select
$subcategorias = mysqli_query($conexion, "SELECT id, nombre FROM subcategorias ORDER BY nombre ASC");

// Obtener marcas para el select
$marcas = mysqli_query($conexion, "SELECT id, nombre FROM marcas ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Lista de Productos</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
  /* Opcional: cursor pointer en iconos */
  .ver-info {
    cursor: pointer;
  }
</style>
</head>
<body class="bg-gray-100 pt-36 p-8">
    <?php include 'menu_admin.php'; ?>

<h1 class="text-2xl font-bold mb-4">Productos</h1>

<!-- Tabla -->
<div class="overflow-x-auto">
<table id="tablaProductos" class="min-w-full bg-white border border-gray-300 rounded-lg shadow table-auto">
    <thead class="bg-gray-200 text-gray-600 uppercase text-sm">
        <tr>
          <th class="px-4 py-2 border border-gray-300">ID</th>
          <th class="px-4 py-2 border border-gray-300">Nombre</th>
          <th class="px-4 py-2 border border-gray-300">Descripción</th>
          <th class="px-4 py-2 border border-gray-300">Imagen</th>
          <th class="px-4 py-2 border border-gray-300">Marca</th>
          <th class="px-4 py-2 border border-gray-300">Subcategoría</th>
          <th class="px-4 py-2 border border-gray-300">Características</th>
          <th class="px-4 py-2 border border-gray-300">Ficha Técnica</th>
          <th class="px-4 py-2 border border-gray-300">Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr class="border-b">
            <td class="py-2 px-4 border border-gray-300"><?= $row['id'] ?></td>
            <td class="py-2 px-4 border border-gray-300"><?= htmlspecialchars($row['nombre']) ?></td>
            <td class="py-2 px-4 text-center border border-gray-300">
                <button class="text-blue-500 hover:underline ver-info" 
                        data-titulo="Descripción" 
                        data-contenido="<?= htmlspecialchars($row['descripcion']) ?>">👁️</button>
            </td>
            <td class="py-2 px-4 text-center border border-gray-300">
                <?php if (!empty($row['imagen'])): ?>
                  <img src="../assets/img/productos/<?= htmlspecialchars($row['imagen']) ?>" alt="Imagen producto" class="mx-auto w-16 h-16 object-cover rounded">
                <?php else: ?>
                  <span class="text-gray-400">Sin imagen</span>
                <?php endif; ?>
            </td>
            <td class="py-2 px-4 border border-gray-300"><?= htmlspecialchars($row['marca']) ?></td>
            <td class="py-2 px-4 border border-gray-300"><?= htmlspecialchars($row['subcategoria']) ?></td>
            <td class="py-2 px-4 text-center border border-gray-300">
                <button class="text-blue-500 hover:underline ver-info" 
                        data-titulo="Características" 
                        data-contenido="<?= htmlspecialchars($row['caracteristicas']) ?>">👁️</button>
            </td>
            <td class="py-2 px-4 border border-gray-300">
                <?php if (!empty($row['ficha_tecnica_url'])): ?>
                  <a href="<?= htmlspecialchars($row['ficha_tecnica_url']) ?>" target="_blank" class="text-blue-600 underline">Ver ficha</a>
                <?php else: ?>
                  <span class="text-gray-400">N/A</span>
                <?php endif; ?>
            </td>
            <td class="py-2 px-4 text-center border border-gray-300">
                <button class="bg-yellow-500 text-white px-3 py-1 rounded editar" 
                        data-producto='<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>Editar</button>
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
    <div class="bg-white rounded-lg p-6 w-1/3 max-w-lg">
        <h2 id="modalInfoTitulo" class="text-xl font-bold mb-4"></h2>
        <p id="modalInfoContenido" class="whitespace-pre-wrap text-gray-700"></p>
        <button id="cerrarInfo" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
    </div>
</div>

<!-- Modal de editar -->
<div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-xl relative overflow-y-auto max-h-[90vh]">
    <h2 class="text-xl font-bold mb-4">Editar Producto</h2>
    <form id="formEditar" enctype="multipart/form-data">
      <input type="hidden" name="id" id="editId">

      <label class="block mb-2">Nombre:</label>
      <input type="text" name="nombre" id="editNombre" class="border w-full mb-2 p-2" required>

      <label class="block mb-2">Descripción:</label>
      <textarea name="descripcion" id="editDescripcion" class="border w-full mb-2 p-2" required></textarea>

      <label class="block mb-2">Características:</label>
      <textarea name="caracteristicas" id="editCaracteristicas" class="border w-full mb-2 p-2"></textarea>

      <label class="block mb-2">Ficha Técnica (URL):</label>
      <input type="text" name="ficha_tecnica_url" id="editFicha" class="border w-full mb-2 p-2">

      <!-- Imagen -->
      <label class="block mb-2">Imagen actual:</label>
      <img id="editImagenPreview" src="#" alt="Imagen actual" class="w-32 h-auto mb-2 border rounded hidden">
      
      <label class="block mb-2">Cambiar imagen:</label>
      <input type="file" name="imagen" id="editImagen" accept="image/*" class="mb-4">

      <!-- Subcategoría -->
      <label class="block mb-2">Subcategoría:</label>
      <select name="id_subcategoria" id="editSubcategoria" class="border w-full mb-4 p-2" required>
        <option value="">-- Selecciona una subcategoría --</option>
        <?php while($sub = mysqli_fetch_assoc($subcategorias)): ?>
          <option value="<?= $sub['id'] ?>"><?= htmlspecialchars($sub['nombre']) ?></option>
        <?php endwhile; ?>
      </select>

      <!-- Marca -->
      <label class="block mb-2">Marca:</label>
      <select name="id_marca" id="editMarca" class="border w-full mb-4 p-2" required>
        <option value="">-- Selecciona una marca --</option>
        <?php while($marca = mysqli_fetch_assoc($marcas)): ?>
          <option value="<?= $marca['id'] ?>"><?= htmlspecialchars($marca['nombre']) ?></option>
        <?php endwhile; ?>
      </select>

      <div class="flex justify-end">
        <button type="button" id="cerrarEditar" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancelar</button>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script>

// Inicializar DataTable con paginación y búsqueda
$(document).ready(function() {
  $('#tablaProductos').DataTable({
    ordering: true,     // Habilita el ordenamiento (por columnas)
    searching: true,
    pageLength: 10,
    lengthMenu: [5, 10, 25],
    language: {
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ productos",
        info: "Mostrando _START_ a _END_ de _TOTAL_ productos",
        paginate: {
        next: "Siguiente",
        previous: "Anterior"
        },
        zeroRecords: "No se encontraron productos",
        emptyTable: "No hay productos agregados."
    }
  });
});

// Mostrar modal info
$(".ver-info").click(function(){
    $("#modalInfoTitulo").text($(this).data("titulo"));
    $("#modalInfoContenido").text($(this).data("contenido"));
    $("#modalInfo").removeClass("hidden");
});
$("#cerrarInfo").click(function(){ $("#modalInfo").addClass("hidden"); });

// Abrir modal editar y cargar datos
$(".editar").click(function(){
    let prod = $(this).data("producto");
    $("#editId").val(prod.id);
    $("#editNombre").val(prod.nombre);
    $("#editDescripcion").val(prod.descripcion);
    $("#editCaracteristicas").val(prod.caracteristicas);
    $("#editFicha").val(prod.ficha_tecnica_url);

    // Mostrar imagen actual si existe
    if(prod.imagen){
      $("#editImagenPreview").attr("src", "../assets/img/productos/" + prod.imagen).removeClass("hidden");
    } else {
      $("#editImagenPreview").attr("src", "").addClass("hidden");
    }

    // Seleccionar subcategoría
    $("#editSubcategoria").val(prod.id_subcategoria);

    // Seleccionar marca
    $("#editMarca").val(prod.id_marca);

    $("#modalEditar").removeClass("hidden");
});
$("#cerrarEditar").click(function(){ $("#modalEditar").addClass("hidden"); });

// Guardar cambios
$("#formEditar").submit(function(e){
    e.preventDefault();
    let formData = new FormData(this); // Por si hay archivo
    $.ajax({
        url: "editar_producto.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(res){
            Swal.fire({
                icon: res.success ? 'success' : 'error',
                title: res.success ? 'Éxito' : 'Error',
                text: res.mensaje,
                confirmButtonText: 'OK'
            }).then(() => {
                if(res.success) location.reload();
            });
        },
        error: function(){
            Swal.fire('Error', 'Error al conectar con el servidor', 'error');
        }
    });
});

// Eliminar producto
$(".eliminar").click(function(){
    const id = $(this).data("id");
    Swal.fire({
        title: '¿Seguro que deseas eliminar este producto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if(result.isConfirmed){
            $.post("eliminar_producto.php", { id }, function(res){
                Swal.fire({
                    icon: res.success ? 'success' : 'error',
                    title: res.success ? '¡Eliminado!' : 'Error',
                    text: res.mensaje,
                    confirmButtonText: 'OK'
                }).then(() => {
                    if(res.success) location.reload();
                });
            }, "json").fail(function(){
                Swal.fire('Error', 'Error al conectar con el servidor', 'error');
            });
        }
    });
});
</script>

</body>
</html>
