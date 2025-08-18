<?php
session_start();
require_once './includes/conexion.php';

// Traer categorías para la tabla
$query = "SELECT id, nombre, imagen FROM categorias ORDER BY id ASC";
$result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Catálogo de Categorías</title>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- jQuery y DataTables CSS/JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<!-- SweetAlert2 para alertas -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="bg-gray-100 pt-36 p-8">
<?php include 'menu_admin.php'; ?>

<h1 class="text-3xl font-bold mb-6">Catálogo de Categorías</h1>

<button id="btnAgregar" class="mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
  Agregar Categoría
</button>

<table id="tablaCategorias" class="min-w-full bg-white rounded shadow-md">
  <thead>
    <tr class="bg-gray-200">
      <th class="p-2 border text-center">ID</th>
      <th class="p-2 border">Nombre</th>
      <th class="p-2 border text-center">Imagen</th>
      <th class="p-2 border text-center">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr data-id="<?= $row['id'] ?>">
      <td class="border p-2 text-center"><?= $row['id'] ?></td>
      <td class="border p-2"><?= htmlspecialchars($row['nombre']) ?></td>
      <td class="border p-2 text-center">
        <?php if (!empty($row['imagen'])): ?>
          <img src="../assets/img/categorias/<?= htmlspecialchars($row['imagen']) ?>" class="mx-auto w-16 h-16 object-cover rounded" alt="Imagen categoría">
        <?php else: ?>
          <span class="text-gray-400">Sin imagen</span>
        <?php endif; ?>
      </td>
      <td class="border p-2 text-center">
        <button class="editar-btn text-green-600 font-semibold mr-2">Editar</button>
        <button class="borrar-btn text-red-600 font-semibold">Borrar</button>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<!-- Modal formulario -->
<div id="modalCategoria" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded p-6 w-96 relative">
    <h2 id="modalTitulo" class="text-xl font-bold mb-4">Agregar Categoría</h2>
    <form id="formCategoria" enctype="multipart/form-data">
      <input type="hidden" name="id" id="categoriaId" value="" />
      <div class="mb-4">
        <label for="nombreCategoria" class="block font-semibold mb-1">Nombre</label>
        <input type="text" id="nombreCategoria" name="nombre" class="border rounded w-full p-2" required />
      </div>
      <div class="mb-4">
        <label for="imagenCategoria" class="block font-semibold mb-1">Imagen</label>
        <input type="file" id="imagenCategoria" name="imagen" accept="image/*" class="border rounded w-full p-2" />
        <small class="text-gray-500">Formatos permitidos: JPG, PNG, GIF</small>
        <div id="previewImagen" class="mt-2"></div>
      </div>
      <div class="flex justify-end">
        <button type="button" id="btnCancelar" class="mr-4 px-4 py-2 rounded border border-gray-400 hover:bg-gray-100">Cancelar</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar</button>
      </div>
    </form>
    <button id="btnCerrarModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 font-bold text-xl">&times;</button>
  </div>
</div>

<script>
$(document).ready(function(){
  // Inicializar DataTable
  $('#tablaCategorias').DataTable({
    language: {
      search: "Buscar:",
      lengthMenu: "Mostrar _MENU_ categorías",
      info: "Mostrando _START_ a _END_ de _TOTAL_ categorías",
      paginate: { next: "Siguiente", previous: "Anterior" },
      zeroRecords: "No se encontraron categorías"
    }
  });

  const modal = $('#modalCategoria');
  const form = $('#formCategoria');
  const tituloModal = $('#modalTitulo');
  const inputId = $('#categoriaId');
  const inputNombre = $('#nombreCategoria');
  const inputImagen = $('#imagenCategoria');
  const preview = $('#previewImagen');

  // Vista previa de imagen
  inputImagen.on('change', function(){
    const file = this.files[0];
    if(file){
      const reader = new FileReader();
      reader.onload = function(e){
        preview.html('<img src="'+e.target.result+'" class="w-24 h-24 object-cover rounded">');
      }
      reader.readAsDataURL(file);
    }
  });

  $('#btnAgregar').click(function(){
    tituloModal.text('Agregar Categoría');
    inputId.val('');
    inputNombre.val('');
    inputImagen.val('');
    preview.html('');
    modal.removeClass('hidden');
  });

  $('#btnCancelar, #btnCerrarModal').click(function(){
    modal.addClass('hidden');
  });

  $('.editar-btn').click(function(){
    const fila = $(this).closest('tr');
    const id = fila.data('id');
    const nombre = fila.find('td:nth-child(2)').text().trim();
    const img = fila.find('img').attr('src');

    tituloModal.text('Editar Categoría');
    inputId.val(id);
    inputNombre.val(nombre);
    preview.html(img ? '<img src="'+img+'" class="w-24 h-24 object-cover rounded">' : '');
    modal.removeClass('hidden');
  });

  $('.borrar-btn').click(function(){
    const fila = $(this).closest('tr');
    const id = fila.data('id');
    const nombre = fila.find('td:nth-child(2)').text().trim();

    Swal.fire({
      title: '¿Eliminar categoría?',
      text: `Se eliminará "${nombre}". Esta acción no se puede deshacer.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, borrar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'categorias_acciones.php',
          method: 'POST',
          data: { action: 'borrar', id },
          dataType: 'json',
          success: function(response){
            if(response.success){
              Swal.fire('Eliminado', response.mensaje, 'success').then(() => location.reload());
            } else {
              Swal.fire('Error', response.mensaje, 'error');
            }
          },
          error: () => {
            Swal.fire('Error', 'Error al conectar con el servidor', 'error');
          }
        });
      }
    });
  });

  form.submit(function(e){
    e.preventDefault();
    let formData = new FormData(this);
    formData.append('action', inputId.val() ? 'editar' : 'agregar');

    $.ajax({
      url: 'categorias_acciones.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(response){
        if(response.success){
          Swal.fire('Éxito', response.mensaje, 'success').then(() => location.reload());
        } else {
          Swal.fire('Error', response.mensaje, 'error');
        }
      },
      error: () => {
        Swal.fire('Error', 'Error al conectar con el servidor', 'error');
      }
    });
  });
});

</script>

</body>
</html>
