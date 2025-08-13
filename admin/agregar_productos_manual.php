<?php
session_start();
require_once './includes/conexion.php';

// Obtener categorías y marcas para combos
$categorias = mysqli_query($conexion, "SELECT id, nombre FROM categorias ORDER BY nombre ASC");
$marcas = mysqli_query($conexion, "SELECT id, nombre FROM marcas ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Productos Manualmente</title>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery y DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

</head>
<body class="bg-gray-100 pt-36 p-8">
<?php include 'menu_admin.php'; ?>

<h1 class="text-2xl font-bold mb-4">Agregar Producto</h1>

<form id="formAgregar" enctype="multipart/form-data" class="grid grid-cols-2 gap-6 bg-white p-6 rounded shadow-md w-full">

  <input type="hidden" id="index_editar" name="index_editar" value="">

  <div>
    <label>Nombre del Producto</label>
    <input type="text" name="nombre" id="nombre" class="border rounded w-full p-2" placeholder="Ej: Bomba Centrífuga" required>
  </div>

  <div>
    <label>Descripción</label>
    <textarea name="descripcion" id="descripcion" class="border rounded w-full p-2" placeholder="Descripción breve" required></textarea>
  </div>

  <div>
    <label>Categoría</label>
    <select name="categoria_id" id="categoria_id" class="border rounded w-full p-2" required>
      <option value="">Seleccione</option>
      <?php while ($row = mysqli_fetch_assoc($categorias)): ?>
        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div>
    <label>Subcategoría</label>
    <select name="subcategoria_id" id="subcategoria_id" class="border rounded w-full p-2" required>
      <option value="">Seleccione categoría primero</option>
    </select>
  </div>

  <div>
    <label>Marca</label>
    <select name="marca_id" id="marca_id" class="border rounded w-full p-2" required>
      <option value="">Seleccione</option>
      <?php while ($row = mysqli_fetch_assoc($marcas)): ?>
        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <div>
    <label>Características (separadas por coma)</label>
    <textarea name="caracteristicas" id="caracteristicas" class="border rounded w-full p-2" placeholder="Ej: Resistente, Ligero"></textarea>
  </div>

  <div>
    <label>URL Ficha Técnica</label>
    <input type="url" name="ficha_tecnica_url" id="ficha_tecnica_url" class="border rounded w-full p-2" placeholder="https://ejemplo.com/ficha.pdf">
  </div>

  <div>
    <label>Imagen</label>
    <input type="file" name="imagen" id="imagen" class="border rounded w-full p-2" accept=".jpg,.jpeg,.png,.gif,.webp">
  </div>

  <div class="col-span-2 flex justify-end">
    <button type="submit" id="btnEnviar" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
      Agregar producto
    </button>
    <button type="button" id="btnCancelarEdicion" class="hidden ml-4 px-6 py-2 rounded border border-gray-400 hover:bg-gray-200">
      Cancelar
    </button>
  </div>

</form>

<h2 class="text-xl font-bold mt-8 mb-4">Productos en lista temporal</h2>

<table id="tablaProductos" class="min-w-full bg-white border rounded shadow-md">
<thead>
  <tr class="bg-gray-200">
    <th class="p-2 border text-center">#</th>
    <th class="p-2 border">Nombre</th>
    <th class="p-2 border">Descripción</th>
    <th class="p-2 border">Categoría</th>
    <th class="p-2 border">Subcategoría</th>
    <th class="p-2 border">Marca</th>
    <th class="p-2 border">Características</th>
    <th class="p-2 border text-center">Ficha Técnica</th>
    <th class="p-2 border text-center">Imagen</th>
    <th class="p-2 border text-center">Editar</th>
    <th class="p-2 border text-center">Eliminar</th>
  </tr>
</thead>
<tbody>
<?php if (!empty($_SESSION['productos_temp'])): ?>
  <?php foreach ($_SESSION['productos_temp'] as $index => $prod): ?>
    <tr data-index="<?= $index ?>">
      <td class="border p-2 text-center"><?= $index + 1 ?></td>
      <td class="border p-2"><?= htmlspecialchars($prod['nombre']) ?></td>
      <td class="border p-2"><?= htmlspecialchars($prod['descripcion']) ?></td>
      <td class="border p-2"><?= htmlspecialchars($prod['categoria_nombre'] ?? $prod['categoria_id']) ?></td>
      <td class="border p-2"><?= htmlspecialchars($prod['subcategoria_nombre'] ?? '-') ?></td>
      <td class="border p-2"><?= htmlspecialchars($prod['marca_nombre'] ?? $prod['marca_id']) ?></td>
      <td class="border p-2"><?= htmlspecialchars($prod['caracteristicas']) ?></td>
      <td class="border p-2 text-center">
        <?php if (!empty($prod['ficha_tecnica_url'])): ?>
          <a href="<?= htmlspecialchars($prod['ficha_tecnica_url']) ?>" target="_blank" title="Ver ficha técnica" class="text-blue-600 hover:text-blue-800">
            🔗
          </a>
        <?php else: ?>
          -
        <?php endif; ?>
      </td>
      <td class="border p-2 text-center"><?= htmlspecialchars($prod['imagen']) ?></td>
      <td class="border p-2 text-center">
        <button class="text-green-600 font-bold editar-btn" data-index="<?= $index ?>">Editar</button>
      </td>
      <td class="border p-2 text-center">
        <button class="text-red-600 font-bold eliminar-btn" data-index="<?= $index ?>">Borrar</button>
      </td>
    </tr>
  <?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>

<?php if (!empty($_SESSION['productos_temp'])): ?>
  <div class="mt-6 flex justify-end">
    <button id="btnGuardarLote" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Guardar todos en BD</button>
  </div>
<?php endif; ?>

<script>
// Variables y elementos
const form = document.getElementById('formAgregar');
const btnEnviar = document.getElementById('btnEnviar');
const btnCancelarEdicion = document.getElementById('btnCancelarEdicion');
const indexEditarInput = document.getElementById('index_editar');

// Cargar subcategorías al cambiar categoría (AJAX)
$('#categoria_id').on('change', function(){
  let categoriaId = $(this).val();
  if(!categoriaId){
    $('#subcategoria_id').html('<option value="">Seleccione categoría primero</option>');
    return;
  }
  $.ajax({
    url: 'obtener_subcategorias_por_categoria.php',
    method: 'GET',
    data: { categoria_id: categoriaId },
    success: function(data){
      let options = '<option value="">(Ninguna)</option>';
      data.forEach(subcat => {
        options += `<option value="${subcat.id}">${subcat.nombre}</option>`;
      });
      $('#subcategoria_id').html(options);
    },
    error: function(){
      Swal.fire('Error', 'No se pudieron cargar las subcategorías', 'error');
    }
  });
});

// Cargar datos en el formulario para editar
function cargarDatosEnFormulario(prod, index) {
  document.getElementById('nombre').value = prod.nombre;
  document.getElementById('descripcion').value = prod.descripcion;
  document.getElementById('categoria_id').value = prod.categoria_id;
  document.getElementById('marca_id').value = prod.marca_id;
  document.getElementById('subcategoria_id').value = prod.subcategoria_id ?? '';
  document.getElementById('caracteristicas').value = prod.caracteristicas;
  document.getElementById('ficha_tecnica_url').value = prod.ficha_tecnica_url || '';
  // Imagen no puede precargarse en input file por seguridad
  indexEditarInput.value = index;
  btnEnviar.textContent = 'Actualizar producto';
  btnCancelarEdicion.classList.remove('hidden');
}

// Resetear formulario a modo agregar
function resetFormulario() {
  form.reset();
  indexEditarInput.value = '';
  btnEnviar.textContent = 'Agregar producto';
  btnCancelarEdicion.classList.add('hidden');
}

// Cargar tabla recarga la página para mantener simple (puedes mejorar si quieres AJAX)
function cargarTabla() {
  location.reload();
}

// Evento editar
document.addEventListener('click', function(e){
  if(e.target.classList.contains('editar-btn')){
    const index = e.target.dataset.index;
    fetch('obtener_producto_temp_indice.php?index=' + index)
      .then(r => r.json())
      .then(prod => {
        if(prod){
          cargarDatosEnFormulario(prod, index);
          window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
          Swal.fire('Error', 'No se encontró el producto para editar', 'error');
        }
      });
  }
});

// Evento cancelar edición
btnCancelarEdicion.addEventListener('click', function(){
  resetFormulario();
});

// Enviar formulario con AJAX
form.addEventListener('submit', function(e){
  e.preventDefault();

  const formData = new FormData(form);

  // Validar campos obligatorios (excepto imagen si es edición)
  if(!formData.get('nombre') || !formData.get('descripcion') || !formData.get('categoria_id') || !formData.get('marca_id')) {
    alert('Por favor completa todos los campos obligatorios.');
    return;
  }

  // Si es agregar, imagen es obligatoria
  if(!formData.get('index_editar') && !formData.get('imagen').name){
    alert('Por favor selecciona una imagen.');
    return;
  }

  fetch('guardar_producto_temp.php', {
    method: 'POST',
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    if(data.success){
      Swal.fire('Éxito', data.mensaje, 'success');
      resetFormulario();
      cargarTabla();
    } else {
      Swal.fire('Error', data.mensaje, 'error');
    }
  })
  .catch(() => {
    Swal.fire('Error', 'Error al conectar con el servidor', 'error');
  });
});

// Inicializar DataTable con paginación y búsqueda
$(document).ready(function() {
  $('#tablaProductos').DataTable({
    pageLength: 5,
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

// Guardar todos en BD
document.getElementById('btnGuardarLote')?.addEventListener('click', function(){
  fetch('guardar_productos_lote.php', {
    method: 'POST'
  })
  .then(r => r.json())
  .then(data => {
    if(data.success){
      Swal.fire('Éxito', data.mensaje, 'success').then(() => {
        location.reload();
      });
    } else {
      Swal.fire('Error', data.mensaje, 'error');
    }
  })
  .catch(() => Swal.fire('Error', 'Error al conectar con el servidor', 'error'));
});

// Función para activar eventos eliminar
function activarEventosEliminar(){
  document.querySelectorAll('.eliminar-btn').forEach(btn => {
    btn.onclick = function(){
      const index = this.dataset.index;
      fetch('eliminar_producto_temp.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({index: parseInt(index)})
      })
      .then(r => r.json())
      .then(data => {
        if(data.success){
          Swal.fire('Eliminado', data.mensaje, 'success').then(() => cargarTabla());
        } else {
          Swal.fire('Error', data.mensaje, 'error');
        }
      })
      .catch(() => Swal.fire('Error', 'Error al conectar con el servidor', 'error'));
    }
  });
}
activarEventosEliminar();
</script>

</body>
</html>
