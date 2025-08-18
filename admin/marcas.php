  <?php
  session_start();
  require_once './includes/conexion.php';
  ?>

  <!DOCTYPE html>
  <html lang="es">
  <head>
  <meta charset="UTF-8" />
  <title>Gestión de Marcas</title>

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

  <h1 class="text-3xl font-bold mb-6">Catálogo de Marcas</h1>

  <!-- Botón agregar -->
  <button id="btnAgregar" class="mb-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Agregar Marca</button>

  <!-- Tabla -->
  <table id="tablaMarcas" class="min-w-full bg-white border rounded shadow-md">
  <thead>
    <tr class="bg-gray-200">
      <th class="p-2 border border-gray-300">ID</th>
      <th class="p-2 border border-gray-300">Nombre</th>
      <th class="p-2 border border-gray-300">Descripción</th>
      <th class="p-2 border border-gray-300">Imagen</th>
      <th class="p-2 border border-gray-300 text-center">Editar</th>
      <th class="p-2 border border-gray-300 text-center">Eliminar</th>
    </tr>
  </thead>
  <tbody></tbody>
  </table>

  <!-- Modal formulario -->
  <div id="modalFormulario" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white rounded shadow-lg p-6 w-full max-w-lg relative">
      <h2 id="modalTitulo" class="text-xl font-bold mb-4">Agregar Marca</h2>
      <form id="formMarca" enctype="multipart/form-data">
        <input type="hidden" id="marca_id" name="id">
        <input type="hidden" id="imagen_actual" name="imagen_actual">

        <div class="mb-4">
          <label for="nombre" class="block mb-1 font-semibold">Nombre</label>
          <input type="text" id="nombre" name="nombre" class="border rounded w-full p-2" required />
        </div>

        <div class="mb-4">
          <label for="descripcion" class="block mb-1 font-semibold">Descripción</label>
          <textarea id="descripcion" name="descripcion" class="border rounded w-full p-2" rows="3" required></textarea>
        </div>

        <div class="mb-4">
          <label for="imagen" class="block mb-1 font-semibold">Imagen (opcional)</label>
          <input type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.gif,.webp" />
        </div>

        <div class="flex justify-end space-x-4">
          <button type="button" id="btnCerrarModal" class="px-4 py-2 rounded border border-gray-400 hover:bg-gray-200">Cancelar</button>
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  $(document).ready(function() {
    const tabla = $('#tablaMarcas').DataTable({
      pageLength: 5,
      lengthMenu: [5, 10, 25],
      language: {
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ marcas",
        info: "Mostrando _START_ a _END_ de _TOTAL_ marcas",
        paginate: { next: "Siguiente", previous: "Anterior" },
        zeroRecords: "No se encontraron marcas"
      }
    });

    function cargarMarcas() {
      $.post('marcas_acciones.php', { action: 'listar' }, function(res) {
        if (res.success) {
          tabla.clear().draw();
          res.data.forEach(marca => {
            tabla.row.add([
              marca.id,
              marca.nombre,
              marca.descripcion,
              marca.imagen ? `<img src="../assets/img/marcas/${marca.imagen}" class="h-12 mx-auto" />` : '-',
              `<button class="editar-btn text-green-600 font-bold" data-id="${marca.id}">Editar</button>`,
              `<button class="eliminar-btn text-red-600 font-bold" data-id="${marca.id}">Borrar</button>`
            ]).draw(false);
          }); // <-- aquí faltaba cerrar forEach
        }
      }, 'json');
    }


    cargarMarcas();

    $('#btnAgregar').click(function() {
      $('#modalTitulo').text('Agregar Marca');
      $('#formMarca')[0].reset();
      $('#marca_id').val('');
      $('#imagen_actual').val('');
      $('#modalFormulario').removeClass('hidden');
    });

    $('#btnCerrarModal').click(function() {
      $('#modalFormulario').addClass('hidden');
    });

    $('#formMarca').submit(function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      const id = $('#marca_id').val();
      formData.append('action', id ? 'editar' : 'agregar');

      $.ajax({
        url: 'marcas_acciones.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(res) {
          if (res.success) {
            Swal.fire('Éxito', res.mensaje, 'success').then(() => {
              $('#modalFormulario').addClass('hidden');
              cargarMarcas();
            });
          } else {
            Swal.fire('Error', res.mensaje, 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Error al conectar con el servidor', 'error');
        }
      });
    });

    $('#tablaMarcas tbody').on('click', '.editar-btn', function() {
      const id = $(this).data('id');
      $.post('marcas_acciones.php', { action: 'listar' }, function(res) {
        if (res.success) {
          const marca = res.data.find(m => m.id == id);
          if (marca) {
            $('#modalTitulo').text('Editar Marca');
            $('#marca_id').val(marca.id);
            $('#nombre').val(marca.nombre);
            $('#descripcion').val(marca.descripcion);
            $('#imagen_actual').val(marca.imagen);
            $('#modalFormulario').removeClass('hidden');
          }
        }
      }, 'json');
    });

    $('#tablaMarcas tbody').on('click', '.eliminar-btn', function() {
      const id = $(this).data('id');

      Swal.fire({
        title: '¿Eliminar marca?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then(result => {
        if (result.isConfirmed) {
          $.post('marcas_acciones.php', { action: 'eliminar', id }, function(res) {
            if (res.success) {
              Swal.fire('Eliminado', res.mensaje, 'success').then(() => cargarMarcas());
            } else {
              Swal.fire('Error', res.mensaje, 'error');
            }
          }, 'json');
        }
      });
    });
  });
  </script>

  </body>
  </html>
