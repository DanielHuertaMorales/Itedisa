<?php
session_start();
require_once './includes/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Gestión de Subcategorías</title>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 pt-36 p-8">
<?php include 'menu_admin.php'; ?>

<h1 class="text-3xl font-bold mb-6">Catálogo de Subcategorías</h1>

<!-- Botón agregar -->
<button id="btnAgregar" class="mb-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Agregar Subcategoría</button>

<!-- Tabla -->
<table id="tablaSubcategorias" class="min-w-full bg-white border rounded shadow-md">
<thead>
  <tr class="bg-gray-200">
    <th class="p-2 border">ID</th>
    <th class="p-2 border">Categoría</th>
    <th class="p-2 border">Nombre</th>
    <th class="p-2 border">Imagen</th>
    <th class="p-2 border text-center">Editar</th>
    <th class="p-2 border text-center">Eliminar</th>
  </tr>
</thead>
<tbody>
<?php
// Consultar subcategorías con nombre de la categoría padre
$sql = "SELECT s.id, s.nombre AS subnombre, s.imagen AS subimagen, c.nombre AS catnombre
        FROM subcategorias s
        LEFT JOIN categorias c ON s.categoria_id = c.id
        ORDER BY s.id ASC";
$result = mysqli_query($conexion, $sql);
if ($result):
  while ($row = mysqli_fetch_assoc($result)):
?>
<tr data-id="<?= $row['id'] ?>">
  <td class="border p-2"><?= $row['id'] ?></td>
  <td class="border p-2"><?= htmlspecialchars($row['catnombre'] ?? 'Sin categoría') ?></td>
  <td class="border p-2"><?= htmlspecialchars($row['subnombre']) ?></td>
  <td class="border p-2 text-center">
    <?php if ($row['subimagen']): ?>
      <img src="assets/img/subcategorias/<?= htmlspecialchars($row['subimagen']) ?>" alt="Imagen" class="h-12 mx-auto" />
    <?php else: ?>
      -
    <?php endif; ?>
  </td>
  <td class="border p-2 text-center">
    <button class="editar-btn text-green-600 font-bold cursor-pointer">Editar</button>
  </td>
  <td class="border p-2 text-center">
    <button class="eliminar-btn text-red-600 font-bold cursor-pointer">Borrar</button>
  </td>
</tr>
<?php
  endwhile;
endif;
?>
</tbody>
</table>

<!-- Modal formulario -->
<div id="modalFormulario" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
  <div class="bg-white rounded shadow-lg p-6 w-full max-w-lg relative">
    <h2 id="modalTitulo" class="text-xl font-bold mb-4">Agregar Subcategoría</h2>
    <form id="formSubcategoria" enctype="multipart/form-data">
      <input type="hidden" id="subcategoria_id" name="subcategoria_id" value="">

      <div class="mb-4">
        <label for="categoria_id" class="block mb-1 font-semibold">Categoría</label>
        <select id="categoria_id" name="categoria_id" class="border rounded w-full p-2" required>
          <option value="">Selecciona una categoría</option>
          <?php
          $cats = mysqli_query($conexion, "SELECT id, nombre FROM categorias ORDER BY nombre ASC");
          while ($cat = mysqli_fetch_assoc($cats)):
          ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-4">
        <label for="nombre" class="block mb-1 font-semibold">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="border rounded w-full p-2" required />
      </div>

      <!--<div class="mb-4">
        <label for="imagen" class="block mb-1 font-semibold">Imagen (opcional)</label>
        <input type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.gif,.webp" />
      </div>-->

      <div class="flex justify-end space-x-4">
        <button type="button" id="btnCerrarModal" class="px-4 py-2 rounded border border-gray-400 hover:bg-gray-200">Cancelar</button>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Guardar</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function() {
  const tabla = $('#tablaSubcategorias').DataTable({
    pageLength: 5,
    lengthMenu: [5,10,25],
    language: {
      search: "Buscar:",
      lengthMenu: "Mostrar _MENU_ subcategorías",
      info: "Mostrando _START_ a _END_ de _TOTAL_ subcategorías",
      paginate: { next: "Siguiente", previous: "Anterior" },
      zeroRecords: "No se encontraron subcategorías"
    }
  });

  // Abrir modal para agregar
  $('#btnAgregar').click(function(){
    $('#modalTitulo').text('Agregar Subcategoría');
    $('#formSubcategoria')[0].reset();
    $('#subcategoria_id').val('');
    $('#modalFormulario').removeClass('hidden');
  });

  // Cerrar modal
  $('#btnCerrarModal').click(function(){
    $('#modalFormulario').addClass('hidden');
  });

  // Guardar subcategoría
  $('#formSubcategoria').submit(function(e){
    e.preventDefault();
    const formData = new FormData(this);
    $.ajax({
      url: 'guardar_subcategoria.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(res){
        if(res.success){
          Swal.fire('Éxito', res.mensaje, 'success').then(()=> location.reload());
        } else {
          Swal.fire('Error', res.mensaje, 'error');
        }
      },
      error: function(){
        Swal.fire('Error', 'Error al conectar con el servidor', 'error');
      }
    });
  });

  // Editar subcategoría
  $('#tablaSubcategorias tbody').on('click', '.editar-btn', function(){
    const fila = $(this).closest('tr');
    const id = fila.data('id');

    $.getJSON('obtener_subcategoria.php?id=' + id, function(res){
      if(res){
        $('#modalTitulo').text('Editar Subcategoría');
        $('#subcategoria_id').val(res.id);
        $('#categoria_id').val(res.categoria_id);
        $('#nombre').val(res.nombre);
        $('#modalFormulario').removeClass('hidden');
      } else {
        Swal.fire('Error', 'No se encontró la subcategoría', 'error');
      }
    });
  });

  // Eliminar subcategoría
  $('#tablaSubcategorias tbody').on('click', '.eliminar-btn', function(){
    const fila = $(this).closest('tr');
    const id = fila.data('id');

    Swal.fire({
      title: '¿Eliminar subcategoría?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if(result.isConfirmed){
        $.ajax({
          url: 'eliminar_subcategoria.php',
          method: 'POST',
          data: { id },
          dataType: 'json',
          success: function(res){
            if(res.success){
              Swal.fire('Eliminado', res.mensaje, 'success').then(()=> location.reload());
            } else {
              Swal.fire('Error', res.mensaje, 'error');
            }
          },
          error: function(){
            Swal.fire('Error', 'Error al conectar con el servidor', 'error');
          }
        });
      }
    });
  });
});
</script>

</body>
</html>
